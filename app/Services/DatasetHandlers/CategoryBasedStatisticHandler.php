<?php

namespace App\Services\DatasetHandlers;

use App\Models\BpsDataset;
use Illuminate\Support\Collection;

class CategoryBasedStatisticHandler implements DatasetHandlerInterface
{
    protected BpsDataset $dataset;
    protected ?int $year;
    protected Collection $latestValues;
    protected string $categoryColumn;

    public function __construct(BpsDataset $dataset, $year = null)
    {
        $this->dataset = $dataset;
        $this->year = $year ?: $dataset->values()->max('year');

        if ($this->year) {
            $this->latestValues = $this->dataset->values()->where('year', $this->year)->get();
            $this->categoryColumn = $this->detectCategoryColumn();
        } else {
            $this->latestValues = collect();
            $this->categoryColumn = 'turvar_label';
        }
    }

    private function detectCategoryColumn(): string
    {
        if ($this->latestValues->isEmpty()) {
            return 'turvar_label';
        }

        $vervarCount = $this->latestValues->pluck('vervar_label')->unique()->count();
        $turvarCount = $this->latestValues->pluck('turvar_label')->unique()->count();

        if ($turvarCount > $vervarCount) {
            return 'turvar_label';
        }

        if ($vervarCount > $turvarCount && $this->latestValues->first()->vervar_label !== 'Kabupaten Kebumen') {
            return 'vervar_label';
        }

        return 'turvar_label';
    }

    public function getTableData(): array
    {
        // Query Dasar
        $query = $this->dataset->values();

        // [PERBAIKAN] Jika ada tahun spesifik (dari dropdown), filter datanya!
        if ($this->year) {
            $query->where('year', $this->year);
        }

        // Ambil data dan urutkan
        $allData = $query->orderBy('year', 'desc')
            ->orderBy($this->categoryColumn, 'asc')
            ->get();

        // ... (Sisa kode ke bawah SAMA PERSIS dengan sebelumnya) ...
        // ... (Logika headers, grouping rows, dll tidak perlu diubah) ...

        if ($allData->isEmpty()) {
            return ['headers' => [], 'rows' => []];
        }

        $units = $allData->pluck('unit')->unique()->filter()->values()->toArray();
        if (empty($units)) $units = ['Nilai'];

        // Buat Headers (Hapus 'Tahun' kalau mau tabel bersih, atau biarkan)
        // Kalau difilter per tahun, kolom 'Tahun' jadi tidak terlalu berguna, bisa dihapus di sini atau di Android
        $headers = array_merge(['Tahun', 'Kategori'], $units);

        $rows = [];
        $groupedByYear = $allData->groupBy('year');

        foreach ($groupedByYear as $year => $itemsInYear) {
            $groupedByCategory = $itemsInYear->groupBy($this->categoryColumn);
            foreach ($groupedByCategory as $category => $items) {
                $row = ['Tahun' => $year, 'Kategori' => $category];
                foreach ($units as $unit) {
                    $item = ($unit === 'Nilai') ? $items->first() : $items->firstWhere('unit', $unit);
                    $row[$unit] = $item ? $item->value : null;
                }
                $rows[] = $row;
            }
        }

        return ['headers' => $headers, 'rows' => $rows];
    }

    public function getChartData(): array
    {
        if ($this->latestValues->isEmpty()) {
            return ['type' => 'bar', 'title' => 'Data Tidak Tersedia', 'labels' => [], 'data' => []];
        }

        $allUnits = $this->latestValues->pluck('unit')->unique();
        $unitForChart = 'Nilai';
        if ($allUnits->contains('Persen')) {
            $unitForChart = 'Persen';
        } elseif ($allUnits->count() > 0 && $allUnits->first() !== null) {
            $unitForChart = $allUnits->first();
        }

        $chartData = ($unitForChart === 'Nilai')
            ? $this->latestValues
            : $this->latestValues->where('unit', $unitForChart);

        return [
            'type' => 'bar',
            'title' => 'Distribusi Berdasarkan Kategori (' . $unitForChart . ')',
            'labels' => $chartData->pluck($this->categoryColumn)->toArray(),
            'data' => $chartData->pluck('value')->toArray(),
        ];
    }

