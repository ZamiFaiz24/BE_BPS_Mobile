<?php

namespace App\Services\DatasetHandlers;

use App\Models\BpsDataset;
use Illuminate\Support\Collection;

class GenderBasedStatisticHandler implements DatasetHandlerInterface
{
    protected BpsDataset $dataset;
    protected ?int $year;
    protected string $unit = '';
    protected string $genderColumn; // [DITAMBAHKAN] Untuk menyimpan nama kolom yang benar
    protected Collection $latestValues;

    public function __construct(BpsDataset $dataset, $year = null)
    {
        $this->dataset = $dataset;
        $this->year = $year ?: $dataset->values()->max('year');

        if ($this->year) {
            // [LOGIKA BARU] Deteksi kolom gender secara otomatis
            $this->genderColumn = $this->detectGenderColumn();

            $this->latestValues = $this->fetchValuesForYear($this->year);
            $this->unit = $this->latestValues->first()->unit ?? 'Persen';
        } else {
            $this->latestValues = collect();
            $this->genderColumn = 'vervar_label'; // Default jika tidak ada data
        }
    }

    /**
     * [FUNGSI BARU]
     * Secara cerdas mendeteksi apakah data gender ada di 'vervar_label' atau 'turvar_label'.
     */
    private function detectGenderColumn(): string
    {
        // Ambil satu sampel data untuk dianalisis
        $sample = $this->dataset->values()->where('year', $this->year)->first();

        if (!$sample) {
            return 'vervar_label'; // Default jika tidak ada sampel
        }

        // Cek di laci pertama ('vervar_label')
        if (in_array($sample->vervar_label, ['Laki-laki', 'Perempuan'])) {
            return 'vervar_label';
        }

        // Jika tidak ada, cek di laci kedua ('turvar_label')
        if (in_array($sample->turvar_label, ['Laki-laki', 'Perempuan'])) {
            return 'turvar_label';
        }

        // Jika tidak ada di keduanya, kembalikan default
        return 'vervar_label';
    }

    /**
     * Helper untuk mengambil data berdasarkan kolom gender yang sudah terdeteksi.
     */
    private function fetchValuesForYear(int $year): Collection
    {
        return $this->dataset->values()
            ->where('year', $year)
            // [DIUBAH] Menggunakan variabel dinamis $this->genderColumn
            ->whereIn($this->genderColumn, ['Laki-laki', 'Perempuan'])
            ->get();
    }

    public function getTableData(): array
    {
        // Query Dasar
        $query = $this->dataset->values()
            ->whereIn($this->genderColumn, ['Laki-laki', 'Perempuan']);

        // [PERBAIKAN] Hormati filter tahun
        if ($this->year) {
            $query->where('year', $this->year);
        }

        $allData = $query->orderBy('year', 'desc')->get();

        $rows = $allData->map(function ($item) {
            return [
                'Tahun' => $item->year,
                'Jenis Kelamin' => $item->{$this->genderColumn},
                $this->unit => $item->value,
            ];
        })->all();

        return [
            'headers' => ["Tahun", "Jenis Kelamin", $this->unit],
            'rows' => $rows,
        ];
    }

    public function getChartData(): array
    {
        return [
            'type' => 'pie',
            'title' => 'Distribusi Menurut Jenis Kelamin (' . $this->unit . ')',
            // [DIUBAH] Menggunakan kolom yang benar
            'labels' => $this->latestValues->pluck($this->genderColumn)->toArray(),
            'data' => $this->latestValues->pluck('value')->toArray(),
        ];
    }

    public function getInsightData(): array
    {
        if ($this->latestValues->isEmpty()) {
            return [['title' => 'Info', 'value' => 'Data tidak tersedia', 'description' => 'Tidak ada data untuk ditampilkan.']];
        }

        $lakiLaki = $this->latestValues->firstWhere($this->genderColumn, 'Laki-laki')->value ?? 0;
        $perempuan = $this->latestValues->firstWhere($this->genderColumn, 'Perempuan')->value ?? 0;

        // ... (Logika insight lainnya tetap sama, tidak perlu diubah)
        $insightValue = $lakiLaki > $perempuan ? 'Laki-laki' : 'Perempuan';
        $selisih = round(abs($lakiLaki - $perempuan), 2);

        $prevValues = $this->fetchValuesForYear($this->year - 1);
        $prevLaki = $prevValues->firstWhere($this->genderColumn, 'Laki-laki')->value ?? null;
        $prevPerempuan = $prevValues->firstWhere($this->genderColumn, 'Perempuan')->value ?? null;

        $persenLaki = ($prevLaki && $lakiLaki) ? round((($lakiLaki - $prevLaki) / $prevLaki) * 100, 2) : null;
        $persenPerempuan = ($prevPerempuan && $perempuan) ? round((($perempuan - $prevPerempuan) / $perempuan) * 100, 2) : null;

        return [
            [
                'title' => 'Nilai Tertinggi',
                'value' => $insightValue,
                'description' => 'Nilai untuk ' . $insightValue . ' lebih tinggi pada tahun ' . $this->year,
            ],
            [
                'title' => 'Selisih Nilai',
                'value' => $selisih . " " . $this->unit,
                'description' => 'Selisih antara Laki-laki dan Perempuan pada tahun ' . $this->year,
            ],
            [
                'title' => 'Perubahan Laki-laki',
                'value' => $persenLaki !== null ? $persenLaki . '%' : '-',
                'description' => 'Perubahan nilai Laki-laki dibanding tahun sebelumnya.',
            ],
            [
                'title' => 'Perubahan Perempuan',
                'value' => $persenPerempuan !== null ? $persenPerempuan . '%' : '-',
                'description' => 'Perubahan nilai Perempuan dibanding tahun sebelumnya.',
            ],
        ];
    }

    public function getHistoryData(): array
    {
        // Ambil semua data urut tahun lama -> baru
        $allValues = $this->dataset->values()
            ->whereIn($this->genderColumn, ['Laki-laki', 'Perempuan'])
            ->orderBy('year', 'asc')
            ->get();

        if ($allValues->isEmpty()) return [];

        // Hitung total (L+P) per tahun
        $yearlyTotals = $allValues->groupBy('year')->map(function ($items) {
            return $items->sum('value');
        });

        return [
            'type' => 'line',
            'title' => 'Tren Total (L+P) dari Tahun ke Tahun',
            'labels' => $yearlyTotals->keys()->toArray(),
            'datasets' => [
                [
                    'label' => 'Total Nilai',
                    'data' => $yearlyTotals->values()->toArray(),
                ],
            ],
        ];
    }
}
