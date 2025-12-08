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
use App\Services\DatasetHandlers\PopulationByGenderAndRegionHandler;
use App\Services\DatasetHandlers\PopulationByAgeAndRegionHandler;
use App\Services\DatasetHandlers\SingleValueTimeSeriesHandler;
use App\Services\DatasetHandlers\GenderBasedStatisticHandler;
use App\Services\DatasetHandlers\CategoryBasedStatisticHandler;

class BpsDatasetController extends Controller
{
    /**
     * Konfigurasi mapping kategori ID ke nama yang deskriptif
     * Update di sini untuk mengubah tampilan kategori di grid
     */
    private const CATEGORY_MAPPING = [
        1 => 'Statistik Demografi dan Sosial',
        2 => 'Statistik Ekonomi dan Perdagangan',
        3 => 'Statistik Pertanian dan Lingkungan',
        // Tambahkan kategori lainnya sesuai kebutuhan
    ];

    /**
     * Grid slots mapping.
     *
     * - key: slug used by frontend
     * - title: display title
     * - subject: optional subject name from DB to match
     * - keywords: array of keywords to match against `dataset_name` (lowercased)
     *
     * Update this array to reflect the 12 grid items the Android expects.
     */
    private const GRID_SLOTS = [
        'kependudukan' => [
            'title' => 'Kependudukan',
            'subject' => 'Penduduk',
            'keywords' => ['penduduk', 'kependudukan']
        ],
        'kemiskinan' => [
            'title' => 'Kemiskinan',
            'subject' => 'Kemiskinan',
            'keywords' => ['kemiskinan', 'garis kemiskinan']
        ],
        'tenaga-kerja' => [
            'title' => 'Tenaga Kerja',
            'subject' => 'Tenaga Kerja',
            'keywords' => ['tenaga kerja', 'pekerja']
        ],
        'pengangguran' => [
            'title' => 'Pengangguran',
            'subject' => 'Tenaga Kerja',
            'keywords' => ['pengangguran', 'tingkat pengangguran']
        ],
        'ipm' => [
            'title' => 'IPM',
            'subject' => 'IPM',
            'keywords' => ['ipm', 'indeks pembangunan manusia']
        ],
        // Tambahkan slot lain sesuai kebutuhan Android (total bisa 12)
    ];

    /**
     * Konfigurasi field yang bisa di-ambil per endpoint
     * Edit di sini untuk mengatur data apa yang ingin ditampilkan
     */
    private const FIELD_CONFIG = [
        'grid' => ['title', 'slug', 'dataset_count'],
        'grid_detail' => ['id', 'dataset_code', 'dataset_name', 'last_update'],
        'index' => ['id', 'dataset_name', 'subject', 'category'],
        'show_basic' => ['id', 'dataset_code', 'dataset_name', 'subject', 'category'],
    ];

    /**
     * Helper method untuk get category name
     */
    private function getCategoryName($categoryId)
    {
        return self::CATEGORY_MAPPING[$categoryId] ?? (string)$categoryId;
    }

    /**
     * Helper method untuk extract field dari collection
     */
    private function selectFields($items, $fieldConfig)
    {
        if (!is_array($fieldConfig)) {
            $fieldConfig = self::FIELD_CONFIG[$fieldConfig] ?? [];
        }

        return $items->map(function ($item) use ($fieldConfig) {
            $result = [];
            foreach ($fieldConfig as $field) {
                if ($field === 'slug' && isset($item['title'])) {
                    // Generate slug dari title jika tidak ada
                    $result[$field] = \Illuminate\Support\Str::slug($item['title'], '-');
                } else if ($field === 'last_update' && isset($item['updated_at'])) {
                    // Format updated_at ke last_update
                    $result[$field] = $item['updated_at'] ? $item['updated_at']->format('Y-m-d') : null;
                } else if (isset($item[$field])) {
                    $result[$field] = $item[$field];
                }
            }
            return $result;
        })->toArray();
    }

