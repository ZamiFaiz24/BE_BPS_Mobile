<?php

namespace App\Services\DatasetHandlers;

use App\Models\BpsDataset;
use Illuminate\Support\Collection;

class PopulationByGenderAndRegionHandler implements DatasetHandlerInterface
{
    protected BpsDataset $dataset;
    protected ?int $year;

    // Konfigurasi Kolom (Sesuaikan dengan data Anda)
    // Biasanya Kecamatan ada di 'var_label' atau 'vervar_label'
    protected string $regionColumn = 'var_label';
    protected string $genderColumn = 'turvar_label';

    public function __construct(BpsDataset $dataset, $year = null)
    {
        $this->dataset = $dataset;
        $this->year = $year ?: $dataset->values()->max('year');

        // Deteksi kolom dinamis
        $sample = $dataset->values()->first();
        if ($sample) {
            // Jika turvar isinya Laki/Perempuan, berarti vervar adalah Kecamatan
            if (in_array($sample->turvar_label, ['Laki-laki', 'Perempuan'])) {
                $this->genderColumn = 'turvar_label';
                $this->regionColumn = 'vervar_label';
            } else {
                $this->genderColumn = 'vervar_label';
                $this->regionColumn = 'turvar_label';
            }
        }
    }

    public function getTableData(): array
    {
        // Query data tahun terpilih
        $query = $this->dataset->values();
        if ($this->year) $query->where('year', $this->year);
        $allData = $query->get();

        // Group by Kecamatan
        $grouped = $allData->groupBy($this->regionColumn);
        $rows = [];

        foreach ($grouped as $region => $items) {
            // Skip baris "Jumlah" atau "Total" Kabupaten
            if (in_array(strtolower($region), ['jumlah', 'total', 'kabupaten kebumen'])) continue;

            $laki = $items->firstWhere($this->genderColumn, 'Laki-laki')->value ?? 0;
            $perempuan = $items->firstWhere($this->genderColumn, 'Perempuan')->value ?? 0;
            $total = $laki + $perempuan;

            $rows[] = [
                'Tahun' => $this->year,
                'Kecamatan' => $region,
                'Laki-laki' => $laki,
                'Perempuan' => $perempuan,
                'Total' => $total
            ];
        }

        // Sort berdasarkan nama kecamatan
        usort($rows, fn($a, $b) => strcmp($a['Kecamatan'], $b['Kecamatan']));

        return [
            'headers' => ['Tahun', 'Kecamatan', 'Laki-laki', 'Perempuan', 'Total'],
            'rows' => $rows
        ];
    }

    public function getChartData(): array
    {
        $mode = request('mode', 'gender');

        $tableData = $this->getTableData()['rows'];
        $collection = collect($tableData);

        if ($mode === 'region') {
            // === MODE 1: BAR CHART KECAMATAN ===
            $sorted = $collection->sortByDesc('Total')->take(10);

            if ($sorted->isEmpty()) {
                return ['type' => 'bar', 'labels' => [], 'datasets' => []];
            }

            return [
                'type' => 'bar',
                'title' => "10 Kecamatan Terpadat ({$this->year})",
                'labels' => $sorted->pluck('Kecamatan')->values()->toArray(),
                'datasets' => [
                    [
                        'label' => 'Total Penduduk',
                        'data' => $sorted->pluck('Total')->values()->toArray()
                    ]
                ]
            ];
        } else {
            // === MODE 2: PIE CHART GENDER (Default) ===
            // Hitung Total Laki & Perempuan se-Kabupaten
            $totalLaki = $collection->sum('Laki-laki');
            $totalPerempuan = $collection->sum('Perempuan');

            return [
                'type' => 'pie', // Pie Chart
                'title' => "Komposisi Gender ({$this->year})",
                'labels' => ['Laki-laki', 'Perempuan'],
                'datasets' => [
                    [
                        'label' => 'Jiwa',
                        'data' => [$totalLaki, $totalPerempuan]
                    ]
                ]
            ];
        }
    }

