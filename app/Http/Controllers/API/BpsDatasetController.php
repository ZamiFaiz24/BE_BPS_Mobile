<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BpsDataset; // Menggunakan model Anda
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \Exception;

// Impor semua kelas Handler yang akan Anda gunakan
use App\Services\DatasetHandlers\PopulationByAgeGroupAndGenderHandler;
use App\Services\DatasetHandlers\SingleValueTimeSeriesHandler;
use App\Services\DatasetHandlers\GenderBasedStatisticHandler;
use App\Services\DatasetHandlers\CategoryBasedStatisticHandler;

class BpsDatasetController extends Controller
{

    protected $handlerMapping = [
        5  => PopulationByAgeGroupAndGenderHandler::class,

        6  => GenderBasedStatisticHandler::class,
        10 => GenderBasedStatisticHandler::class,

        7  => CategoryBasedStatisticHandler::class,
        9  => CategoryBasedStatisticHandler::class,

        // Contoh mapping untuk ID dataset mulai dari 11
        11 => CategoryBasedStatisticHandler::class, // Distribusi Produk Domestik Regional Bruto (PDRB) ...
        12 => CategoryBasedStatisticHandler::class, // Produk Domestik Regional Bruto (PDRB) Triwulanan ...
        13 => CategoryBasedStatisticHandler::class, // Produk Domestik Regional Bruto (PDRB) Triwulanan ...
        14 => CategoryBasedStatisticHandler::class, // Produk Domestik Regional Bruto (PDRB) Triwulanan ...
        15 => CategoryBasedStatisticHandler::class, // Produk Domestik Regional Bruto (PDRB) Triwulanan ...
        20 => PopulationByAgeGroupAndGenderHandler::class, // Penduduk Menurut Kelompok Umur dan Kecamatan ...
        21 => CategoryBasedStatisticHandler::class, // Penduduk Berumur 15 Tahun Ke Atas yang Termasuk ...
        22 => CategoryBasedStatisticHandler::class, // Jumlah Kejadian Bencana Alam Menurut Kecamatan ...
        23 => CategoryBasedStatisticHandler::class, // Jumlah Dusun, Rukun Warga (RW), dan Rukun Tetangga ...
        24 => SingleValueTimeSeriesHandler::class, // Angka Beban Ketergantungan di Kabupaten Kebumen
        // ...tambahkan mapping lain sesuai kebutuhan
    ];

    /**
     * Get list of datasets (for Layer 3: Dataset List based on selected subject)
     * Filter by subject (NOT category)
     * GET /api/datasets?subject=Penduduk
     */
    public function index(Request $request)
    {
        try {
            $modelClass = \App\Models\BpsDataset::class;

            if (!class_exists($modelClass)) {
                throw new Exception('Server setup error: Model not found.');
            }

            // 2. Ambil filter dari URL query
            $subject = $request->query('subject'); // FILTER BERDASARKAN SUBJECT (untuk Layar 3)
            $q = $request->query('q'); // search

            // 3. Mulai Query Builder
            $query = $modelClass::query();

            // 4. Pilih kolom ringan (termasuk subject dan category)
            $query->select([
                'id',
                'dataset_name',
                'subject',   // Untuk filter Layar 3
                'category',  // Untuk info tambahan (opsional)
            ]);

            // 5. Terapkan filter 'subject' (jika ada) - UNTUK LAYAR 3
            if ($subject) {
                $query->where('subject', $subject);
            }

            // 6. Pencarian judul
            if ($q) {
                $query->where('dataset_name', 'like', "%{$q}%");
            }

            // 7. Ambil data (maks 100)
            $datasets = $query->limit(100)->get();

            // 8. Response ringan
            return response()->json($datasets);
        } catch (\Exception $e) {
            Log::error('Error in BpsDatasetController@index: ' . $e->getMessage());

            return response()->json([
                'error_A' => 'Terjadi kesalahan pada server.',
                'error_B_message' => $e->getMessage(),
                'error_C_file' => $e->getFile(),
                'error_D_line' => $e->getLine()
            ], 500);
        }
    }

