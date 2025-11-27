<?php

namespace App\Services\DatasetHandlers;

use App\Models\BpsDataset;
use Illuminate\Support\Collection;

class PopulationByAgeGroupAndGenderHandler implements DatasetHandlerInterface
{
    protected BpsDataset $dataset;
    protected ?int $latestYear;
    protected Collection $latestValues;
    protected string $ageGroupColumn;
    protected string $genderColumn;

    // Properti baru untuk menyimpan filter dari user
    protected ?int $yearFilter;

    // Tambahkan parameter $year = null di sini
    public function __construct(BpsDataset $dataset, $year = null)
    {
        $this->dataset = $dataset;

        // 1. Simpan tahun yang diminta user (untuk filter tabel nanti)
        $this->yearFilter = $year;

        // 2. Logic lama untuk Chart (Tetap ambil tahun terbaru saja biar grafik piramida gak rusak)
        $this->latestYear = $dataset->values()->max('year');

        if ($this->latestYear) {
            // Ambil data tahun terbaru untuk Chart Piramida
            $this->latestValues = $dataset->values()->where('year', $this->latestYear)->get();
            $this->determineColumns();
        } else {
            $this->latestValues = collect();
            $this->ageGroupColumn = 'vervar_label';
            $this->genderColumn = 'turvar_label';
        }
    }

    private function determineColumns(): void
    {
        if ($this->latestValues->isEmpty()) {
            $this->ageGroupColumn = 'vervar_label';
            $this->genderColumn = 'turvar_label';
            return;
        }

        $vervarCount = $this->latestValues->pluck('vervar_label')->unique()->count();
        $turvarCount = $this->latestValues->pluck('turvar_label')->unique()->count();

        if ($vervarCount > $turvarCount) {
            $this->ageGroupColumn = 'vervar_label';
            $this->genderColumn = 'turvar_label';
        } else {
            $this->ageGroupColumn = 'turvar_label';
            $this->genderColumn = 'vervar_label';
        }
    }

    // --- FUNGSI GET TABLE DATA (Sesuai kode Anda tadi) ---
    public function getTableData(): array
    {
        // Query dasar
        $query = $this->dataset->values();

        // [PERBAIKAN] Hormati filter tahun dari user
        if ($this->yearFilter !== null) {
            $query->where('year', $this->yearFilter);
        }

        // Ambil data terurut tahun (desc)
        $allValues = $query->orderBy('year', 'desc')->get();

        // Group by Tahun dulu
        $groupedByYear = $allValues->groupBy('year');

        $rows = [];

        // Loop setiap tahun
        foreach ($groupedByYear as $year => $itemsInYear) {
            // Group by Kelompok Umur di tahun tersebut
            $groupedByAge = $itemsInYear->groupBy($this->ageGroupColumn);

            foreach ($groupedByAge as $ageGroup => $data) {
                $lakiLaki = $data->firstWhere($this->genderColumn, 'Laki-laki')->value ?? 0;
                $perempuan = $data->firstWhere($this->genderColumn, 'Perempuan')->value ?? 0;

                // Cek apakah ada data 'Jumlah' dari API, kalau tidak ada hitung sendiri
                $jumlahItem = $data->firstWhere($this->genderColumn, 'Jumlah');
                $jumlah = $jumlahItem ? $jumlahItem->value : ($lakiLaki + $perempuan);

                $rows[] = [
                    'Tahun' => $year,
                    'Kelompok Umur' => $ageGroup,
                    'Laki-laki' => $lakiLaki,
                    'Perempuan' => $perempuan,
                    'Jumlah'    => $jumlah,
                ];
            }
        }

        return [
            'headers' => ["Tahun", "Kelompok Umur", "Laki-laki", "Perempuan", "Jumlah"],
            'rows' => $rows,
        ];
    }

    public function getChartData(): array
    {
        // Tetap gunakan data tahun terbaru ($this->latestValues) untuk chart piramida
        // Agar grafiknya tidak tumpuk-tumpuk antar tahun
        $grouped = $this->latestValues->groupBy($this->ageGroupColumn);
        $labels = [];
        $lakiData = [];
        $perempuanData = [];

        foreach ($grouped as $ageGroup => $data) {
            $labels[] = $ageGroup;
            $lakiData[] = $data->firstWhere($this->genderColumn, 'Laki-laki')->value ?? 0;
            $perempuanData[] = $data->firstWhere($this->genderColumn, 'Perempuan')->value ?? 0;
        }

        return [
            'type' => 'pyramid',
            'title' => 'Piramida Penduduk ' . $this->latestYear,
            'labels' => $labels,
            'datasets' => [
                ['label' => 'Laki-laki', 'data' => $lakiData],
                ['label' => 'Perempuan', 'data' => $perempuanData],
            ]
        ];
    }

    public function getInsightData(): array
    {
        $tableData = $this->getChartData(); // Ambil dari chart data saja biar gampang
        return []; // Implementasi insight sesuai kebutuhan
    }

    public function getHistoryData(): array
    {
        $allValues = $this->dataset->values()->orderBy('year')->get();
        if ($allValues->isEmpty()) return [];

        $yearlyTotals = $allValues->groupBy('year')->map(function ($items) {
            $jumlah = $items->where($this->genderColumn, 'Jumlah')->sum('value');
            if ($jumlah == 0) {
                $jumlah = $items->whereIn($this->genderColumn, ['Laki-laki', 'Perempuan'])->sum('value');
            }
            return $jumlah;
        })->sortKeys();

        return [
            'type' => 'line',
            'title' => 'Tren Total Penduduk',
            'labels' => $yearlyTotals->keys()->toArray(),
            'datasets' => [
                ['label' => 'Total Penduduk', 'data' => $yearlyTotals->values()->toArray()],
            ],
        ];
    }
}
