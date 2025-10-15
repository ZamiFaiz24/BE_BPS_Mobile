<?php

namespace App\Services\DatasetHandlers;

use App\Models\BpsDataset;
use Illuminate\Support\Collection;

class SingleValueTimeSeriesHandler implements DatasetHandlerInterface
{
    protected BpsDataset $dataset;
    protected Collection $allValues;
    protected ?int $latestYear;
    protected string $unit = '';

    public function __construct(BpsDataset $dataset)
    {
        $this->dataset = $dataset;
        // Ambil semua data sekaligus dan urutkan
        $this->allValues = $dataset->values()->orderBy('year', 'desc')->get();
        $this->latestYear = $this->allValues->first()->year ?? null;
        $this->unit = $this->allValues->first()->unit ?? '';
    }

    /**
     * Tabel untuk data ini akan menampilkan nilai per tahun.
     */
    public function getTableData(): array
    {
        return [
            'headers' => ['Tahun', 'Nilai'],
            'rows' => $this->allValues->map(function ($item) {
                return [
                    'Tahun' => $item->year,
                    'Nilai' => $item->value,
                ];
            })->all(),
        ];
    }

    /**
     * Chart terbaik untuk data ini adalah grafik garis (line chart).
     */
    public function getChartData(): array
    {
        // Data harus diurutkan dari tahun terlama ke terbaru untuk chart
        $sortedForChart = $this->allValues->sortBy('year');

        return [
            'type' => 'line', // Tipe chart diubah menjadi 'line'
            'title' => $this->dataset->dataset_name,
            'labels' => $sortedForChart->pluck('year')->toArray(),
            // Untuk line chart, 'data' harus dalam satu array di dalam 'datasets'
            'datasets' => [
                [
                    'label' => $this->unit,
                    'data' => $sortedForChart->pluck('value')->toArray(),
                ]
            ],
        ];
    }

    /**
     * Insight untuk data ini fokus pada nilai terbaru dan perubahannya.
     */
    public function getInsightData(): array
    {
        if ($this->allValues->isEmpty()) {
            return [['title' => 'Info', 'value' => '-', 'description' => 'Data tidak tersedia.']];
        }

        $latest = $this->allValues->first();
        $insights = [];

        $insights[] = [
            'title' => 'Nilai Terbaru',
            'value' => "{$latest->value} {$this->unit}",
            'description' => "Data untuk tahun {$this->latestYear}",
        ];

        // Cek perubahan dari tahun sebelumnya jika ada
        if ($this->allValues->count() > 1) {
            $previous = $this->allValues->get(1); // Ambil data kedua (tahun sebelumnya)
            $change = $latest->value - $previous->value;

            $insights[] = [
                'title' => 'Perubahan Tahunan',
                'value' => sprintf("%+.2f", $change), // Tampilkan tanda + atau -
                'description' => "Perubahan dari tahun {$previous->year} ke {$latest->year}",
            ];
        }

        return $insights;
    }
}