    public function getInsightData(): array
    {
        // 1. Cek Mode Tampilan (Gender / Region)
        $mode = request('mode', 'gender');

        // 2. Ambil Data Dasar
        $query = $this->dataset->values();
        if ($this->year) $query->where('year', $this->year);
        $allData = $query->get();

        // --- SKENARIO 1: MODE KECAMATAN (REGION) ---
        if ($mode === 'region') {
            // Kita butuh data total per kecamatan
            $regionStats = $allData->groupBy($this->regionColumn)
                ->map(function ($items) {
                    // Hitung total L+P di kecamatan ini
                    return $items->whereIn($this->genderColumn, ['Laki-laki', 'Perempuan'])->sum('value');
                })
                // Hapus baris "Total" atau "Kabupaten" agar tidak dianggap sebagai kecamatan
                ->reject(function ($value, $key) {
                    return in_array(strtolower($key), ['jumlah', 'total', 'kabupaten kebumen']);
                })
                ->sortDesc(); // Urutkan dari terbesar

            if ($regionStats->isEmpty()) return [];

            // Ambil Terpadat & Tersepi
            $mostPopulatedRegion = $regionStats->keys()->first();
            $mostPopulatedValue = $regionStats->first();

            $leastPopulatedRegion = $regionStats->keys()->last();
            $leastPopulatedValue = $regionStats->last();

            return [
                [
                    'title' => 'Kecamatan Terpadat',
                    'value' => $mostPopulatedRegion,
                    'description' => "Memiliki jumlah penduduk terbanyak yaitu " . number_format($mostPopulatedValue) . " jiwa."
                ],
                [
                    'title' => 'Kecamatan Tersepi',
                    'value' => $leastPopulatedRegion,
                    'description' => "Memiliki jumlah penduduk paling sedikit yaitu " . number_format($leastPopulatedValue) . " jiwa."
                ],
                [
                    'title' => 'Rata-rata Penduduk',
                    'value' => number_format($regionStats->average()) . " Jiwa",
                    'description' => "Rata-rata jumlah penduduk per kecamatan di Kebumen."
                ]
            ];
        }

        // --- SKENARIO 2: MODE GENDER (DEFAULT) ---
        else {
            // Logika lama Anda (sudah benar)
            $totalLaki = $allData->where($this->genderColumn, 'Laki-laki')->sum('value');
            $totalPerempuan = $allData->where($this->genderColumn, 'Perempuan')->sum('value');
            $totalKabupaten = $totalLaki + $totalPerempuan;

            $sexRatio = ($totalPerempuan > 0) ? round(($totalLaki / $totalPerempuan) * 100, 2) : 0;

            return [
                [
                    'title' => 'Total Penduduk',
                    'value' => number_format($totalKabupaten) . " Jiwa",
                    'description' => "Total penduduk Kabupaten Kebumen tahun {$this->year}."
                ],
                [
                    'title' => 'Rasio Gender',
                    'value' => $sexRatio,
                    'description' => "Terdapat $sexRatio laki-laki untuk setiap 100 perempuan."
                ],
                [
                    'title' => 'Dominasi Gender',
                    'value' => ($totalLaki > $totalPerempuan) ? 'Laki-laki' : 'Perempuan',
                    'description' => "Penduduk " . (($totalLaki > $totalPerempuan) ? 'Laki-laki' : 'Perempuan') . " lebih banyak " . number_format(abs($totalLaki - $totalPerempuan)) . " jiwa."
                ]
            ];
        }
    }

    public function getHistoryData(): array
    {
        // Grafik Tren Penduduk Total Kabupaten (Time Series)
        // Jumlahkan L+P per tahun
        $history = $this->dataset->values()
            ->get()
            ->groupBy('year')
            ->map(function ($items) {
                return $items->whereIn($this->genderColumn, ['Laki-laki', 'Perempuan'])->sum('value');
            })
            ->sortKeys();

        return [
            'type' => 'line',
            'title' => 'Tren Populasi',
            'labels' => $history->keys()->toArray(),
            'datasets' => [
                ['label' => 'Total Penduduk', 'data' => $history->values()->toArray()]
            ]
        ];
    }
}
