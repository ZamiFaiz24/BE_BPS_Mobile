<?php

namespace App\Services\DatasetHandlers;

use App\Models\BpsDataset;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class GenderBasedStatisticHandler implements DatasetHandlerInterface
{
    protected BpsDataset $dataset;
    protected ?int $year;
    protected string $unit = '';
    protected string $genderColumn;
    protected Collection $dataForYear;
    protected bool $isPercent = false; // Flag untuk menandai apakah ini data Persen

    public function __construct(BpsDataset $dataset, $year = null)
    {
        $this->dataset = $dataset;
        $this->year = $year ?: $dataset->values()->max('year');

        if ($this->year) {
            // 1. Deteksi Kolom Gender
            $this->genderColumn = $this->detectGenderColumn();

            // 2. Ambil SEMUA data tahun ini (termasuk baris Total/Kabupaten)
            $this->dataForYear = $this->dataset->values()
                ->where('year', $this->year)
                ->get();

            // 3. Deteksi Unit & Apakah Persen?
            $firstItem = $this->dataForYear->first();
            $this->unit = $firstItem->unit ?? '';

            // Cek keyword persen, %, atau tpt (tingkat pengangguran terbuka)
            $this->isPercent = Str::contains(strtolower($this->unit), ['persen', '%'])
                || Str::contains(strtolower($dataset->dataset_name), ['tingkat pengangguran', 'tpt']);
        } else {
            $this->dataForYear = collect();
            $this->genderColumn = 'vervar_label';
        }
    }

    private function detectGenderColumn(): string
    {
        $sample = $this->dataset->values()->where('year', $this->year)->first();
        if (!$sample) return 'vervar_label';

        // Cek keywords Laki/Perempuan di kolom vervar vs turvar
        if (in_array($sample->vervar_label, ['Laki-laki', 'Perempuan', 'Laki-Laki + Perempuan'])) return 'vervar_label';
        if (in_array($sample->turvar_label, ['Laki-laki', 'Perempuan', 'Laki-Laki + Perempuan'])) return 'turvar_label';

        return 'vervar_label';
    }

    public function getTableData(): array
    {
        $rows = [];

        foreach ($this->dataForYear as $item) {
            $label = $item->{$this->genderColumn};
            $val = $item->value;

            // Cek apakah ini baris TOTAL (Kabupaten Kebumen / Jumlah)
            $isTotalRow = in_array(strtolower($label), ['jumlah', 'total', 'kabupaten kebumen', 'laki-laki + perempuan']);

            // --- LOGIKA UTAMA ---
            // Jika data BUKAN Persen (misal: Jiwa), kita SKIP baris Total agar tabel bersih
            if (!$this->isPercent && $isTotalRow) {
                continue;
            }

            // Jika baris Total, rapikan labelnya
            if ($isTotalRow) {
                $label = 'Total (Kabupaten)';
            }

            $rows[] = [
                'Tahun' => $item->year,
                'Kategori' => $label,
                'Nilai' => number_format($val, 2) . ' ' . $this->unit, // Format angka 2 desimal biar rapi
            ];
        }

        // Urutkan: Laki-laki, Perempuan, baru Total (jika ada)
        usort($rows, function ($a, $b) {
            $order = ['Laki-laki' => 1, 'Perempuan' => 2, 'Total (Kabupaten)' => 3];
            $valA = $order[$a['Kategori']] ?? 99;
            $valB = $order[$b['Kategori']] ?? 99;
            return $valA <=> $valB;
        });

        return [
            'headers' => ["Tahun", "Kategori", "Nilai"],
            'rows' => $rows,
        ];
    }

    public function getChartData(): array
    {
        // 1. Ambil Data
        $laki = $this->dataForYear->first(fn($i) => Str::contains(strtolower($i->{$this->genderColumn}), 'laki'))->value ?? 0;
        $pr   = $this->dataForYear->first(fn($i) => Str::contains(strtolower($i->{$this->genderColumn}), 'perempuan'))->value ?? 0;

        // 2. Cari Data Total
        $totalRow = $this->dataForYear->first(fn($i) => in_array(strtolower($i->{$this->genderColumn}), ['jumlah', 'total', 'kabupaten kebumen', 'laki-laki + perempuan']));
        $totalVal = $totalRow ? $totalRow->value : 0;

        // [MODIFIKASI] Ambil label asli dari database (misal: "Kabupaten Kebumen" atau "Total")
        // Kalau tidak ketemu, default ke "Total"
        $labelTotal = $totalRow ? $totalRow->{$this->genderColumn} : 'Total';

        // --- SKENARIO 1: DATA PERSEN (TPT/Kemiskinan) ---
        // Bar Chart dengan Label "Total"
        if ($this->isPercent) {
            return [
                'type' => 'bar',
                'title' => "Perbandingan Gender ({$this->year})",
                // [UBAH DISINI] Ganti 'Rata-rata Kab.' jadi variabel $labelTotal atau string 'Total'
                'labels' => ['Laki-laki', 'Perempuan', 'Total'],
                'datasets' => [
                    [
                        'label' => $this->unit,
                        'data' => [$laki, $pr, $totalVal],
                        'backgroundColor' => ['#36A2EB', '#FF6384', '#9E9E9E']
                    ]
                ]
            ];
        }

        // --- SKENARIO 2: DATA POPULASI (Jiwa) ---
        // Pie Chart
        else {
            return [
                'type' => 'pie',
                'title' => "Komposisi Gender ({$this->year})",
                'labels' => ['Laki-laki', 'Perempuan'],
                'datasets' => [
                    [
                        'label' => $this->unit,
                        'data' => [$laki, $pr],
                        'backgroundColor' => ['#36A2EB', '#FF6384']
                    ]
                ]
            ];
        }
    }
    public function getInsightData(): array
    {
        if ($this->dataForYear->isEmpty()) {
            return [['title' => 'Info', 'value' => '-', 'description' => 'Data Kosong']];
        }

        $laki = $this->dataForYear->first(fn($i) => Str::contains(strtolower($i->{$this->genderColumn}), 'laki'))->value ?? 0;
        $pr   = $this->dataForYear->first(fn($i) => Str::contains(strtolower($i->{$this->genderColumn}), 'perempuan'))->value ?? 0;

        $insights = [];

        // Insight 1: Mana Lebih Tinggi?
        $labelDominan = ($laki > $pr) ? 'Laki-laki' : 'Perempuan';
        $selisih = abs($laki - $pr);

        $desc = $this->isPercent
            ? "Tingkat {$this->dataset->dataset_name} pada $labelDominan lebih tinggi " . number_format($selisih, 2) . " poin."
            : "Jumlah $labelDominan lebih banyak " . number_format($selisih) . " jiwa.";

        $insights[] = [
            'title' => 'Nilai Tertinggi',
            'value' => $labelDominan,
            'description' => $desc
        ];

        // Insight 2: Selisih (Gap)
        $insights[] = [
            'title' => 'Selisih Gender',
            'value' => number_format($selisih, 2) . ' ' . $this->unit,
            'description' => "Jarak (gap) antara Laki-laki dan Perempuan."
        ];

        return $insights;
    }

    public function getHistoryData(): array
    {
        // Grafik Tren: Jika Persen, tampilkan 3 garis (L, P, Total)
        // Jika Jiwa, tampilkan 1 garis (Total L+P) atau Stacked

        // Sederhana saja: Tampilkan Tren Total dulu
        $history = $this->dataset->values()
            ->get()
            ->groupBy('year')
            ->map(function ($items) {
                // Cari row total dulu
                $total = $items->first(fn($i) => in_array(strtolower($i->{$this->genderColumn}), ['jumlah', 'total', 'kabupaten kebumen']));
                if ($total) return $total->value;

                // Kalau gak ada row total, jumlahkan L+P
                return $items->sum('value');
            })
            ->sortKeys();

        return [
            'type' => 'line',
            'title' => 'Tren Tahunan',
            'labels' => $history->keys()->toArray(),
            'datasets' => [
                [
                    'label' => 'Total / Rata-rata',
                    'data' => $history->values()->toArray()
                ]
            ]
        ];
    }
}
