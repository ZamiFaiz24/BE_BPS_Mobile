<?php
// filepath: d:\Aplikasi\xampp\htdocs\Laravel\be_bps_mobile\app\Services\DatasetHandlers\CategoryBasedStatisticHandler.php

namespace App\Services\DatasetHandlers;

use App\Models\BpsDataset;

class CategoryBasedStatisticHandler implements DatasetHandlerInterface
{
    protected $dataset;
    protected $year;
    protected $unit;

    public function __construct(BpsDataset $dataset, $year = null)
    {
        $this->dataset = $dataset;
        $this->year = $year ?: $dataset->values()->max('year');
        $firstValue = $this->dataset->values()->where('year', $this->year)->first();
        $this->unit = $firstValue ? $firstValue->unit : '';
    }

    /**
     * Menyiapkan data untuk tabel
     */
    public function getTableData(): array
    {
        $values = $this->dataset->values()
            ->where('year', $this->year)
            ->get();

        // Ambil semua unit unik
        $units = $values->pluck('unit')->unique()->filter()->values()->toArray();

        // Header: Kategori + semua unit
        $headers = array_merge(['Kategori'], $units);

        // Bangun rows
        $rows = [];
        // Kelompokkan berdasarkan kategori (turvar_label)
        $grouped = $values->groupBy('turvar_label');
        foreach ($grouped as $kategori => $items) {
            $row = ['Kategori' => $kategori];
            foreach ($units as $unit) {
                // Cari value untuk unit ini
                $item = $items->firstWhere('unit', $unit);
                $row[$unit] = $item ? $item->value : null;
            }
            $rows[] = $row;
        }

        return [
            'headers' => $headers,
            'rows' => $rows,
        ];
    }

    /**
     * Menyiapkan data untuk grafik
     */
    public function getChartData(): array
    {
        $chartData = $this->dataset->values()
            ->where('year', $this->year)
            ->get();

        $unit = $chartData->first() ? $chartData->first()->unit : '';

        return [
            'type' => 'bar',
            'title' => ["Distribusi Berdasarkan Kategori ", $this->unit],
            'labels' => $chartData->pluck('turvar_label')->toArray(),
            'data' => $chartData->pluck('value')->toArray(),
        ];
    }

    /**
     * Menyiapkan data untuk insight
     */
    public function getInsightData(): array
    {
        $values = $this->dataset->values()
            ->where('year', $this->year)
            ->get();

        $unit = $values->first() ? $values->first()->unit : '';
        $max = $values->sortByDesc('value')->first();

        return [
            [
                'title' => 'Kategori Tertinggi',
                'value' => $max ? $max->turvar_label : '-',
                'description' => $max
                    ? 'Kategori dengan nilai tertinggi adalah ' . $max->turvar_label . ' (' . $max->value . ' ' . $unit . ') pada tahun ' . $this->year
                    : 'Data tidak tersedia.',
            ]
        ];
    }
}
