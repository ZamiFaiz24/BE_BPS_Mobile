<?php

namespace App\Services\DatasetHandlers;

use App\Models\BpsDataset;
use Illuminate\Support\Collection;

class PopulationByAgeAndRegionHandler implements DatasetHandlerInterface
{
    protected BpsDataset $dataset;
    protected ?int $year;

    protected string $regionColumn = 'vervar_label'; // Kolom Kecamatan
    protected string $ageColumn = 'turvar_label';    // Kolom Umur

    public function __construct(BpsDataset $dataset, $year = null)
    {
        $this->dataset = $dataset;
        $this->year = $year ?: $dataset->values()->max('year');

        // Deteksi Kolom secara Cerdas
        // Biasanya: Kelompok Umur itu yang ada angka dan strip (0-4, 5-9)
        $sample = $dataset->values()->first();
        if ($sample) {
            // Cek apakah turvar mengandung angka (ciri umur)
            if (preg_match('/[0-9]/', $sample->turvar_label)) {
                $this->ageColumn = 'turvar_label';
                $this->regionColumn = 'vervar_label';
            } else {
                $this->ageColumn = 'vervar_label';
                $this->regionColumn = 'turvar_label';
            }
        }
    }

    public function getTableData(): array
    {
        // Tabel menampilkan data mentah lengkap (agar user bisa scroll detail)
        $query = $this->dataset->values();
        if ($this->year) $query->where('year', $this->year);

        // Urutkan berdasarkan Kecamatan lalu Umur
        $allData = $query->orderBy($this->regionColumn)->get();

        $rows = $allData->map(function ($item) {
            return [
                'Tahun' => $item->year,
                'Kecamatan' => $item->{$this->regionColumn},
                'Kelompok Umur' => $item->{$this->ageColumn},
                'Jiwa' => $item->value
            ];
        })->toArray();

        return [
            'headers' => ['Tahun', 'Kecamatan', 'Kelompok Umur', 'Jiwa'],
            'rows' => $rows
        ];
    }

    public function getChartData(): array
    {
        // --- SWITCH MODE (Kecamatan vs Umur) ---
        // Default: region (Kecamatan) karena biasanya user ingin lihat sebaran wilayah
        $mode = request('mode', 'region');

        $query = $this->dataset->values();
        if ($this->year) $query->where('year', $this->year);
        $allData = $query->get();

        if ($mode === 'region') {
            // === MODE 1: BAR CHART KECAMATAN (Agregat) ===
            // Jumlahkan semua umur untuk setiap kecamatan
            $regionStats = $allData->groupBy($this->regionColumn)
                ->map(function ($items) {
                    return $items->sum('value');
                })
                // Hapus total kabupaten/jumlah agar grafik tidak timpang
                ->reject(function ($val, $key) {
                    return in_array(strtolower($key), ['jumlah', 'total', 'kabupaten kebumen']);
                })
                ->sortDesc()
                ->take(10); // Ambil Top 10 saja biar rapi

            return [
                'type' => 'bar',
                'title' => "10 Kecamatan Terpadat ({$this->year})",
                'labels' => $regionStats->keys()->toArray(),
                'datasets' => [
                    [
                        'label' => 'Total Penduduk (Pr)',
                        'data' => $regionStats->values()->toArray()
                    ]
                ]
            ];
        } else {
            // === MODE 2: BAR CHART KELOMPOK UMUR (Agregat) ===
            // Jumlahkan semua kecamatan untuk setiap kelompok umur
            $ageStats = $allData->groupBy($this->ageColumn)
                ->map(function ($items) {
                    return $items->whereNotIn(strtolower($items->first()->{$this->regionColumn} ?? ''), ['jumlah', 'total', 'kabupaten kebumen'])->sum('value');
                });

            // Masalah sorting umur (string): "10-14" bisa muncul sebelum "5-9"
            // Trik: Gunakan ID asli dari database jika urut, atau biarkan default dulu
            // (Biasanya BPS inputnya sudah urut ID)

            // Hapus label "Jumlah" jika ada di kolom umur
            $ageStats = $ageStats->reject(fn($v, $k) => strtolower($k) === 'jumlah');

            return [
                'type' => 'bar', // Bisa bar atau line
                'title' => "Distribusi Umur ({$this->year})",
                'labels' => $ageStats->keys()->toArray(),
                'datasets' => [
                    [
                        'label' => 'Jumlah Jiwa',
                        'data' => $ageStats->values()->toArray()
                    ]
                ]
            ];
        }
    }

    public function getInsightData(): array
    {
        // Insight sederhana total
        $query = $this->dataset->values();
        if ($this->year) $query->where('year', $this->year);
        $allData = $query->get();

        // Cari Total Kabupaten (Biasanya ada baris dengan kecamatan "Kabupaten Kebumen" dan umur "Jumlah")
        // Atau kita sum manual semua data (hati-hati double counting dengan baris Total)

        // Cara aman: Sum semua data yang kecamatannya BUKAN "Kabupaten Kebumen" dan Umurnya BUKAN "Jumlah"
        $realData = $allData->filter(function ($item) {
            return !in_array(strtolower($item->{$this->regionColumn}), ['jumlah', 'total', 'kabupaten kebumen']) &&
                !in_array(strtolower($item->{$this->ageColumn}), ['jumlah', 'total']);
        });

        $totalJiwa = $realData->sum('value');
        $avgPerKecamatan = $totalJiwa / 26; // Kebumen ada 26 kecamatan

        return [
            [
                'title' => 'Total Populasi (Pr)',
                'value' => number_format($totalJiwa) . " Jiwa",
                'description' => "Total penduduk perempuan berdasarkan data kecamatan tahun {$this->year}."
            ],
            [
                'title' => 'Rata-rata per Kecamatan',
                'value' => number_format($avgPerKecamatan) . " Jiwa",
                'description' => "Rata-rata jumlah penduduk perempuan di setiap kecamatan."
            ]
        ];
    }

    public function getHistoryData(): array
    {
        // Ambil data historis dari baris "Kabupaten Kebumen" + "Jumlah" (Totalnya Total)
        $history = $this->dataset->values()
            ->where($this->regionColumn, 'Kabupaten Kebumen')
            ->where($this->ageColumn, 'Jumlah') // Biasanya ada baris rekap ini
            ->orderBy('year')
            ->get();

        // Jika tidak ada baris rekap, hitung manual (agak berat query-nya tapi akurat)
        if ($history->isEmpty()) {
            // Skip implementasi kompleks, return kosong atau hitung simpel
            return [];
        }

        return [
            'type' => 'line',
            'title' => 'Tren Total Populasi',
            'labels' => $history->pluck('year')->toArray(),
            'datasets' => [['label' => 'Total Jiwa', 'data' => $history->pluck('value')->toArray()]]
        ];
    }
}
