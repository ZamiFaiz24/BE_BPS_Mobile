<?php

namespace App\Services\DatasetHandlers;

use App\Models\BpsDataset;
use Illuminate\Support\Facades\DB;

class GenderBasedStatisticHandler implements DatasetHandlerInterface
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
     * Helper untuk mengambil data dasar yang bersih
     */
    private function getBaseValues()
    {
        return $this->dataset->values()
            ->where('year', $this->year)
            ->whereIn('vervar_label', ['Laki-laki', 'Perempuan'])
            ->get();
    }

    public function getTableData(): array
    {
        $values = $this->getBaseValues();
        return [
            'headers' => ["Jenis Kelamin", $this->unit],
            'rows' => $values->map(function ($item) {
                return [
                    'Jenis Kelamin' => $item->vervar_label,
                    $this->unit => $item->value,
                ];
            })->all(),
        ];
    }

    public function getChartData(): array
    {
        $chartData = $this->getBaseValues();

        return [
            'type' => 'pie',
            'title' => 'Distribusi Menurut Jenis Kelamin (' . $this->unit . ')',
            'labels' => $chartData->pluck('vervar_label')->toArray(),
            'data' => $chartData->pluck('value')->toArray(),
        ];
    }

    public function getInsightData(): array
    {
        $values = $this->getBaseValues();

        $lakiLaki = $values->firstWhere('vervar_label', 'Laki-laki')->value ?? 0;
        $perempuan = $values->firstWhere('vervar_label', 'Perempuan')->value ?? 0;
        $insightValue = $lakiLaki > $perempuan ? 'Laki-laki' : 'Perempuan';

        return [
            [
                'title' => 'Nilai Tertinggi',
                'value' => $insightValue,
                'description' => 'Nilai untuk ' . $insightValue . ' lebih tinggi pada tahun ' . $this->year,
            ]
        ];
    }
}
