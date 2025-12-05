<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BpsDataset;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BpsDataController extends Controller
{
    public function show($id)
    {
        $dataset = BpsDataset::where('dataset_code', $id)
            ->with('values')
            ->firstOrFail();
        return response()->json($dataset);
    }

    public function getGenderChartData($dataset_code, $year)
    {
        // 1. Cari dulu dataset_id berdasarkan dataset_code
        $dataset = BpsDataset::where('dataset_code', $dataset_code)->first();

        if (!$dataset) {
            return response()->json(['message' => 'Dataset not found'], 404);
        }

        // 2. Lakukan query agregasi ke tabel bps_datavalue
        $chartData = DB::table('bps_datavalue')
            // Pilih kolom label dan jumlahkan (SUM) kolom value
            ->select('turvar_label', DB::raw('SUM(value) as total_value'))
            // Filter berdasarkan dataset_id dan tahun yang diminta
            ->where('dataset_id', $dataset->id)
            ->where('year', $year)
            // Kita hanya mau data Laki-laki dan Perempuan, bukan 'Jumlah'
            ->whereIn('turvar_label', ['Laki-laki', 'Perempuan'])
            // Kelompokkan hasilnya berdasarkan label
            ->groupBy('turvar_label')
            ->get();

        // 3. Ubah format data agar lebih mudah dibaca oleh Frontend
        $formattedData = $chartData->map(function ($item) {
            return [
                'label' => $item->turvar_label,
                'value' => (int) $item->total_value, // Ubah ke integer
            ];
        });

        // 4. Kirim respons JSON yang sudah siap pakai
        return response()->json([
            'chart_title' => "Distribusi Penduduk Laki-laki & Perempuan Tahun {$year}",
            'data_points' => $formattedData,
        ]);
    }

    /**
     * Ambil nilai terbaru dari multiple datasets untuk insight
     * Contoh: /api/insights/indicators
     */
    public function getInsightIndicators()
    {
        // Define dataset yang akan diambil untuk insight (sesuaikan dengan ID di database Anda)
        // Format: 'key_untuk_fe' => dataset_id
        $datasetIds = [
            'angkatan_kerja' => 21,          // Penduduk Berumur 15 Tahun Ke Atas yang Termasuk Angkatan Kerja
            'bencana_alam' => 22,            // Jumlah Kejadian Bencana Alam
            'dusun_rw_rt' => 23,             // Jumlah Dusun, RW, dan RT
            'beban_ketergantungan' => 24,    // Angka Beban Ketergantungan
        ];

        $result = [];

        foreach ($datasetIds as $key => $datasetId) {
            // Cari dataset
            $dataset = BpsDataset::find($datasetId);

            if ($dataset) {
                // Ambil nilai terbaru (latest value) beserta unitnya
                $latestValue = DB::table('bps_datavalue')
                    ->where('dataset_id', $datasetId)
                    ->orderBy('year', 'desc')
                    ->orderBy('id', 'desc')
                    ->first();

                // Store hasil
                $result[$key] = [
                    'dataset_id' => $datasetId,
                    'dataset_name' => $dataset->dataset_name ?? null,
                    'value' => $latestValue?->value ?? null,
                    'year' => $latestValue?->year ?? null,
                    'unit' => $latestValue?->unit ?? null,  // Ambil dari bps_datavalue
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $result,
            'timestamp' => now(),
        ]);
    }

    /**
     * Manual update unit untuk dataset tertentu (untuk fix data yang unit-nya kosong)
     * POST /api/update-dataset-unit
     * Body: { "dataset_id": 21, "unit": "Orang" }
     */
    public function updateDatasetUnit(Request $request)
    {
        $validated = $request->validate([
            'dataset_id' => 'required|integer|exists:bps_dataset,id',
            'unit' => 'required|string|max:100',
        ]);

        $updated = DB::table('bps_datavalue')
            ->where('dataset_id', $validated['dataset_id'])
            ->update(['unit' => $validated['unit']]);

        return response()->json([
            'status' => 'success',
            'message' => "Unit updated for {$updated} records",
            'dataset_id' => $validated['dataset_id'],
            'unit' => $validated['unit'],
            'records_affected' => $updated,
        ]);
    }

    /**
     * Auto-fix unit untuk semua dataset berdasarkan nama dataset
     * POST /api/auto-fix-dataset-units
     * Ini akan secara otomatis menentukan unit yang sesuai berdasarkan nama dataset
     */
    public function autoFixDatasetUnits()
    {
        // Mapping dataset_id ke unit yang sesuai berdasarkan nama dataset
        $unitMappings = [
            5 => 'Jiwa',                      // Jumlah Penduduk
            6 => 'Persen',                    // Tingkat Pengangguran Terbuka (Persen)
            7 => 'Persen',                    // Tingkat Pengangguran Terbuka (Persen)
            8 => 'Jiwa',                      // Jumlah Penduduk
            9 => 'Persen',                    // Persentase Penduduk
            10 => 'Persen',                   // TPAK (Tingkat Partisipasi Angkatan Kerja)
            11 => 'Persen',                   // Distribusi PDRB
            12 => 'Milyar Rupiah',            // PDRB Triwulanan
            13 => 'Milyar Rupiah',            // PDRB Triwulanan
            14 => 'Milyar Rupiah',            // PDRB Triwulanan
            15 => 'Milyar Rupiah',            // PDRB Triwulanan
            20 => 'Jiwa',                     // Penduduk Menurut Kelompok Umur
            21 => 'Persen',                   // Angkatan Kerja
            22 => 'Kejadian',                 // Jumlah Kejadian Bencana
            23 => 'Unit',                     // Jumlah Dusun, RW, RT
            24 => 'Persen',                   // Angka Beban Ketergantungan
            25 => 'Orang',                    // Jumlah Perjalanan Wisatawan
            26 => 'Orang',                    // Jumlah Perjalanan Wisatawan
            27 => 'Orang',                    // Jumlah Wisatawan
            28 => 'Rupiah',                   // Rata-rata Upah/Gaji
            29 => 'Rupiah',                   // Rata-rata Pendapatan
            30 => 'Indeks',                   // Gini Rasio
            31 => 'Persen',                   // Indeks Kedalaman Kemiskinan
            32 => 'Persen',                   // Indeks Keparahan Kemiskinan
            33 => 'Indeks',                   // Indeks Pembangunan Manusia
            34 => 'Indeks',                   // Indeks Pembangunan Manusia
            35 => 'Nilai',                    // UHH, HLS, RLS, dll
        ];

        $results = [];
        $totalUpdated = 0;

        foreach ($unitMappings as $datasetId => $unit) {
            $dataset = BpsDataset::find($datasetId);

            if ($dataset) {
                $updated = DB::table('bps_datavalue')
                    ->where('dataset_id', $datasetId)
                    ->update(['unit' => $unit]);

                $results[] = [
                    'dataset_id' => $datasetId,
                    'dataset_name' => substr($dataset->dataset_name, 0, 80) . '...',
                    'unit' => $unit,
                    'records_affected' => $updated,
                ];

                $totalUpdated += $updated;
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => "Auto-fix completed. Total records updated: {$totalUpdated}",
            'total_datasets_fixed' => count($results),
            'updates' => $results,
        ]);
    }

    /**
     * Lihat semua unit yang ada di setiap dataset
     * GET /api/dataset-units
     */
    public function getDatasetUnits()
    {
        $datasets = BpsDataset::all();
        $result = [];

        foreach ($datasets as $dataset) {
            // Ambil semua unit unik untuk dataset ini
            $units = DB::table('bps_datavalue')
                ->where('dataset_id', $dataset->id)
                ->distinct('unit')
                ->pluck('unit')
                ->filter() // Hilangkan nilai null
                ->values();

            $result[] = [
                'dataset_id' => $dataset->id,
                'dataset_name' => $dataset->dataset_name,
                'units' => $units,
                'unit_count' => $units->count(),
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $result,
            'total_datasets' => count($result),
        ]);
    }

    /**
     * Lihat unit untuk dataset tertentu
     * GET /api/dataset-units/21
     */
    public function getDatasetUnitsById($datasetId)
    {
        $dataset = BpsDataset::find($datasetId);

        if (!$dataset) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dataset not found'
            ], 404);
        }

        // Ambil sample data dengan unit berbeda
        $dataWithUnits = DB::table('bps_datavalue')
            ->where('dataset_id', $datasetId)
            ->select('unit', DB::raw('COUNT(*) as count'), DB::raw('MAX(year) as latest_year'))
            ->groupBy('unit')
            ->get();

        return response()->json([
            'status' => 'success',
            'dataset_id' => $datasetId,
            'dataset_name' => $dataset->dataset_name,
            'units_breakdown' => $dataWithUnits,
        ]);
    }
}
