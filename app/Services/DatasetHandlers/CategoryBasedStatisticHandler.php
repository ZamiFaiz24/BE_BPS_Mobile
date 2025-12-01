<?php

namespace App\Services\DatasetHandlers;

use App\Models\BpsDataset;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CategoryBasedStatisticHandler implements DatasetHandlerInterface
{
    protected BpsDataset $dataset;
    protected ?int $year;

    // Settingan Kunci:
    protected string $rowLabelColumn = 'turvar_label'; // Baris = Kecamatan
    protected string $pivotColumn = 'vervar_label';    // Kolom = Dusun, RW, RT
    protected bool $isPercentage = false;
    protected array $excludedLabels = ['jumlah', 'total', 'kabupaten kebumen', 'jawa tengah', 'indonesia'];

    public function __construct(BpsDataset $dataset, $year = null)
    {
        $this->dataset = $dataset;
        $this->year = $year ?: $dataset->values()->max('year');

        // 1. Definisikan Default Dulu
        $this->rowLabelColumn = 'vervar_label';
        $this->pivotColumn = 'turvar_label';

        if ($this->year) {
            // 2. Deteksi Unit (Persen atau Nilai)
            $sample = $dataset->values()->where('year', $this->year)->first();
            $this->isPercentage = false;

            if ($sample) {
                // Deteksi Persen
                if (
                    Str::contains(strtolower($sample->unit), ['%', 'persen']) ||
                    Str::contains(strtolower($dataset->dataset_name), ['persentase', 'tingkat'])
                ) {
                    $this->isPercentage = true;
                }

                // --- LOGIKA DETEKSI PIVOT (ROW vs COL) ---
                // Kita hitung variasi datanya
                $allData = $dataset->values()->where('year', $this->year);
                $vervarCount = $allData->pluck('vervar_label')->unique()->count();
                $turvarCount = $allData->pluck('turvar_label')->unique()->count();

                // Kolom dengan variasi LEBIH BANYAK = Baris (Kecamatan)
                // Kolom dengan variasi LEBIH SEDIKIT = Header Kolom (Dusun/RW)
                if ($vervarCount >= $turvarCount) {
                    $this->rowLabelColumn = 'vervar_label'; // Kecamatan jadi Baris
                    $this->pivotColumn = 'turvar_label';    // Jenis jadi Kolom
                } else {
                    $this->rowLabelColumn = 'turvar_label';
                    $this->pivotColumn = 'vervar_label';
                }
            }
        }
    }

    public function getTableData(): array
    {
        // 1. Ambil Data
        $query = $this->dataset->values();
        if ($this->year) {
            $query->where('year', $this->year);
        }
        $data = $query->get();

        if ($data->isEmpty()) return ['headers' => [], 'rows' => []];

        // 2. Tentukan Header Kolom (Dusun, RW, RT)
        // Kita ambil isi dari 'vervar_label'
        $pivotHeaders = $data->pluck($this->pivotColumn)
            ->unique()
            ->filter(fn($val) => !empty($val) && strtolower($val) !== 'total')
            ->values()
            ->toArray();

        // Fallback jika datanya simple (tidak punya vervar)
        if (empty($pivotHeaders)) {
            $pivotHeaders = ['Nilai'];
        }

        // 3. Susun Header Final [Tahun, Kecamatan, Dusun, RW, RT]
        // Kita ubah label 'Kategori' jadi 'Kecamatan / Uraian' agar lebih jelas
        $headers = array_merge(['Tahun', 'Kecamatan'], $pivotHeaders);

        // 4. Grouping Data Berdasarkan Baris (Kecamatan/turvar)
        $grouped = $data->groupBy($this->rowLabelColumn);
        $rows = [];

        foreach ($grouped as $rowLabel => $items) {
            // Abaikan baris "Total" atau "Jumlah" di tabel agar tidak double dengan footer (opsional)
            // if (in_array(strtolower($rowLabel), ['jumlah', 'total'])) continue; 

            $row = [
                'Tahun' => $this->year,
                'Kecamatan' => $rowLabel, // Key ini harus sama dengan header di atas
            ];

            foreach ($pivotHeaders as $header) {
                // Cari nilai yang cocok:
                // Baris = Kecamatan ini (sudah di-filter lewat groupBy)
                // Kolom = Header ini (Dusun/RW/RT)
                $foundItem = $items->first(function ($item) use ($header) {
                    $itemCol = $item->{$this->pivotColumn} ?? 'Nilai';
                    return trim($itemCol) === trim($header)
                        || ($header === 'Nilai');
                });

                // Ambil nilainya
                $val = $foundItem ? $foundItem->value : 0;

                // Pastikan format Float agar dibaca angka oleh Android
                $row[$header] = (float) $val;
            }
            $rows[] = $row;
        }

        return ['headers' => $headers, 'rows' => $rows];
    }

    public function getChartData(): array
    {
        // Gunakan logika table data agar konsisten
        $tableData = $this->getTableData();
        $headers = $tableData['headers'];
        $rows = $tableData['rows'];

        if (empty($rows)) return [];

        // 1. Label Sumbu X (Kecamatan)
        $labels = [];
        $validIndices = [];

        foreach ($rows as $index => $row) {
            // Ambil nama kecamatan dari key 'Kecamatan'
            $catName = $row['Kecamatan'];

            // Filter "Kabupaten Kebumen" atau "Total" agar grafik tidak jomplang
            if (!in_array(strtolower($catName), $this->excludedLabels)) {
                $labels[] = $catName;
                $validIndices[] = $index;
            }
        }

        // 2. Dataset (Dusun, RW, RT)
        // Loop mulai index 2 (karena index 0=Tahun, 1=Kecamatan)
        $datasets = [];
        for ($i = 2; $i < count($headers); $i++) {
            $headerName = $headers[$i];
            $dataValues = [];

            foreach ($validIndices as $idx) {
                $dataValues[] = $rows[$idx][$headerName];
            }

            $datasets[] = [
                'label' => $headerName,
                'data' => $dataValues,
            ];
        }

        return [
            'type' => 'bar', // Bar chart paling cocok untuk perbandingan wilayah
            'title' => $this->dataset->dataset_name,
            'labels' => $labels,
            'datasets' => $datasets,
        ];
    }

    public function getInsightData(): array
    {
        // Logika sederhana: Ambil kolom data pertama (misal: Dusun) untuk insight
        $chartData = $this->getChartData();
        if (empty($chartData['datasets'])) return [];

        $dataset = $chartData['datasets'][0]; // Ambil dataset pertama
        $values = $dataset['data'];
        $labels = $chartData['labels'];

        if (empty($values)) return [];

        $maxVal = max($values);
        $maxIndex = array_search($maxVal, $values);
        $maxLabel = $labels[$maxIndex];

        $minVal = min($values);
        $minIndex = array_search($minVal, $values);
        $minLabel = $labels[$minIndex];

        return [
            [
                'title' => 'Tertinggi',
                'value' => $maxLabel,
                'description' => "Wilayah dengan {$dataset['label']} terbanyak adalah $maxLabel ($maxVal)"
            ],
            [
                'title' => 'Terendah',
                'value' => $minLabel,
                'description' => "Wilayah dengan {$dataset['label']} terendah adalah $minLabel ($minVal)"
            ]
        ];
    }

    public function getHistoryData(): array
    {
        // History Data (Tren Tahun ke Tahun)
        // Kita ambil Total per Tahun
        $history = $this->dataset->values()
            ->get()
            ->groupBy('year')
            ->map(function ($items) {
                // Prioritaskan baris "Kabupaten Kebumen" atau "Jumlah" jika ada
                $totalRow = $items->first(function ($i) {
                    return in_array(strtolower($i->{$this->rowLabelColumn}), ['jumlah', 'total', 'kabupaten kebumen']);
                });

                if ($totalRow) return $totalRow->value;

                // Jika persen, rata-rata. Jika nilai, sum.
                return $this->isPercentage ? $items->avg('value') : $items->sum('value');
            })
            ->sortKeys();

        return [
            'type' => 'line',
            'title' => 'Tren Tahunan',
            'labels' => $history->keys()->toArray(),
            'datasets' => [[
                'label' => 'Total',
                'data' => $history->values()->toArray(),
                'borderColor' => '#3b82f6',
            ]]
        ];
    }
}