    public function show(BpsDataset $dataset)
    {
        // 1. Cek apakah ada "spesialis" yang terdaftar untuk dataset ini di peta.
        if (!isset($this->handlerMapping[$dataset->id])) {
            return response()->json([
                'error' => 'Tampilan data untuk dataset ini belum dikonfigurasi.'
            ], 404);
        }

        // 2. Ambil nama kelas "spesialis" dari peta.
        $handlerClass = $this->handlerMapping[$dataset->id];

        // Ambil parameter year dari query string, jika ada
        $year = request('year');

        // Buat handler, berikan dataset dan year (jika ada)
        $handler = app()->make($handlerClass, [
            'dataset' => $dataset,
            'year' => $year,
        ]);

        // 4. Minta "spesialis" untuk mengerjakan semua tugasnya.
        $tableData = $handler->getTableData();
        $chartData = $handler->getChartData();
        $insightData = $handler->getInsightData();

        // 5. Gabungkan semua hasil pekerjaan menjadi satu laporan lengkap dan kirimkan.
        return response()->json([
            'dataset' => $dataset,
            'table' => $tableData,
            'chart' => $chartData,
            'insights' => $insightData,
        ]);
    }

    public function history(BpsDataset $dataset)
    {
        if (!isset($this->handlerMapping[$dataset->id])) {
            return response()->json([
                'error' => 'Tampilan data untuk dataset ini belum dikonfigurasi.'
            ], 404);
        }
        $handlerClass = $this->handlerMapping[$dataset->id];
        $year = request('year');
        $handler = app()->make($handlerClass, [
            'dataset' => $dataset,
            'year' => $year,
        ]);
        $historyData = method_exists($handler, 'getHistoryData')
            ? $handler->getHistoryData()
            : [];
        return response()->json([
            'dataset' => $dataset,
            'history' => $historyData,
        ]);
    }

    public function insights(BpsDataset $dataset)
    {
        if (!isset($this->handlerMapping[$dataset->id])) {
            return response()->json([
                'error' => 'Tampilan data untuk dataset ini belum dikonfigurasi.'
            ], 404);
        }
        $handlerClass = $this->handlerMapping[$dataset->id];
        $year = request('year');
        $handler = app()->make($handlerClass, [
            'dataset' => $dataset,
            'year' => $year,
        ]);
        $insightData = $handler->getInsightData();
        return response()->json([
            'dataset' => $dataset,
            'insights' => $insightData,
        ]);
    }

    /**
     * Get categories with their subjects (for Layer 2: Category & Subject selection)
     * GET /api/datasets/categories
     * 
     * JANGAN DIUBAH - INI SUDAH BENAR
     */
    public function getCategories(Request $request)
    {
        try {
            $modelClass = \App\Models\BpsDataset::class;

            if (!class_exists($modelClass)) {
                throw new Exception('Server setup error: Model not found.');
            }

            // Ambil semua category dan subject yang unik dari database
            $categoriesWithSubjects = $modelClass::select('category', 'subject')
                ->whereNotNull('category')
                ->whereNotNull('subject')
                ->distinct()
                ->get();

            // Group subjects by category
            $groupedData = [];

            foreach ($categoriesWithSubjects as $item) {
                $category = $item->category;
                $subject = $item->subject;

                // Jika category belum ada di array, tambahkan
                if (!isset($groupedData[$category])) {
                    $groupedData[$category] = [
                        'category' => $category,
                        'subjects' => []
                    ];
                }

                // Tambahkan subject ke array (hindari duplikat)
                if (!in_array($subject, $groupedData[$category]['subjects'])) {
                    $groupedData[$category]['subjects'][] = $subject;
                }
            }

            // Sort subjects dalam setiap category
            foreach ($groupedData as &$categoryData) {
                sort($categoryData['subjects']);
            }

            // Convert associative array ke indexed array dan sort by category name
            $result = array_values($groupedData);
            usort($result, function ($a, $b) {
                return strcmp($a['category'], $b['category']);
            });

            return response()->json($result, 200);
        } catch (\Exception $e) {
            Log::error('Error in BpsDatasetController@getCategories: ' . $e->getMessage());

            return response()->json([
                'error' => 'Terjadi kesalahan pada server.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
