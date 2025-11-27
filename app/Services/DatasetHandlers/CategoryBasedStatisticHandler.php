<?php

namespace App\Services\DatasetHandlers;

use App\Models\BpsDataset;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CategoryBasedStatisticHandler implements DatasetHandlerInterface
{
    protected BpsDataset $dataset;
    protected ?int $year;
    protected string $categoryColumn;
    protected string $primaryUnit = 'Nilai';

    public function __construct(BpsDataset $dataset, $year = null)
    {
        $this->dataset = $dataset;
        $this->year = $year ?: $dataset->values()->max('year');

        // Deteksi kolom kategori
        $sample = $dataset->values()->first();
        if ($sample) {
            $vervarCount = $dataset->values()->pluck('vervar_label')->unique()->count();
            $turvarCount = $dataset->values()->pluck('turvar_label')->unique()->count();
            $this->categoryColumn = ($turvarCount > $vervarCount) ? 'turvar_label' : 'vervar_label';
        } else {
            $this->categoryColumn = 'turvar_label';
        }

        // Deteksi Unit Utama berdasarkan Judul Dataset
        if (
            Str::contains(strtolower($dataset->dataset_name), 'persentase') ||
            Str::contains(strtolower($dataset->dataset_name), 'distribusi')
        ) {
            $this->primaryUnit = 'Persen';
        } else {
            $this->primaryUnit = 'Nilai';
        }
    }

    public function getTableData(): array
    {
        // ... (Kode getTableData SAMA PERSIS seperti sebelumnya, tidak perlu diubah) ...
        // ... Copy paste dari jawaban sebelumnya ...
        // (Saya singkat biar tidak kepanjangan, intinya tabel menampilkan semua unit)

        $query = $this->dataset->values();
        if ($this->year) $query->where('year', $this->year);

        $allData = $query->orderBy('year', 'desc')->orderBy($this->categoryColumn, 'asc')->get();
        if ($allData->isEmpty()) return ['headers' => [], 'rows' => []];

        $units = $allData->pluck('unit')->unique()->filter()->values()->toArray();
        if (empty($units)) $units = ['Nilai'];
        $headers = array_merge(['Tahun', 'Kategori'], $units);
        $rows = [];
        $groupedByYear = $allData->groupBy('year');

        foreach ($groupedByYear as $year => $itemsInYear) {
            $groupedByCategory = $itemsInYear->groupBy($this->categoryColumn);
            foreach ($groupedByCategory as $category => $items) {
                // DI TABEL: "Jumlah" TETAP DITAMPILKAN (Biar user tau totalnya)
                $row = ['Tahun' => $year, 'Kategori' => $category];
                foreach ($units as $unit) {
                    $item = $items->first(function ($val) use ($unit) {
                        return strtolower($val->unit) == strtolower($unit) || ($unit == 'Nilai');
                    });
                    $row[$unit] = $item ? $item->value : null;
                }
                $rows[] = $row;
            }
        }
        return ['headers' => $headers, 'rows' => $rows];
    }

    public function getChartData(): array
    {
        // 1. Ambil data tahun terpilih
        $query = $this->dataset->values();
        if ($this->year) $query->where('year', $this->year);
        $allItems = $query->get();

        // 2. FILTER DATA UNTUK GRAFIK
        $chartDataRaw = $allItems->filter(function ($item) {
            $kategori = strtolower($item->{$this->categoryColumn});
            $unit = strtolower($item->unit);

            // ATURAN A: BUANG KATEGORI "JUMLAH" / "TOTAL" (Wajib!)
            if (in_array($kategori, ['jumlah', 'total'])) {
                return false;
            }

            // ATURAN B: Pilih Unit yang sesuai
            if ($this->primaryUnit === 'Persen') {
                return $unit === 'persen' || $unit === '%';
            } else {
                return $unit !== 'persen' && $unit !== '%';
            }
        });

        // Fallback: Jika filter unit bikin kosong, ambil apa adanya (kecuali Jumlah)
        if ($chartDataRaw->isEmpty()) {
            $chartDataRaw = $allItems->filter(fn($i) => !in_array(strtolower($i->{$this->categoryColumn}), ['jumlah', 'total']));
        }

        // 3. TENTUKAN TIPE CHART
        // Jika unitnya Persen -> Pie Chart
        // Jika bukan -> Bar Chart
        $chartType = ($this->primaryUnit === 'Persen') ? 'pie' : 'bar';

        // Urutkan data (Pie enak dilihat kalau urut besar ke kecil)
        $chartDataSorted = $chartDataRaw->sortByDesc('value');

        return [
            'type' => $chartType, // <-- Ini akan dinamis (pie/bar)
            'title' => $this->dataset->dataset_name,
            'labels' => $chartDataSorted->pluck($this->categoryColumn)->toArray(),
            'datasets' => [
                [
                    'label' => $this->primaryUnit,
                    'data' => $chartDataSorted->pluck('value')->values()->toArray(),
                ]
            ]
        ];
    }

    // ... (getInsightData dan getHistoryData SAMA seperti sebelumnya) ...
    public function getInsightData(): array
    { /* ... logic lama ... */
        return [];
    }
    public function getHistoryData(): array
    { /* ... logic lama ... */
        return [];
    }
}
