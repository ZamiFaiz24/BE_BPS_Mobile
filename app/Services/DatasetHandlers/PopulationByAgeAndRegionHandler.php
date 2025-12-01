<?php

namespace App\Services\DatasetHandlers;

use App\Models\BpsDataset;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PopulationByAgeAndRegionHandler implements DatasetHandlerInterface
{
    protected BpsDataset $dataset;
    protected Collection $dataForYear; // Data hanya untuk tahun yang dipilih
    protected ?int $selectedYear;
    protected string $mode; // 'region' (Kecamatan) atau 'age' (Umur)

    // Nama Kolom Dinamis
    protected string $regionColumn = 'vervar_label'; // Default
    protected string $ageColumn = 'turvar_label';    // Default

    /**
     * @param BpsDataset $dataset
     * @param int|null $year
     * @param string $mode  Bisa 'region' atau 'age' (dikirim dari Controller)
     */
    public function __construct(BpsDataset $dataset, $year = null, $mode = 'region')
    {
        $this->dataset = $dataset;
        $this->mode = $mode ?: 'region'; // Default ke region jika null

        // 1. Tentukan Tahun (Jika null, ambil tahun terbaru)
        $this->selectedYear = $year ?: $dataset->values()->max('year');

        // 2. Ambil data HANYA untuk tahun tersebut (Optimasi memori)
        if ($this->selectedYear) {
            $this->dataForYear = $dataset->values()->where('year', $this->selectedYear)->get();
            $this->detectColumns(); // Deteksi mana kolom Kecamatan, mana Umur
        } else {
            $this->dataForYear = collect();
        }
    }

    /**
     * Logika pintar untuk menentukan mana kolom Kecamatan dan mana Umur
     */
    private function detectColumns(): void
    {
        if ($this->dataForYear->isEmpty()) return;

        // Ambil sampel satu baris
        $sample = $this->dataForYear->first();

        // Logika sederhana: Cek isi stringnya
        // Biasanya Kecamatan ada di vervar, tapi kita cek kontennya
        $vervar = strtolower($sample->vervar_label ?? '');
        $turvar = strtolower($sample->turvar_label ?? '');

        // Cek keywords untuk Age (Umur)
        $ageKeywords = ['0-4', '5-9', 'umur', 'usia', 'tahunan'];

        $isVervarAge = Str::contains($vervar, $ageKeywords);

        if ($isVervarAge) {
            $this->ageColumn = 'vervar_label';
            $this->regionColumn = 'turvar_label';
        } else {
            // Default: Vervar adalah Kecamatan
            $this->regionColumn = 'vervar_label';
            $this->ageColumn = 'turvar_label';
        }
    }

    public function getChartData(): array
    {
        if ($this->dataForYear->isEmpty()) return [];

        // KATA KUNCI BLACKLIST (Untuk membuang baris Total/Kabupaten)
        $blacklist = ['jumlah', 'total', 'kabupaten', 'provinsi'];

        // Tentukan kolom mana yang jadi Grouping Utama berdasarkan Mode
        $groupByColumn = ($this->mode === 'age') ? $this->ageColumn : $this->regionColumn;

        // 1. Filter Data (Buang Total)
        $chartData = $this->dataForYear
            ->reject(function ($item) use ($blacklist, $groupByColumn) {
                $label = strtolower($item->{$groupByColumn});
                foreach ($blacklist as $keyword) {
                    if (Str::contains($label, $keyword)) return true;
                }
                return false;
            })
            // 2. Grouping
            ->groupBy($groupByColumn)
            // 3. Summing (Menjumlahkan)
            // Jika Mode Region: Jumlahkan semua umur di kecamatan itu
            // Jika Mode Age: Jumlahkan semua kecamatan di umur itu
            ->map(function ($group) {
                return $group->sum('value');
            });

        // 4. Sorting
        // Jika Umur, biasanya biarkan default urutan database (agar 0-4, 5-9 urut)
        // Jika Kecamatan, sort dari nilai tertinggi
        if ($this->mode === 'region') {
            $chartData = $chartData->sortDesc();
        }

        return [
            'type' => 'horizontalBar', // Bar chart mendatar lebih rapi untuk banyak data
            'title' => ($this->mode === 'age') ? 'Distribusi per Kelompok Umur' : 'Distribusi per Kecamatan',
            'labels' => $chartData->keys()->values()->all(),
            'data' => $chartData->values()->all(),
        ];
    }

    public function getInsightData(): array
    {
        if ($this->dataForYear->isEmpty()) return [];

        // Gunakan logika grouping yang sama dengan Chart
        // Agar insight sinkron dengan apa yang dilihat user
        $chartDataRaw = $this->getChartData();

        if (empty($chartDataRaw['data'])) return [];

        // Konversi kembali ke Collection untuk memudahkan manipulasi
        $labels = $chartDataRaw['labels'];
        $values = $chartDataRaw['data'];

        // Gabungkan Label dan Value
        $combined = collect(array_combine($labels, $values));

        $maxVal = $combined->max();
        $maxKey = $combined->search($maxVal);

        $minVal = $combined->min();
        $minKey = $combined->search($minVal);

        $totalPopulasi = $combined->sum();

        // Buat kalimat insight dinamis
        $entityName = ($this->mode === 'age') ? 'Kelompok Umur' : 'Kecamatan';

        $insights = [
            [
                'title' => "$entityName Tertinggi",
                'value' => $maxKey,
                'description' => "$entityName dengan populasi perempuan terbanyak adalah $maxKey (" . number_format($maxVal) . " Jiwa)."
            ],
            [
                'title' => "$entityName Terendah",
                'value' => $minKey,
                'description' => "$entityName dengan populasi perempuan paling sedikit adalah $minKey (" . number_format($minVal) . " Jiwa)."
            ]
        ];

        // Tambahkan Insight Proporsi
        if ($totalPopulasi > 0) {
            $percentage = ($maxVal / $totalPopulasi) * 100;
            $insights[] = [
                'title' => 'Dominasi',
                'value' => number_format($percentage, 1) . '%',
                'description' => "Sekitar " . number_format($percentage, 1) . "% dari total data terpusat di $maxKey."
            ];
        }

        return $insights;
    }

    public function getTableData(): array
    {
        // Untuk Tabel, kita tampilkan Matrix (Pivot)
        // Baris: Kecamatan, Kolom: Kelompok Umur (atau sebaliknya)
        // Tapi format tabel Anda sebelumnya simple list. 
        // Mari kita buat format List standar tapi difilter tahun.

        $rows = $this->dataForYear->map(function ($item) {
            return [
                'Tahun' => $item->year,
                'Kecamatan' => $item->{$this->regionColumn},
                'Kelompok Umur' => $item->{$this->ageColumn},
                'Jiwa' => number_format($item->value)
            ];
        });

        return [
            'headers' => ['Tahun', 'Kecamatan', 'Kelompok Umur', 'Jiwa'],
            'rows' => $rows
        ];
    }
}
