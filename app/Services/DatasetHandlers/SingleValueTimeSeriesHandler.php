<?php

namespace App\Services\DatasetHandlers;

use App\Models\BpsDataset;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SingleValueTimeSeriesHandler implements DatasetHandlerInterface
{
    protected BpsDataset $dataset;
    protected string $unit;
    protected ?int $year;
    protected Collection $dataForYear;

    // Mode: 'single' (satu nilai per tahun) atau 'multi' (banyak item per tahun)
    protected string $mode = 'single';
    protected string $labelColumn = 'vervar_label';

    public function __construct(BpsDataset $dataset, $year = null)
    {
        $this->dataset = $dataset;
        // Ambil tahun terbaru jika user tidak memilih
        $this->year = $year ?: $dataset->values()->max('year');

        $sample = $dataset->values()->first();
        $this->unit = $sample->unit ?? '';

        // --- DETEKSI MODE ---
        if ($this->year) {
            // Ambil data untuk tahun yang dipilih
            $this->dataForYear = $dataset->values()->where('year', $this->year)->get();

            // Cek ada berapa baris data di tahun ini (setelah membuang baris Total/Tahunan)
            $realDataCount = $this->dataForYear->reject(function ($item) {
                $label = strtolower($item->vervar_label . $item->turvar_label);
                return Str::contains($label, ['jumlah', 'total', 'tahunan', 'kabupaten kebumen']);
            })->count();

            // Jika baris data asli lebih dari 1, berarti ini data Rincian (Bulan/Komponen/Jenis)
            if ($realDataCount > 1) {
                $this->mode = 'multi';
                $this->detectLabelColumn();
            } else {
                $this->mode = 'single';
            }
        } else {
            $this->dataForYear = collect();
        }
    }

    private function detectLabelColumn(): void
    {
        // Cari kolom mana yang punya variasi label (vervar vs turvar)
        $vervarCount = $this->dataForYear->pluck('vervar_label')->unique()->count();
        $turvarCount = $this->dataForYear->pluck('turvar_label')->unique()->count();

        $this->labelColumn = ($turvarCount > $vervarCount) ? 'turvar_label' : 'vervar_label';
    }

    public function getTableData(): array
    {
        // --- SKENARIO 1: DATA RINCIAN (IPM Components / Bulan) ---
        if ($this->mode === 'multi') {
            // Tampilkan rincian untuk tahun terpilih
            $rows = $this->dataForYear->map(function ($item) {
                return [
                    'Tahun' => $item->year,
                    'Uraian' => $item->{$this->labelColumn}, // Label: "Januari" atau "Angka Harapan Hidup"
                    'Nilai' => number_format($item->value, 2) . ' ' . $item->unit
                ];
            });

            // Jika Bulan, urutkan logis (Jan-Des), jika tidak biarkan default
            $isMonthly = $this->isMonthlyData($rows);
            if ($isMonthly) {
                $rows = $this->sortMonthlyData($rows);
            }

            return [
                'headers' => ['Tahun', 'Uraian', 'Nilai'],
                'rows' => $rows
            ];
        }

        // --- SKENARIO 2: DATA TUNGGAL (History Trend) ---
        // Tampilkan list tahun ke belakang
        $allData = $this->dataset->values()->orderBy('year', 'desc')->get();

        $grouped = $allData->groupBy('year')->map(function ($items) {
            // Prioritaskan ambil baris "Total" atau "Tahunan" jika ada
            $totalRow = $items->first(fn($i) => Str::contains(strtolower($i->vervar_label . $i->turvar_label), ['total', 'jumlah', 'tahunan']));
            return $totalRow ? $totalRow->value : $items->sum('value');
        });

        $rows = [];
        foreach ($grouped as $year => $value) {
            $rows[] = [
                'Tahun' => $year,
                'Nilai' => number_format($value, 2) . ' ' . $this->unit
            ];
        }

        return [
            'headers' => ['Tahun', 'Nilai'],
            'rows' => $rows
        ];
    }

    public function getChartData(): array
    {
        // --- CHART UNTUK MULTI ITEM (Snapshot Tahun Ini) ---
        if ($this->mode === 'multi') {
            // Filter data sampah (Total/Tahunan) agar grafik tidak jomplang
            $chartItems = $this->dataForYear->reject(function ($item) {
                $label = strtolower($item->{$this->labelColumn});
                return Str::contains($label, ['jumlah', 'total', 'tahunan', 'kabupaten kebumen']);
            });

            $labels = $chartItems->pluck($this->labelColumn)->toArray();
            $values = $chartItems->pluck('value')->toArray();

            // Cek apakah data Bulanan?
            $isMonthly = $this->isMonthlyCheck($labels);

            // Jika Bulanan -> LINE CHART (Urutkan Jan-Des)
            if ($isMonthly) {
                // Sorting Bulan logic
                $sorted = $this->sortChartMonthly($labels, $values);
                return [
                    'type' => 'line',
                    'title' => 'Tren Bulanan (' . $this->year . ')',
                    'labels' => $sorted['labels'],
                    'datasets' => [[
                        'label' => $this->unit ?: 'Nilai',
                        'data' => $sorted['values'],
                        'borderColor' => '#FF9800', // Oranye
                        'fill' => false
                    ]]
                ];
            }

            // Jika Komponen (IPM/Wisatawan) -> HORIZONTAL BAR
            return [
                'type' => 'horizontalBar',
                'title' => 'Rincian ' . $this->dataset->dataset_name,
                'labels' => array_values($labels),
                'datasets' => [[
                    'label' => $this->unit ?: 'Nilai',
                    'data' => array_values($values),
                    'backgroundColor' => '#36A2EB'
                ]]
            ];
        }

        // --- CHART UNTUK SINGLE ITEM (History Trend) ---
        // Sama seperti kode sebelumnya
        $allData = $this->dataset->values()->orderBy('year', 'asc')->get();
        $history = $allData->groupBy('year')->map(function ($items) {
            $totalRow = $items->first(fn($i) => Str::contains(strtolower($i->vervar_label . $i->turvar_label), ['total', 'jumlah', 'tahunan']));
            return $totalRow ? $totalRow->value : $items->sum('value');
        });

        return [
            'type' => 'line',
            'title' => 'Tren Tahunan',
            'labels' => $history->keys()->toArray(),
            'datasets' => [[
                'label' => $this->unit ?: 'Nilai',
                'data' => $history->values()->toArray(),
                'borderColor' => '#4CAF50',
                'backgroundColor' => 'rgba(76, 175, 80, 0.1)',
                'fill' => true
            ]]
        ];
    }

    public function getInsightData(): array
    {
        if ($this->mode === 'multi') {
            $maxItem = $this->dataForYear->sortByDesc('value')->first();
            $minItem = $this->dataForYear->sortBy('value')->first();

            return [
                [
                    'title' => 'Tertinggi',
                    'value' => $maxItem->{$this->labelColumn} ?? '-',
                    'description' => "Nilai tertinggi pada komponen ini (" . number_format($maxItem->value ?? 0) . ")."
                ]
            ];
        }

        // Insight Trend (Logic lama)
        $chartData = $this->getChartData();
        $values = $chartData['datasets'][0]['data'];
        $years = $chartData['labels'];
        $lastVal = end($values);
        $prevVal = prev($values);

        $growth = ($prevVal > 0) ? (($lastVal - $prevVal) / $prevVal) * 100 : 0;
        $trend = $growth >= 0 ? "Naik " . number_format($growth, 1) . "%" : "Turun " . number_format(abs($growth), 1) . "%";

        return [[
            'title' => 'Tren Terbaru',
            'value' => $trend,
            'description' => "Perbandingan tahun {$this->year} dengan tahun sebelumnya."
        ]];
    }

    public function getHistoryData(): array
    {
        return [];
    }

    // --- HELPER FUNCTION UNTUK BULAN ---

    private function isMonthlyCheck(array $labels): bool
    {
        foreach ($labels as $lbl) {
            if (Str::contains(strtolower($lbl), ['januari', 'februari', 'maret', 'april'])) return true;
        }
        return false;
    }

    private function isMonthlyData($rows): bool
    {
        // Cek baris pertama kolom Uraian
        $first = $rows->first();
        return $first && Str::contains(strtolower($first['Uraian']), ['januari', 'februari', 'maret']);
    }

    private function sortMonthlyData($rows)
    {
        $months = array_flip(['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember', 'tahunan']);
        return $rows->sortBy(function ($row) use ($months) {
            return $months[strtolower($row['Uraian'])] ?? 99;
        })->values();
    }

    private function sortChartMonthly($labels, $values)
    {
        $monthsOrder = array_flip(['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember']);

        $combined = [];
        foreach ($labels as $i => $lbl) {
            $combined[] = ['label' => $lbl, 'value' => $values[$i]];
        }

        usort($combined, function ($a, $b) use ($monthsOrder) {
            $idxA = $monthsOrder[strtolower($a['label'])] ?? 99;
            $idxB = $monthsOrder[strtolower($b['label'])] ?? 99;
            return $idxA <=> $idxB;
        });

        return [
            'labels' => array_column($combined, 'label'),
            'values' => array_column($combined, 'value')
        ];
    }
}
