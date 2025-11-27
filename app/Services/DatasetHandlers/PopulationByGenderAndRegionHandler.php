<?php

namespace App\Services\DatasetHandlers;

use App\Models\BpsDataset;
use Illuminate\Support\Facades\DB;

class PopulationByGenderAndRegionHandler implements DatasetHandlerInterface
{
    protected $dataset;
    protected $latestYear;

    public function __construct(BpsDataset $dataset)
    {
        $this->dataset = $dataset;
        $this->latestYear = $dataset->values()->max('year');
    }

    // --- BAGIAN 1: TABLE DATA (Tampilkan Semua Tahun) ---
    public function getTableData(): array
    {
        // 1. Ambil SEMUA data, urutkan tahun dari yang terbaru
        $allValues = $this->dataset->values()
            ->orderBy('year', 'desc')
            ->get();

        // 2. Kelompokkan berdasarkan Tahun
        $groupedByYear = $allValues->groupBy('year');

        $rows = [];

        // 3. Loop setiap tahun
        foreach ($groupedByYear as $year => $itemsInYear) {
            // Kelompokkan berdasarkan Kecamatan
            $groupedByRegion = $itemsInYear->groupBy('var_label');

            foreach ($groupedByRegion as $region => $data) {
                $lakiLaki = $data->firstWhere('turvar_label', 'Laki-laki')->value ?? 0;
                $perempuan = $data->firstWhere('turvar_label', 'Perempuan')->value ?? 0;

                $rows[] = [
                    'Tahun'     => $year, // Kolom Baru
                    'Kecamatan' => $region,
                    'Laki-laki' => $lakiLaki,
                    'Perempuan' => $perempuan,
                    'Jumlah'    => $lakiLaki + $perempuan,
                ];
            }
        }

        return [
            'headers' => ["Tahun", "Kecamatan", "Laki-laki", "Perempuan", "Jumlah"],
            'rows' => $rows,
        ];
    }

    // --- BAGIAN 2: CHART DATA (Hanya Tahun Terbaru) ---
    public function getChartData(): array
    {
        // Ambil data HANYA untuk tahun terbaru agar grafik Top 5 valid
        // (Jangan ambil dari getTableData karena sekarang isinya banyak tahun)
        $values = $this->dataset->values()->where('year', $this->latestYear)->get();
        $grouped = $values->groupBy('var_label');

        $chartItems = [];
        foreach ($grouped as $region => $data) {
            $lakiLaki = $data->firstWhere('turvar_label', 'Laki-laki')->value ?? 0;
            $perempuan = $data->firstWhere('turvar_label', 'Perempuan')->value ?? 0;
            $chartItems[] = [
                'Kecamatan' => $region,
                'Jumlah' => $lakiLaki + $perempuan
            ];
        }

        // Ambil Top 5 Kecamatan Terbanyak
        $top5 = collect($chartItems)->sortByDesc('Jumlah')->take(5);

        return [
            'type' => 'bar',
            'title' => '5 Kecamatan Terpadat (' . $this->latestYear . ')',
            'labels' => $top5->pluck('Kecamatan')->toArray(),
            'data' => $top5->pluck('Jumlah')->toArray(),
        ];
    }

    // --- BAGIAN 3: INSIGHT DATA (Tetap Sama) ---
    public function getInsightData(): array
    {
        $values = $this->dataset->values()->where('year', $this->latestYear)->get();

        if ($values->isEmpty()) {
            return [['title' => 'Info', 'value' => '-', 'description' => 'Data tidak tersedia.']];
        }

        $totalLakiLaki = $values->where('turvar_label', 'Laki-laki')->sum('value');
        $totalPerempuan = $values->where('turvar_label', 'Perempuan')->sum('value');
        $totalPenduduk = $totalLakiLaki + $totalPerempuan;
        $sexRatio = ($totalPerempuan > 0) ? round(($totalLakiLaki / $totalPerempuan) * 100) : 0;

        return [
            [
                'title' => 'Total Penduduk',
                'value' => number_format($totalPenduduk) . ' Jiwa',
                'description' => 'Berdasarkan data tahun ' . $this->latestYear,
            ],
            [
                'title' => 'Rasio Jenis Kelamin',
                'value' => $sexRatio,
                'description' => 'Terdapat ' . $sexRatio . ' laki-laki untuk setiap 100 perempuan.',
            ],
        ];
    }

    // --- BAGIAN 4: HISTORY DATA (Grafik Garis Tren) ---
    public function getHistoryData(): array
    {
        $allValues = $this->dataset->values()->orderBy('year')->get();

        if ($allValues->isEmpty()) return [];

        // Hitung total penduduk per tahun
        $yearlyTotals = $allValues->groupBy('year')->map(function ($items) {
            // Jumlahkan Laki-laki + Perempuan
            return $items->whereIn('turvar_label', ['Laki-laki', 'Perempuan'])->sum('value');
        });

        return [
            'type' => 'line',
            'title' => 'Tren Total Penduduk Kab. Kebumen',
            'labels' => $yearlyTotals->keys()->toArray(),
            'datasets' => [
                [
                    'label' => 'Total Penduduk',
                    'data' => $yearlyTotals->values()->toArray(),
                ],
            ],
        ];
    }
}
