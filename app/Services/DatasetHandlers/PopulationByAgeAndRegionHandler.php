<?php

namespace App\Services\DatasetHandlers;

use App\Models\BpsDataset;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PopulationByAgeAndRegionHandler implements DatasetHandlerInterface
{
    protected BpsDataset $dataset;
    protected Collection $dataForYear;
    protected ?int $selectedYear;
    protected string $mode;

    // Kolom Dinamis
    protected string $regionColumn = 'vervar_label'; // Kecamatan
    protected string $ageColumn = 'turvar_label';    // Kelompok Umur

    public function __construct(BpsDataset $dataset, $year = null, $mode = 'region')
    {
        $this->dataset = $dataset;
        $this->mode = $mode ?: 'region';
        $this->selectedYear = $year ?: $dataset->values()->max('year');

        if ($this->selectedYear) {
            $this->dataForYear = $dataset->values()->where('year', $this->selectedYear)->get();
            $this->detectColumns();
        } else {
            $this->dataForYear = collect();
        }
    }

    private function detectColumns(): void
    {
        if ($this->dataForYear->isEmpty()) return;

        $sample = $this->dataForYear->first();
        $vervar = strtolower($sample->vervar_label ?? '');

        // Cek keywords untuk Umur di vervar. Jika ada, berarti vervar=Umur, turvar=Kecamatan.
        // Jika tidak, default vervar=Kecamatan.
        if (Str::contains($vervar, ['0-4', '5-9', 'umur', 'usia', 'tahunan'])) {
            $this->ageColumn = 'vervar_label';
            $this->regionColumn = 'turvar_label';
        } else {
            $this->regionColumn = 'vervar_label';
            $this->ageColumn = 'turvar_label';
        }
    }

    // --- FITUR UTAMA: TABEL PIVOT (MATRIKS) ---
    public function getTableData(): array
    {
        if ($this->dataForYear->isEmpty()) return ['headers' => [], 'rows' => []];

        // 1. Ambil Header Kolom (Kelompok Umur)
        $ageGroups = $this->dataForYear->pluck($this->ageColumn)
            ->unique()
            ->filter(fn($val) => !empty($val) && !in_array(strtolower($val), ['total', 'jumlah']))
            ->values()
            ->toArray();

        // Sort Umur secara Natural (0-4, 5-9, 10-14...)
        natsort($ageGroups);
        $ageGroups = array_values($ageGroups);

        // 2. Susun Header Tabel [Tahun, Kecamatan, ...Umur..., Total]
        $headers = array_merge(['Tahun', 'Kecamatan'], $ageGroups, ['Total']);

        // 3. Grouping Data per Kecamatan (Baris)
        $rows = [];
        $groupedByRegion = $this->dataForYear->groupBy($this->regionColumn);

        foreach ($groupedByRegion as $region => $items) {
            // Skip baris Agregat Kabupaten agar tabel bersih
            if (in_array(strtolower($region), ['jumlah', 'total', 'kabupaten kebumen'])) continue;

            $row = [
                'Tahun' => $this->selectedYear,
                'Kecamatan' => $region,
            ];

            $totalRow = 0;

            // Isi setiap sel umur
            foreach ($ageGroups as $age) {
                $val = $items->firstWhere($this->ageColumn, $age);
                $num = $val ? $val->value : 0;

                $row[$age] = number_format($num, 0); // Format angka ribuan
                $totalRow += $num;
            }

            // Kolom Total di ujung kanan
            $row['Total'] = number_format($totalRow, 0);

            $rows[] = $row;
        }

        // Sort baris berdasarkan Abjad Kecamatan
        usort($rows, fn($a, $b) => strcmp($a['Kecamatan'], $b['Kecamatan']));

        return [
            'headers' => $headers,
            'rows' => $rows
        ];
    }

    public function getChartData(): array
    {
        if ($this->dataForYear->isEmpty()) return [];

        // MODE 1: UMUR (Chart Default / Piramida / Bar)
        if ($this->mode === 'age') {
            // Group by Umur, Sum value dari semua kecamatan
            $data = $this->dataForYear
                ->reject(fn($i) => in_array(strtolower($i->{$this->regionColumn}), ['jumlah', 'total', 'kabupaten kebumen']))
                ->groupBy($this->ageColumn)
                ->map(fn($g) => $g->sum('value'));

            // Sort keys natural (biar umur urut)
            $keys = $data->keys()->toArray();
            natsort($keys);

            $sortedLabels = [];
            $sortedValues = [];
            foreach ($keys as $k) {
                $sortedLabels[] = $k;
                $sortedValues[] = $data[$k];
            }

            return [
                'type' => 'bar',
                'title' => 'Distribusi Penduduk per Kelompok Umur',
                'labels' => $sortedLabels,
                'datasets' => [[
                    'label' => 'Jumlah Jiwa',
                    'data' => $sortedValues,
                    'backgroundColor' => '#36A2EB'
                ]]
            ];
        }

        // MODE 2: KECAMATAN (Horizontal Bar - Top 10)
        else {
            $data = $this->dataForYear
                ->reject(fn($i) => in_array(strtolower($i->{$this->regionColumn}), ['jumlah', 'total', 'kabupaten kebumen']))
                ->groupBy($this->regionColumn)
                ->map(fn($g) => $g->sum('value'))
                ->sortDesc()
                ->take(10); // Ambil Top 10 Terpadat

            return [
                'type' => 'horizontalBar',
                'title' => '10 Kecamatan Terpadat',
                'labels' => $data->keys()->values()->toArray(),
                'datasets' => [[
                    'label' => 'Total Penduduk',
                    'data' => $data->values()->toArray(),
                    'backgroundColor' => '#FF6384'
                ]]
            ];
        }
    }

    public function getInsightData(): array
    {
        if ($this->dataForYear->isEmpty()) return [['title' => 'Info', 'value' => '-', 'description' => 'Data Kosong']];

        // 1. Kecamatan Terpadat
        $byRegion = $this->dataForYear
            ->reject(fn($i) => in_array(strtolower($i->{$this->regionColumn}), ['jumlah', 'total', 'kabupaten kebumen']))
            ->groupBy($this->regionColumn)
            ->map(fn($g) => $g->sum('value'))
            ->sortDesc();

        $topRegion = $byRegion->keys()->first();
        $topRegionVal = $byRegion->first();

        // 2. Kelompok Umur Dominan
        $byAge = $this->dataForYear
            ->reject(fn($i) => in_array(strtolower($i->{$this->regionColumn}), ['jumlah', 'total', 'kabupaten kebumen']))
            ->groupBy($this->ageColumn)
            ->map(fn($g) => $g->sum('value'))
            ->sortDesc();

        $topAge = $byAge->keys()->first();
        $topAgeVal = $byAge->first();

        return [
            [
                'title' => 'Kecamatan Terpadat',
                'value' => $topRegion,
                'description' => "Memiliki populasi terbanyak: " . number_format($topRegionVal) . " Jiwa."
            ],
            [
                'title' => 'Usia Dominan',
                'value' => $topAge,
                'description' => "Kelompok umur terbanyak adalah $topAge (" . number_format($topAgeVal) . " Jiwa)."
            ]
        ];
    }

    public function getHistoryData(): array
    {
        // Simple History
        $history = $this->dataset->values()
            ->get()
            ->groupBy('year')
            ->map(fn($items) => $items->whereIn($this->regionColumn, ['Jumlah', 'Total', 'Kabupaten Kebumen'])->first()->value ?? $items->sum('value'))
            ->sortKeys();

        return [
            'type' => 'line',
            'title' => 'Tren Total',
            'labels' => $history->keys()->toArray(),
            'datasets' => [['label' => 'Total', 'data' => $history->values()->toArray()]]
        ];
    }
}