    public function getInsightData(): array
    {
        if ($this->latestValues->isEmpty()) {
            return [['title' => 'Info', 'value' => 'Data Kosong', 'description' => 'Data tidak tersedia.']];
        }

        // 1. PISAHKAN DATA: Detail (Kecamatan) vs Agregat (Kabupaten/Total)
        // Menggunakan partition() collection Laravel:
        // $aggregates = data baris total (Kabupaten)
        // $details = data baris rincian (Kecamatan)
        [$aggregates, $details] = $this->latestValues->partition(function ($item) {
            $name = strtolower($item->{$this->categoryColumn});
            return str_contains($name, 'kabupaten') ||
                str_contains($name, 'jumlah') ||
                str_contains($name, 'total');
        });

        // Safety: Jika $details kosong (misal data cuma 1 baris total), pakai semua data
        if ($details->isEmpty()) {
            $details = $this->latestValues;
        }

        // 2. LOGIC PERHITUNGAN
        // Cari Max (Terpadat) dan Min (Tersepi) hanya dari $details
        $max = $details->sortByDesc('value')->first();
        $min = $details->sortBy('value')->first();

        // Ambil nilai Total untuk penyebut persentase
        // Jika ada baris "Kabupaten", pakai itu. Jika tidak, jumlahkan semua details.
        $totalValue = $aggregates->isNotEmpty()
            ? $aggregates->first()->value
            : $details->sum('value');

        $unit = $max->unit ?? '';
        $insights = [];

        // --- Insight 1: Tertinggi (Terpadat) ---
        if ($max) {
            $insights[] = [
                'title' => 'Nilai Tertinggi', // Bisa diganti "Kecamatan Terpadat" jika konteksnya pasti wilayah
                'value' => $max->{$this->categoryColumn},
                'description' => "Wilayah dengan angka tertinggi adalah {$max->{$this->categoryColumn}} (" . number_format($max->value, 0, ',', '.') . " $unit).",
            ];
        }

        // --- Insight 2: Terendah (Tersepi) ---
        if ($min) {
            $insights[] = [
                'title' => 'Nilai Terendah', // Bisa diganti "Kecamatan Tersepi"
                'value' => $min->{$this->categoryColumn},
                'description' => "Wilayah dengan angka terendah adalah {$min->{$this->categoryColumn}} (" . number_format($min->value, 0, ',', '.') . " $unit).",
            ];
        }

        // --- Insight 3: Proporsi (Persentase) ---
        if ($max && $totalValue > 0) {
            $percentage = ($max->value / $totalValue) * 100;

            $insights[] = [
                'title' => 'Dominasi Wilayah', // Atau "Proporsi " . $max->{$this->categoryColumn}
                'value' => number_format($percentage, 1) . '%', // Contoh: 9.6%
                'description' => "Sekitar " . number_format($percentage, 1) . "% dari total data terpusat di " . $max->{$this->categoryColumn} . ".",
            ];
        }

        return $insights;
    }

    // ======================================================================
    // [FUNGSI BARU] Menambahkan Kemampuan untuk Melihat Sejarah (History)
    // ======================================================================
    public function getHistoryData(): array
    {
        // 1. Ambil semua data historis dari database untuk dataset ini
        $allValues = $this->dataset->values()->orderBy('year')->get();

        if ($allValues->isEmpty()) {
            return []; // Kembalikan kosong jika memang tidak ada data
        }

        // 2. Kelompokkan data berdasarkan tahun
        $groupedByYear = $allValues->groupBy('year');

        // 3. Hitung total nilai untuk setiap tahun
        $yearlyTotals = $groupedByYear->map(function ($itemsInYear) {
            // Kita akan menjumlahkan nilai dari unit yang paling umum (bukan persen)
            $unit = $itemsInYear->first()->unit ?? 'Nilai';
            return $itemsInYear->where('unit', $unit)->sum('value');
        });

        // 4. Siapkan data untuk grafik garis
        return [
            'type' => 'line',
            'title' => 'Tren Total dari Tahun ke Tahun',
            'labels' => $yearlyTotals->keys()->toArray(),
            'datasets' => [
                [
                    'label' => 'Total Nilai',
                    'data' => $yearlyTotals->values()->toArray(),
                ],
            ],
        ];
    }
}
