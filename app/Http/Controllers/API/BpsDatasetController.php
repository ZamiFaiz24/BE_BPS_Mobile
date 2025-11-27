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

    private function detectHandler(BpsDataset $dataset)
    {
        $judul = strtolower($dataset->dataset_name);

        // ATURAN 1: Cek Piramida Penduduk (Paling Spesifik)
        // Ciri: Ada kata "kelompok umur" dan "jenis kelamin"
        if (str_contains($judul, 'kelompok umur') && str_contains($judul, 'jenis kelamin')) {
            return PopulationByAgeGroupAndGenderHandler::class;
        }

        // ATURAN 2: Cek Gender
        // Ciri: Ada kata "jenis kelamin" tapi bukan kelompok umur
        if (str_contains($judul, 'jenis kelamin')) {
            return GenderBasedStatisticHandler::class;
        }

        // ATURAN 3: Cek Kategori
        // Ciri: Ada kata "menurut", "berdasarkan", atau "tipe"
        // Contoh: "Menurut Kecamatan", "Menurut Lapangan Usaha", "Menurut Pendidikan"
        if (str_contains($judul, 'menurut') || str_contains($judul, 'berdasarkan')) {
            return CategoryBasedStatisticHandler::class;
        }

        // ATURAN 4: Default (Single Value / Time Series)
        // Jika tidak ada ciri di atas, biasanya ini data total (1 angka per tahun)
        // Contoh: "IPM Kab Kebumen", "Gini Rasio", "Jumlah Penduduk Total"
        return SingleValueTimeSeriesHandler::class;
    }

    public function show(BpsDataset $dataset)
    {
        // 1. Deteksi Handler (Sudah ada)
        $handlerClass = $this->detectHandler($dataset);

        // 2. Ambil parameter tahun dari URL (?year=2023)
        $year = request('year');

        // 3. Buat Handler
        $handler = app()->make($handlerClass, [
            'dataset' => $dataset,
            'year' => $year,
        ]);

        $tableData = $handler->getTableData();
        $chartData = $handler->getChartData();
        $insightData = $handler->getInsightData();

        // --- TAMBAHAN BARU: AMBIL DAFTAR TAHUN TERSEDIA ---
        // Ini agar Android bisa bikin Dropdown (2025, 2024, 2023...)
        $availableYears = $dataset->values()
            ->select('year')
            ->distinct() // Biar gak dobel2
            ->orderBy('year', 'desc') // Tahun terbaru di atas
            ->pluck('year');

        return response()->json([
            'dataset' => $dataset,
            // Kirim daftar tahun ke FE
            'available_years' => $availableYears,
            'current_year' => $year ? (int)$year : $availableYears->first(), // Tahun yang sedang tampil
            'table' => $tableData,
            'chart' => $chartData,
            'insights' => $insightData,
        ]);
    }
    
    public function history(BpsDataset $dataset)
    {
        $handlerClass = $this->detectHandler($dataset);

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
        // Panggil detektif lagi
        $handlerClass = $this->detectHandler($dataset);

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
