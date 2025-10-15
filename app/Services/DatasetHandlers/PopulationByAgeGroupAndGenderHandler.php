<?php

namespace App\Services\DatasetHandlers;

use App\Models\BpsDataset;
use Illuminate\Support\Collection;

class PopulationByAgeGroupAndGenderHandler implements DatasetHandlerInterface
{
    protected BpsDataset $dataset;
    protected ?int $latestYear;
    protected Collection $latestValues;
    protected string $ageGroupColumn; // Kolom untuk kelompok umur (dinamis)
    protected string $genderColumn;   // Kolom untuk jenis kelamin (dinamis)

    public function __construct(BpsDataset $dataset)
    {
        $this->dataset = $dataset;
        $this->latestYear = $dataset->values()->max('year');

        if ($this->latestYear) {
            // Ambil data dari DB hanya satu kali
            $this->latestValues = $dataset->values()->where('year', $this->latestYear)->get();
            // [LOGIKA BARU] Tentukan kolom mana yang untuk umur dan mana yang untuk gender
            $this->determineColumns();
        } else {
            $this->latestValues = collect();
            $this->ageGroupColumn = 'vervar_label'; // Default
            $this->genderColumn = 'turvar_label';   // Default
        }
    }

    /**
     * [FUNGSI BARU]
     * Secara cerdas mendeteksi kolom mana yang berisi kelompok umur dan mana yang jenis kelamin.
     */
    private function determineColumns(): void
    {
        if ($this->latestValues->isEmpty()) {
            $this->ageGroupColumn = 'vervar_label';
            $this->genderColumn = 'turvar_label';
            return;
        }

        // Logika: Kolom dengan variasi label paling banyak adalah kolom kelompok umur.
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

    public function getTableData(): array
    {
        // [DIUBAH] Gunakan kolom dinamis yang sudah terdeteksi
        $grouped = $this->latestValues->groupBy($this->ageGroupColumn);

        $rows = [];
        foreach ($grouped as $ageGroup => $data) {
            // [DIUBAH] Gunakan kolom gender dinamis
            $lakiLaki = $data->firstWhere($this->genderColumn, 'Laki-laki')->value ?? 0;
            $perempuan = $data->firstWhere($this->genderColumn, 'Perempuan')->value ?? 0;

            // Cek juga data 'Jumlah' jika disediakan oleh API
            $jumlahFromApi = $data->firstWhere($this->genderColumn, 'Jumlah')->value ?? ($lakiLaki + $perempuan);

            $rows[] = [
                'Kelompok Umur' => $ageGroup,
                'Laki-laki' => $lakiLaki,
                'Perempuan' => $perempuan,
                'Jumlah'    => $jumlahFromApi,
            ];
        }

        // Urutkan berdasarkan kelompok umur
        usort($rows, function ($a, $b) {
            return strcmp($a['Kelompok Umur'], $b['Kelompok Umur']);
        });

        return [
            'headers' => ["Kelompok Umur", "Laki-laki", "Perempuan", "Jumlah"],
            'rows' => $rows,
        ];
    }

    public function getChartData(): array
    {
        // Tidak perlu query lagi, panggil getTableData()
        $tableData = $this->getTableData()['rows'];

        // Untuk piramida penduduk, kita butuh data L/P terpisah
        $labels = collect($tableData)->pluck('Kelompok Umur')->toArray();
        $lakiData = collect($tableData)->pluck('Laki-laki')->toArray();
        $perempuanData = collect($tableData)->pluck('Perempuan')->toArray();

        return [
            'type' => 'pyramid', // Sarankan tipe chart 'pyramid'
            'title' => 'Piramida Penduduk ' . $this->latestYear,
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Laki-laki',
                    'data' => $lakiData,
                ],
                [
                    'label' => 'Perempuan',
                    'data' => $perempuanData,
                ],
            ]
        ];
    }

    public function getInsightData(): array
    {
        // Tidak perlu query lagi, panggil getTableData()
        $tableData = $this->getTableData()['rows'];
        if (empty($tableData)) {
            return [['title' => 'Info', 'value' => '-', 'description' => 'Data tidak tersedia.']];
        }

        $largestGroup = collect($tableData)->sortByDesc('Jumlah')->first();

        return [
            [
                'title' => 'Kelompok Umur Terbesar',
                'value' => $largestGroup['Kelompok Umur'] ?? 'N/A',
                'description' => 'Dengan total ' . number_format($largestGroup['Jumlah'] ?? 0) . ' jiwa pada tahun ' . $this->latestYear,
            ]
        ];
    }
}
