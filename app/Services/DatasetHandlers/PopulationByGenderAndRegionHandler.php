<?php
// app/Services/DatasetHandlers/PopulationByGenderAndRegionHandler.php

namespace App\Services\DatasetHandlers;

use App\Models\BpsDataset;
use Illuminate\Support\Facades\DB;

class PopulationByGenderAndRegionHandler implements DatasetHandlerInterface
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
        // Query ini akan mengambil data mentah, lalu kita olah
        $values = $this->dataset->values()->where('year', $this->latestYear)->get();
        // Kelompokkan data berdasarkan kecamatan ('region_name' atau 'var_label')
        $grouped = $values->groupBy('var_label');

        $rows = [];
        foreach ($grouped as $region => $data) {
            // Di dalam setiap kecamatan, cari nilai Laki-laki dan Perempuan
            $lakiLaki = $data->firstWhere('turvar_label', 'Laki-laki')->value ?? 0;
            $perempuan = $data->firstWhere('turvar_label', 'Perempuan')->value ?? 0;
            $rows[] = [
                'Kecamatan' => $region,
                'Laki-laki' => $lakiLaki,
                'Perempuan' => $perempuan,
                'Jumlah'    => $lakiLaki + $perempuan,
            ];
        }

        return [
            'headers' => ["Kecamatan", "Laki-laki", "Perempuan", "Jumlah"],
            'rows' => collect($rows)->sortBy('Kecamatan')->values()->all(),
        ];
    }

    public function getChartData(): array
    {
        // Grafik: 5 Kecamatan dengan Penduduk Terbanyak
        $tableData = $this->getTableData()['rows'];
        $top5 = collect($tableData)->sortByDesc('Jumlah')->take(5);
        
        return [
            'type' => 'bar',
            'title' => '5 Kecamatan dengan Penduduk Terbanyak',
            'labels' => $top5->pluck('Kecamatan')->toArray(),
            'data' => $top5->pluck('Jumlah')->toArray(),
        ];
    }

    public function getInsightData(): array
    {
        // Insight: Total Penduduk dan Rasio Jenis Kelamin
        $values = $this->dataset->values()->where('year', $this->latestYear)->get();
        $totalLakiLaki = $values->where('turvar_label', 'Laki-laki')->sum('value');
        $totalPerempuan = $values->where('turvar_label', 'Perempuan')->sum('value');
        $totalPenduduk = $totalLakiLaki + $totalPerempuan;
        $sexRatio = ($totalPerempuan > 0) ? round(($totalLakiLaki / $totalPerempuan) * 100) : 0;

        return [
            [
                'title' => 'Total Penduduk',
                'value' => number_format($totalPenduduk) . ' Jiwa',
                'description' => 'Berdasarkan data tahun ' . $this->latestYear,
            ],
            [
                'title' => 'Rasio Jenis Kelamin',
                'value' => $sexRatio,
                'description' => 'Terdapat ' . $sexRatio . ' laki-laki untuk setiap 100 perempuan.',
            ],
        ];
    }
}