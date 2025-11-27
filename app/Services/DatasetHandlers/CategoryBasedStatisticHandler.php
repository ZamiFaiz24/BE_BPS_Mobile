<?php

namespace App\Services\DatasetHandlers;

use App\Models\BpsDataset;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CategoryBasedStatisticHandler implements DatasetHandlerInterface
{
    protected BpsDataset $dataset;
    protected ?int $year;
    protected string $categoryColumn;

    // Properti baru
    protected string $primaryUnit = 'Nilai'; // Unit utama untuk grafik (misal: Persen)

    public function __construct(BpsDataset $dataset, $year = null)
    {
        $this->dataset = $dataset;
        // Gunakan tahun dari request, atau default ke tahun terbaru
        $this->year = $year ?: $dataset->values()->max('year');

        // Deteksi kolom kategori (Sama seperti sebelumnya)
        $sample = $dataset->values()->first();
        if ($sample) {
            $vervarCount = $dataset->values()->pluck('vervar_label')->unique()->count();
            $turvarCount = $dataset->values()->pluck('turvar_label')->unique()->count();
            $this->categoryColumn = ($turvarCount > $vervarCount) ? 'turvar_label' : 'vervar_label';
        } else {
            $this->categoryColumn = 'turvar_label';
        }

        // DETEKSI UNIT UTAMA (PENTING!)
        // Jika judul mengandung "Persentase", kita prioritaskan data Persen untuk grafik
        if (Str::contains(strtolower($dataset->dataset_name), 'persentase')) {
            $this->primaryUnit = 'Persen';
        } else {
            // Jika tidak, cari unit terbanyak selain 'Persen'
            $this->primaryUnit = 'Nilai'; // Fallback
        }
    }

    public function getTableData(): array
    {
        // 1. Ambil data sesuai filter tahun (jika ada)
        $query = $this->dataset->values();
        if ($this->year) {
            $query->where('year', $this->year);
        }

        // Urutkan: Tahun Desc, lalu Kategori Asc
        $allData = $query->orderBy('year', 'desc')
            ->orderBy($this->categoryColumn, 'asc')
            ->get();

        if ($allData->isEmpty()) return ['headers' => [], 'rows' => []];

        // 2. Deteksi Unit apa saja yang ada (misal: "Orang", "Persen")
        // Kita paksa 'Persen' ada di belakang biar rapi
        $units = $allData->pluck('unit')->unique()->filter()->values()->toArray();
        // Kalau kosong, anggap 'Nilai'
        if (empty($units)) $units = ['Nilai'];

        // Header: Tahun, Kategori, [Unit 1], [Unit 2]...
        $headers = array_merge(['Tahun', 'Kategori'], $units);

        // 3. Grouping Data
        $rows = [];
        $groupedByYear = $allData->groupBy('year');

        foreach ($groupedByYear as $year => $itemsInYear) {
            $groupedByCategory = $itemsInYear->groupBy($this->categoryColumn);

            foreach ($groupedByCategory as $category => $items) {
                // Jangan tampilkan baris "Jumlah" di tabel Kategori (karena itu total)
                if (strtolower($category) === 'jumlah' || strtolower($category) === 'total') {
                    continue;
                }

                $row = ['Tahun' => $year, 'Kategori' => $category];

                foreach ($units as $unit) {
                    // Cari item dengan unit tersebut
                    $item = $items->first(function ($val) use ($unit) {
                        return strtolower($val->unit) == strtolower($unit)
                            || ($unit == 'Nilai'); // Fallback
                    });

                    // Format angka: Jika desimal pakai koma, jika ribuan pakai titik
                    // Tapi untuk JSON API biarkan angka mentah (double) biar Android yang format
                    $row[$unit] = $item ? $item->value : null;
                }
                $rows[] = $row;
            }
        }

        return ['headers' => $headers, 'rows' => $rows];
    }

    public function getChartData(): array
    {
        // Chart HANYA menampilkan data untuk tahun yang dipilih (Snapshot)
        $query = $this->dataset->values();
        if ($this->year) $query->where('year', $this->year);

        // Filter Unit Utama Saja! (Agar grafik tidak campur aduk)
        // Jika judul "Persentase", ambil yang unitnya 'Persen'
        // Jika bukan, ambil yang unitnya BUKAN 'Persen' (atau ambil semuanya jika cuma 1 unit)

        $chartDataRaw = $query->get()->filter(function ($item) {
            // Jangan masukkan kategori "Jumlah" ke grafik (karena itu total, nanti grafiknya timpang)
            if (in_array(strtolower($item->{$this->categoryColumn}), ['jumlah', 'total'])) {
                return false;
            }

            // Filter Unit
            if ($this->primaryUnit === 'Persen') {
                // Cek unit di DB (kadang ditulis 'Persen', '%', atau kosong tapi nilainya < 100)
                return strtolower($item->unit) === 'persen' || $item->unit === '%';
            } else {
                // Ambil yang BUKAN persen
                return strtolower($item->unit) !== 'persen' && $item->unit !== '%';
            }
        });

        // Jika hasil filter kosong (mungkin unitnya null semua), ambil saja semuanya kecuali Jumlah
        if ($chartDataRaw->isEmpty()) {
            $chartDataRaw = $query->get()->filter(function ($item) {
                return !in_array(strtolower($item->{$this->categoryColumn}), ['jumlah', 'total']);
            });
        }

        // Urutkan berdasarkan nilai (agar grafik rapi urut besar ke kecil)
        $chartDataSorted = $chartDataRaw->sortByDesc('value');

        return [
            'type' => 'bar',
            'title' => $this->dataset->dataset_name . " (" . $this->year . ")",
            'labels' => $chartDataSorted->pluck($this->categoryColumn)->toArray(),
            'datasets' => [
                [
                    // Gunakan primaryUnit sebagai label dataset
                    'label' => $this->primaryUnit === 'Nilai' ? 'Jumlah' : $this->primaryUnit,
                    'data' => $chartDataSorted->pluck('value')->values()->toArray(),
                ]
            ],
            // Kirim data mentah di luar datasets juga (untuk kompatibilitas ChartSection lama)
            'data' => $chartDataSorted->pluck('value')->values()->toArray()
        ];
    }

    public function getInsightData(): array
    {
        // Gunakan logika chart data agar konsisten (tanpa "Jumlah")
        $chartData = $this->getChartData();
        $labels = $chartData['labels'];
        $values = $chartData['datasets'][0]['data'] ?? [];

        if (empty($values)) {
            return [['title' => 'Info', 'value' => '-', 'description' => 'Data tidak tersedia.']];
        }

        // Cari Max & Min
        $maxVal = max($values);
        $minVal = min($values);

        // Cari Index-nya untuk dapat label kategori
        $maxIndex = array_search($maxVal, $values);
        $minIndex = array_search($minVal, $values);

        $maxLabel = $labels[$maxIndex];
        $minLabel = $labels[$minIndex];

        $insights = [];

        $insights[] = [
            'title' => 'Dominan',
            'value' => $maxLabel,
            'description' => "Kategori tertinggi adalah $maxLabel dengan nilai " . number_format($maxVal, 2) . ($this->primaryUnit == 'Persen' ? '%' : ''),
        ];

        if (count($values) > 1) {
            $insights[] = [
                'title' => 'Terendah',
                'value' => $minLabel,
                'description' => "Kategori terendah adalah $minLabel dengan nilai " . number_format($minVal, 2) . ($this->primaryUnit == 'Persen' ? '%' : ''),
            ];
        }

        return $insights;
    }

    public function getHistoryData(): array
    {
        // Grafik tren total (mengambil kategori 'Jumlah' saja jika ada)
        $historyData = $this->dataset->values()
            ->where(function ($q) {
                $q->where($this->categoryColumn, 'Jumlah')
                    ->orWhere($this->categoryColumn, 'Total');
            })
            ->orderBy('year')
            ->get();

        // Jika tidak ada baris "Jumlah", hitung manual sum-nya
        if ($historyData->isEmpty()) {
            $historyData = $this->dataset->values()
                ->where('unit', '!=', 'Persen') // Jangan jumlahkan persen
                ->get()
                ->groupBy('year')
                ->map(function ($items) {
                    return [
                        'year' => $items->first()->year,
                        'value' => $items->sum('value')
                    ];
                })
                ->sortBy('year')
                ->values();
        }

        return [
            'type' => 'line',
            'title' => 'Tren Total',
            'labels' => $historyData->pluck('year')->toArray(),
            'datasets' => [['label' => 'Total', 'data' => $historyData->pluck('value')->toArray()]]
        ];
    }
}
