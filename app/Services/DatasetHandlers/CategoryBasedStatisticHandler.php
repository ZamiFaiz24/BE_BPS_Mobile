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
    protected string $primaryUnit = 'Nilai';

    public function __construct(BpsDataset $dataset, $year = null)
    {
        $this->dataset = $dataset;
        // Gunakan tahun dari request, atau default ke tahun terbaru
        $this->year = $year ?: $dataset->values()->max('year');

        // 1. Deteksi kolom kategori (turvar vs vervar)
        $sample = $dataset->values()->first();
        if ($sample) {
            $vervarCount = $dataset->values()->pluck('vervar_label')->unique()->count();
            $turvarCount = $dataset->values()->pluck('turvar_label')->unique()->count();
            // Ambil kolom yang variasi datanya lebih banyak sebagai kategori
            $this->categoryColumn = ($turvarCount > $vervarCount) ? 'turvar_label' : 'vervar_label';
        } else {
            $this->categoryColumn = 'turvar_label';
        }

        // 2. Deteksi Unit Utama berdasarkan Judul Dataset
        // Jika judul mengandung "Persentase" atau "Distribusi", prioritaskan unit Persen
        if (
            Str::contains(strtolower($dataset->dataset_name), 'persentase') ||
            Str::contains(strtolower($dataset->dataset_name), 'distribusi')
        ) {
            $this->primaryUnit = 'Persen';
        } else {
            $this->primaryUnit = 'Nilai';
        }
    }

    public function getTableData(): array
    {
        // Query Dasar
        $query = $this->dataset->values();

        // Hormati filter tahun (jika ada)
        if ($this->year) {
            $query->where('year', $this->year);
        }

        // Ambil data (Urutkan Tahun Desc, Kategori Asc)
        $allData = $query->orderBy('year', 'desc')
            ->orderBy($this->categoryColumn, 'asc')
            ->get();

        if ($allData->isEmpty()) return ['headers' => [], 'rows' => []];

        // Deteksi Unit yang tersedia
        $units = $allData->pluck('unit')->unique()->filter()->values()->toArray();
        if (empty($units)) $units = ['Nilai'];

        // Header Tabel: Tahun, Kategori, [Unit1], [Unit2]...
        $headers = array_merge(['Tahun', 'Kategori'], $units);

        $rows = [];
        $groupedByYear = $allData->groupBy('year');

        foreach ($groupedByYear as $year => $itemsInYear) {
            $groupedByCategory = $itemsInYear->groupBy($this->categoryColumn);

            foreach ($groupedByCategory as $category => $items) {
                $row = ['Tahun' => $year, 'Kategori' => $category];

                foreach ($units as $unit) {
                    // Cari item yang cocok dengan unit ini
                    // (Logika: Cocokkan string unit, atau fallback ke 'Nilai')
                    $item = $items->first(function ($val) use ($unit) {
                        return strtolower($val->unit) == strtolower($unit)
                            || ($unit == 'Nilai');
                    });

                    $row[$unit] = $item ? $item->value : null;
                }
                $rows[] = $row;
            }
        }

        return ['headers' => $headers, 'rows' => $rows];
    }

    public function getChartData(): array
    {
        // 1. Ambil data HANYA untuk tahun terpilih (Snapshot)
        $query = $this->dataset->values();
        if ($this->year) $query->where('year', $this->year);
        $allItems = $query->get();

        // 2. FILTER DATA UNTUK GRAFIK
        $chartDataRaw = $allItems->filter(function ($item) {
            $kategori = strtolower($item->{$this->categoryColumn});
            $unit = strtolower($item->unit);

            // ATURAN A: BUANG KATEGORI "JUMLAH" / "TOTAL" 
            // (Agar grafik perbandingan proporsional)
            if (in_array($kategori, ['jumlah', 'total'])) {
                return false;
            }

            // ATURAN B: Pilih Unit yang sesuai dengan Judul
            if ($this->primaryUnit === 'Persen') {
                return $unit === 'persen' || $unit === '%';
            } else {
                // Ambil yang BUKAN persen
                return $unit !== 'persen' && $unit !== '%';
            }
        });

        // Fallback: Jika filter unit bikin kosong (misal unitnya null semua), 
        // ambil saja semuanya (kecuali Jumlah)
        if ($chartDataRaw->isEmpty()) {
            $chartDataRaw = $allItems->filter(fn($i) => !in_array(strtolower($i->{$this->categoryColumn}), ['jumlah', 'total']));
        }

        // 3. TENTUKAN TIPE CHART
        $chartType = ($this->primaryUnit === 'Persen') ? 'pie' : 'bar';

        // Urutkan data dari besar ke kecil (Biar grafik rapi)
        $chartDataSorted = $chartDataRaw->sortByDesc('value');

        return [
            'type' => $chartType, // Dinamis: pie atau bar
            'title' => $this->dataset->dataset_name,
            'labels' => $chartDataSorted->pluck($this->categoryColumn)->toArray(),
            'datasets' => [
                [
                    'label' => $this->primaryUnit === 'Nilai' ? 'Jumlah' : $this->primaryUnit,
                    'data' => $chartDataSorted->pluck('value')->values()->toArray(),
                ]
            ],
            // Data mentah flat (opsional, untuk kompatibilitas)
            'data' => $chartDataSorted->pluck('value')->values()->toArray()
        ];
    }

    public function getInsightData(): array
    {
        // Gunakan data chart yang sudah bersih (tanpa "Jumlah")
        $chartData = $this->getChartData();
        $labels = $chartData['labels'];
        $values = $chartData['datasets'][0]['data'] ?? [];

        if (empty($values)) {
            return [['title' => 'Info', 'value' => '-', 'description' => 'Data tidak tersedia.']];
        }

        // Cari Nilai Tertinggi (Max)
        $maxVal = max($values);
        $maxIndex = array_search($maxVal, $values);
        $maxLabel = $labels[$maxIndex];

        // Cari Nilai Terendah (Min)
        $minVal = min($values);
        $minIndex = array_search($minVal, $values);
        $minLabel = $labels[$minIndex];

        $unitLabel = ($this->primaryUnit === 'Persen') ? '%' : '';

        $insights = [];

        // Insight 1: Dominan
        $insights[] = [
            'title' => 'Dominan',
            'value' => $maxLabel,
            'description' => "Kategori tertinggi adalah $maxLabel dengan nilai " . number_format($maxVal, 2) . $unitLabel,
        ];

        // Insight 2: Terendah (Jika ada lebih dari 1 kategori)
        if (count($values) > 1) {
            $insights[] = [
                'title' => 'Terendah',
                'value' => $minLabel,
                'description' => "Kategori terendah adalah $minLabel dengan nilai " . number_format($minVal, 2) . $unitLabel,
            ];
        }

        return $insights;
    }

    public function getHistoryData(): array
    {
        // Grafik Tren Total dari Tahun ke Tahun

        // 1. Coba cari baris dengan kategori "Jumlah" atau "Total" di database
        $historyData = $this->dataset->values()
            ->where(function ($q) {
                $q->where($this->categoryColumn, 'Jumlah')
                    ->orWhere($this->categoryColumn, 'Total');
            })
            ->orderBy('year')
            ->get();

        // 2. Jika tidak ada baris "Jumlah", hitung manual (Sum)
        if ($historyData->isEmpty()) {
            $historyData = $this->dataset->values()
                ->where('unit', '!=', 'Persen') // Jangan jumlahkan persen
                ->where('unit', '!=', '%')
                ->get()
                ->groupBy('year')
                ->map(function ($items) {
                    return (object)[
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
            'datasets' => [
                [
                    'label' => 'Total',
                    'data' => $historyData->pluck('value')->toArray()
                ]
            ]
        ];
    }
}
