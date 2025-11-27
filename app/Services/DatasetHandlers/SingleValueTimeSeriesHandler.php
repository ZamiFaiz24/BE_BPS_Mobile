<?php

namespace App\Services\DatasetHandlers;

use App\Models\BpsDataset;
use Illuminate\Support\Collection;

class SingleValueTimeSeriesHandler implements DatasetHandlerInterface
{
    protected BpsDataset $dataset;
    protected Collection $allValues;
    protected string $unit = '';
    protected ?string $monthColumn = null; // Properti baru untuk simpan nama kolom bulan

    // Daftar nama bulan untuk deteksi otomatis
    private array $monthNames = [
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember',
        'Triwulan I',
        'Triwulan II',
        'Triwulan III',
        'Triwulan IV' // Support Triwulan juga
    ];

    public function __construct(BpsDataset $dataset, $year = null)
    {
        $this->dataset = $dataset;

        // 1. Ambil semua data (urutkan tahun desc, lalu id desc agar bulan urut Des->Jan)
        $this->allValues = $dataset->values()
            ->orderBy('year', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $firstRow = $this->allValues->first();
        $this->unit = $firstRow->unit ?? '';

        // 2. DETEKSI APAKAH INI DATA BULANAN?
        if ($firstRow) {
            $this->monthColumn = $this->detectPeriodColumn($firstRow);
        }
    }

    /**
     * Cek apakah ada kolom yang isinya nama bulan
     */
    private function detectPeriodColumn($row): ?string
    {
        // Cek turvar_label
        if (in_array($row->turvar_label, $this->monthNames)) {
            return 'turvar_label';
        }
        // Cek vervar_label
        if (in_array($row->vervar_label, $this->monthNames)) {
            return 'vervar_label';
        }
        // Cek var_label
        if (in_array($row->var_label, $this->monthNames)) {
            return 'var_label';
        }
        return null; // Bukan data bulanan (Tahunan biasa)
    }

    public function getTableData(): array
    {
        // Jika data bulanan, tambahkan kolom "Periode"
        $headers = $this->monthColumn ? ['Tahun', 'Periode', 'Nilai'] : ['Tahun', 'Nilai'];

        $rows = $this->allValues->map(function ($item) {
            $row = ['Tahun' => $item->year];

            // Jika ada bulan, masukkan ke baris
            if ($this->monthColumn) {
                $row['Periode'] = $item->{$this->monthColumn};
            }

            $row['Nilai'] = $item->value;
            return $row;
        })->all();

        return [
            'headers' => $headers,
            'rows' => $rows,
        ];
    }

    public function getChartData(): array
    {
        // Urutkan dari terlama ke terbaru untuk grafik
        // Sort by Year ASC, lalu ID ASC (agar Jan->Des)
        $sortedForChart = $this->allValues->sortBy(['year', 'id']);

        // Buat label grafik
        // Jika Tahunan: "2020", "2021"
        // Jika Bulanan: "Jan 2020", "Feb 2020"
        $labels = $sortedForChart->map(function ($item) {
            if ($this->monthColumn) {
                // Ambil 3 huruf pertama bulan (Januari -> Jan) biar grafik gak penuh
                $shortMonth = substr($item->{$this->monthColumn}, 0, 3);
                return "$shortMonth " . $item->year;
            }
            return $item->year;
        })->toArray();

        return [
            'type' => 'line',
            'title' => $this->dataset->dataset_name,
            'labels' => array_values($labels), // Re-index array
            'datasets' => [
                [
                    'label' => $this->unit,
                    'data' => $sortedForChart->pluck('value')->values()->toArray(),
                ]
            ],
        ];
    }

    public function getInsightData(): array
    {
        if ($this->allValues->isEmpty()) {
            return [['title' => 'Info', 'value' => '-', 'description' => 'Data tidak tersedia.']];
        }

        // Data terbaru (Paling atas)
        $latest = $this->allValues->first();

        // Label waktu (Tahun 2024 atau Januari 2024)
        $timeLabel = $latest->year;
        if ($this->monthColumn) {
            $timeLabel = $latest->{$this->monthColumn} . " " . $latest->year;
        }

        $insights = [];
        $insights[] = [
            'title' => 'Nilai Terbaru',
            'value' => number_format($latest->value) . " " . $this->unit,
            'description' => "Data periode $timeLabel",
        ];

        // Bandingkan dengan periode sebelumnya (Bulan lalu / Tahun lalu)
        if ($this->allValues->count() > 1) {
            $previous = $this->allValues->get(1); // Data kedua

            $change = $latest->value - $previous->value;
            $sign = $change > 0 ? 'Naik' : ($change < 0 ? 'Turun' : 'Tetap');
            $icon = $change > 0 ? '+' : '';

            $prevLabel = $previous->year;
            if ($this->monthColumn) {
                $prevLabel = substr($previous->{$this->monthColumn}, 0, 3);
            }

            $insights[] = [
                'title' => 'Perubahan',
                'value' => sprintf("%s %s", $icon, number_format($change)),
                'description' => "$sign dibanding $prevLabel",
            ];
        }

        return $insights;
    }

    public function getHistoryData(): array
    {
        return $this->getChartData();
    }
}
