<?php
// app/Services/DatasetHandlers/PopulationByAgeGroupAndGenderHandler.php

namespace App\Services\DatasetHandlers;

use App\Models\BpsDataset;
use Illuminate\Support\Facades\DB;

class PopulationByAgeGroupAndGenderHandler implements DatasetHandlerInterface
{
    protected $dataset;
    protected $latestYear;

    public function __construct(BpsDataset $dataset)
    {
        $this->dataset = $dataset;
        $this->latestYear = $dataset->values()->max('year');
    }

    public function getTableData(): array
    {
        $values = $this->dataset->values()->where('year', $this->latestYear)->get();
        // Kelompokkan data berdasarkan kelompok umur (kita asumsikan nama kolomnya 'var_label')
        $grouped = $values->groupBy('var_label');

        $rows = [];
        foreach ($grouped as $ageGroup => $data) {
            $lakiLaki = $data->firstWhere('turvar_label', 'Laki-laki')->value ?? 0;
            $perempuan = $data->firstWhere('turvar_label', 'Perempuan')->value ?? 0;
            $rows[] = [
                'Kelompok Umur' => $ageGroup,
                'Laki-laki' => $lakiLaki,
                'Perempuan' => $perempuan,
                'Jumlah'    => $lakiLaki + $perempuan,
            ];
        }

        return [
            'headers' => ["Kelompok Umur", "Laki-laki", "Perempuan", "Jumlah"],
            'rows' => $rows,
        ];
    }

    public function getChartData(): array
    {
        // Grafik: Piramida Penduduk atau Bar Chart per Kelompok Umur
        $tableData = $this->getTableData()['rows'];

        return [
            'type' => 'bar',
            'title' => 'Jumlah Penduduk per Kelompok Umur',
            'labels' => collect($tableData)->pluck('Kelompok Umur')->toArray(),
            'data' => collect($tableData)->pluck('Jumlah')->toArray(),
        ];
    }

    public function getInsightData(): array
    {
        // Insight: Kelompok Umur dengan populasi terbanyak
        $tableData = $this->getTableData()['rows'];
        $largestGroup = collect($tableData)->sortByDesc('Jumlah')->first();

        return [
            [
                'title' => 'Kelompok Umur Terbesar',
                'value' => $largestGroup['Kelompok Umur'] ?? 'N/A',
                'description' => 'Dengan total ' . number_format($largestGroup['Jumlah'] ?? 0) . ' jiwa',
            ]
        ];
    }
}