    private function detectHandler(BpsDataset $dataset)
    {
        $judul = strtolower($dataset->dataset_name);

        if (str_contains($judul, 'kelompok umur') && str_contains($judul, 'jenis kelamin')) {
            return PopulationByAgeGroupAndGenderHandler::class;
        }

        if (str_contains($judul, 'kelompok umur') && str_contains($judul, 'kecamatan')) {
            return PopulationByAgeAndRegionHandler::class;
        }


        if (str_contains($judul, 'penduduk') && str_contains($judul, 'kecamatan') && str_contains($judul, 'jenis kelamin')) {
            return PopulationByGenderAndRegionHandler::class;
        }

        if (str_contains($judul, 'jenis kelamin')) {
            return GenderBasedStatisticHandler::class;
        }

        if (str_contains($judul, 'menurut') || str_contains($judul, 'berdasarkan')) {
            return CategoryBasedStatisticHandler::class;
        }

        // ATURAN 5: Default (Time Series / Garis)
        return SingleValueTimeSeriesHandler::class;
    }

    public function show(BpsDataset $dataset)
    {
        // 1. Deteksi Handler (Sudah ada)
        $handlerClass = $this->detectHandler($dataset);

        // 2. Ambil parameter dari URL (dikirim oleh Android)
        // Contoh URL: /api/datasets/20?year=2022&mode=region
        $year = request('year');
        $mode = request('mode'); // <--- TAMBAHAN PENTING

        // 3. Buat Handler dengan parameter lengkap
        // Parameter array ini akan masuk ke __construct Handler
        $handler = app()->make($handlerClass, [
            'dataset' => $dataset,
            'year'    => $year,
            'mode'    => $mode, // <--- KIRIM MODE KE HANDLER
        ]);

        $tableData   = $handler->getTableData();
        $chartData   = $handler->getChartData();
        $insightData = $handler->getInsightData();

        // 4. Ambil daftar tahun tersedia (Logic ini sudah bagus)
        $availableYears = $dataset->values()
            ->select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // 5. Kirim Response
        return response()->json([
            'dataset'         => $dataset,
            'available_years' => $availableYears,
            'current_year'    => $year ? (int)$year : ($availableYears->first() ?? null),
            'table'           => $tableData,
            'chart'           => $chartData,
            'insights'        => $insightData,
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
     * 
     * Query params:
     * - subject: Filter by subject
     * - q: Search by dataset name
     * - fields: Comma-separated field names (id, dataset_name, subject, category, dataset_code)
     *   Default: id,dataset_name,subject,category
     *   Example: /api/datasets?subject=Penduduk&fields=id,dataset_name
     */
    public function index(Request $request)
    {
        try {
            $modelClass = \App\Models\BpsDataset::class;

            if (!class_exists($modelClass)) {
                throw new Exception('Server setup error: Model not found.');
            }

            // Ambil filter dari URL query
            $subject = $request->query('subject');
            $q = $request->query('q');
            $fieldsParam = $request->query('fields');

            // Tentukan fields yang ingin di-ambil
            if ($fieldsParam) {
                $fields = array_map('trim', explode(',', $fieldsParam));
                $allowedFields = ['id', 'dataset_code', 'dataset_name', 'subject', 'category', 'updated_at'];
                $fields = array_intersect($fields, $allowedFields);
            } else {
                $fields = ['id', 'dataset_name', 'subject', 'category'];
            }

            // Pastikan 'id' selalu ada
            if (!in_array('id', $fields)) {
                array_unshift($fields, 'id');
            }

            // Mulai Query Builder
            $query = $modelClass::query();

            // Pilih kolom yang diinginkan
            $query->select($fields);

            // Terapkan filter subject (jika ada)
            if ($subject) {
                $query->where('subject', $subject);
            }

            // Pencarian judul
            if ($q) {
                $query->where('dataset_name', 'like', "%{$q}%");
            }

            // Ambil data (maks 100)
            $datasets = $query->limit(100)->get();

            // Response
            return response()->json([
                'status' => 'success',
                'count' => count($datasets),
                'data' => $datasets
            ]);
        } catch (\Exception $e) {
            Log::error('Error in BpsDatasetController@index: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage()
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

            // Sort by category name (keep as associative)
            ksort($groupedData);

            return response()->json($groupedData, 200);
        } catch (\Exception $e) {
            Log::error('Error in BpsDatasetController@getCategories: ' . $e->getMessage());

            return response()->json([
                'error' => 'Terjadi kesalahan pada server.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get grid view of categories with dataset count
     * GET /api/datasets/grid
     */
    public function getGrid(Request $request)
    {
        try {
            // Ambil seluruh dataset (kita butuh subject + nama untuk pemetaan ke grid slots)
            $datasets = BpsDataset::select('id', 'dataset_name', 'subject')->get();

            // Inisialisasi counters untuk tiap slot berdasarkan GRID_SLOTS
            $slots = [];
            foreach (self::GRID_SLOTS as $slug => $cfg) {
                $slots[$slug] = [
                    'title' => $cfg['title'],
                    'slug' => $slug,
                    'dataset_count' => 0,
                ];
            }

            // Tambahkan slot 'others' untuk dataset yang tidak cocok
            $slots['others'] = [
                'title' => 'Lainnya',
                'slug' => 'others',
                'dataset_count' => 0,
            ];

            // Iterasi dataset dan tentukan slotnya
            foreach ($datasets as $ds) {
                $placed = false;
                $name = strtolower($ds->dataset_name ?? '');
                $subject = strtolower($ds->subject ?? '');

                foreach (self::GRID_SLOTS as $slug => $cfg) {
                    // cek keywords dulu (prioritas)
                    foreach ($cfg['keywords'] as $kw) {
                        if ($kw !== '' && str_contains($name, strtolower($kw))) {
                            $slots[$slug]['dataset_count']++;
                            $placed = true;
                            break 2;
                        }
                    }

                    // cek subject match bila disediakan
                    if (isset($cfg['subject']) && $cfg['subject'] !== '' && strtolower($cfg['subject']) === $subject) {
                        $slots[$slug]['dataset_count']++;
                        $placed = true;
                        break;
                    }
                }

                if (!$placed) {
                    $slots['others']['dataset_count']++;
                }
            }

            // Susun hasil sesuai urutan GRID_SLOTS lalu others
            $gridData = [];
            foreach (array_keys(self::GRID_SLOTS) as $slug) {
                $gridData[] = $slots[$slug];
            }
            $gridData[] = $slots['others'];

            return response()->json([
                'status' => 'success',
                'data' => $gridData
            ]);
        } catch (\Exception $e) {
            Log::error('Error in BpsDatasetController@getGrid: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get datasets by category slug
     * GET /api/datasets/grid/{slug}
     * 
     * Query params:
     * - fields: Comma-separated field names (id, dataset_code, dataset_name, last_update, subject, category)
     *   Default: id,dataset_code,dataset_name,last_update
     *   Example: /api/datasets/grid/statistik-demografi-dan-sosial?fields=id,dataset_name,subject
     */
    public function getGridDetail($slug, Request $request)
    {
        try {
            // Cari slot konfigurasi berdasarkan slug
            $slotConfig = self::GRID_SLOTS[$slug] ?? null;

            if (!$slotConfig) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Grid slot tidak ditemukan'
                ], 404);
            }

            // Ambil field pilihan dari query atau gunakan default
            $fieldsParam = $request->query('fields');
            if ($fieldsParam) {
                $fields = array_map('trim', explode(',', $fieldsParam));
                $allowedFields = ['id', 'dataset_code', 'dataset_name', 'updated_at', 'subject', 'category'];
                $fields = array_intersect($fields, $allowedFields);
            } else {
                $fields = ['id', 'dataset_code', 'dataset_name', 'updated_at', 'subject'];
            }

            if (!in_array('id', $fields)) {
                array_unshift($fields, 'id');
            }

            // Build query: match by keywords OR subject
            $query = BpsDataset::query();

            // Apply subject match if provided
            if (!empty($slotConfig['subject'])) {
                $query->where('subject', $slotConfig['subject']);
            }

            // Also attempt to include datasets matching any keyword in name
            if (!empty($slotConfig['keywords'])) {
                $query->orWhere(function ($q) use ($slotConfig) {
                    foreach ($slotConfig['keywords'] as $kw) {
                        if ($kw === '') continue;
                        $q->orWhere('dataset_name', 'like', '%' . $kw . '%');
                    }
                });
            }

            $datasets = $query->select($fields)->get();

            $gridDetail = $datasets->map(function ($dataset) {
                $result = $dataset->toArray();
                if (isset($result['updated_at'])) {
                    $result['last_update'] = $result['updated_at'] ? \Carbon\Carbon::parse($result['updated_at'])->format('Y-m-d') : null;
                    unset($result['updated_at']);
                }
                return $result;
            })->toArray();

            return response()->json([
                'status' => 'success',
                'category' => $slotConfig['title'],
                'datasets' => $gridDetail
            ]);
        } catch (\Exception $e) {
            Log::error('Error in BpsDatasetController@getGridDetail: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
