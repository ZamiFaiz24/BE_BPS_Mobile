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
    /**
     * Konfigurasi Grid Menu sesuai desain UI (12 Menu).
     * Urutan di array ini menentukan urutan tampilan di HP.
     */
    private const GRID_SLOTS = [
        // Baris 1 - Mapping disesuaikan dengan subjects yang SEBENARNYA ada di database
        'kependudukan' => [
            'title'    => 'Penduduk',
            'display_name' => 'Penduduk', // Short title untuk slider/display
            'subject'  => 'Kependudukan dan migrasi', // Subject sesuai DB
            'keywords' => [] // Cukup subject match
        ],
        'tenaga-kerja' => [
            'title'    => 'Tenaga Kerja',
            'display_name' => 'TPAK', // Short title untuk slider/display
            'subject'  => 'Tenaga Kerja', // Subject sesuai DB
            'keywords' => [] // Cukup subject match
        ],
        'pengangguran' => [
            'title'    => 'Pengangguran',
            'display_name' => 'TPT', // Short title untuk slider/display
            'subject'  => null, // Tidak ada subject terpisah
            'keywords' => ['pengangguran', 'tpak', 'tpt', 'tidak bekerja']
        ],

        // Baris 2
        'kemiskinan' => [
            'title'    => 'Kemiskinan',
            'display_name' => 'Kemiskinan', // Short title untuk slider/display
            'subject'  => null, // Tidak ada di DB
            'keywords' => ['kemiskinan', 'kedalaman kemiskinan', 'keparahan kemiskinan', 'headcount', 'poverty'] // Keywords untuk cari dataset kemiskinan
        ],
        'rasio-gini' => [
            'title'    => 'Rasio GINI',
            'display_name' => 'GINI', // Short title untuk slider/display
            'subject'  => null,
            'keywords' => ['gini', 'ketimpangan', 'rasio gini', 'inequality'] // Keywords spesifik untuk GINI
        ],
        'ipm' => [
            'title'    => 'IPM',
            'display_name' => 'IPM', // Short title untuk slider/display
            'subject'  => null, // Tidak ada di DB
            'keywords' => ['ipm', 'indeks pembangunan manusia', 'hdi', 'human development'] // Keywords untuk cari dataset IPM
        ],

        // Baris 3
        'inflasi' => [
            'title'    => 'Inflasi',
            'display_name' => 'Inflasi', // Short title untuk slider/display
            'subject'  => null, // Tidak ada di DB
            'keywords' => ['inflasi', 'inflasi umum', 'laju inflasi', 'inflation'] // Keywords untuk cari dataset Inflasi
        ],
        'ekonomi' => [
            'title'    => 'Ekonomi',
            'display_name' => '', // Kosongkan untuk sekarang
            'subject'  => null, // Kosongkan untuk sekarang
            'keywords' => [] // Tidak ada keywords - skip grid ini
        ],
        'pdrb' => [
            'title'    => 'PDRB',
            'display_name' => 'PDRB', // Short title untuk slider/display
            'subject'  => null, // Tidak ada subject terpisah
            'keywords' => ['pdrb', 'neraca ekonomi', 'produk domestik bruto', 'gdp'] // Match ke "Neraca Ekonomi"
        ],

        // Baris 4
        'pendidikan' => [
            'title'    => 'Pendidikan',
            'display_name' => 'Pendidikan', // Short title untuk slider/display
            'subject'  => 'Pendidikan', // Subject sesuai DB
            'keywords' => [] // Cukup subject match
        ],
        'perumahan' => [
            'title'    => 'Perumahan',
            'display_name' => 'Perumahan', // Short title untuk slider/display
            'subject'  => 'Perumahan', // Subject sesuai DB
            'keywords' => [] // Cukup subject match
        ],
        'pertanian' => [
            'title'    => 'Pertanian',
            'display_name' => 'Pertanian', // Short title untuk slider/display
            'subject'  => 'Pertanian', // Subject sesuai DB
            'keywords' => [] // Cukup subject match
        ]
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
     * Helper method untuk expose GRID_SLOTS (digunakan oleh controller lain)
     */
    public function getGridSlots()
    {
        return self::GRID_SLOTS;
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

    /**
     * Get detailed dataset information with table, chart, and insight data
     *
     * This endpoint returns comprehensive data for a specific BPS dataset including:
     * - Table data with rows and columns formatted for display
     * - Chart data (formatted per handler type - bar, line, etc.)
     * - Insight/summary information
     * - List of available years for the dataset
     *
     * The data format depends on the dataset type detected from its name:
     * - Population by Age & Gender
     * - Population by Gender & Region
     * - Population by Age & Region
     * - Gender-based statistics
     * - Category-based statistics
     * - Time series (default)
     *
     * @urlParam dataset integer required The dataset ID. Example: 20
     * @queryParam year integer optional The year to retrieve data for. If not provided, uses latest available year. Example: 2022
     * @queryParam mode string optional Display mode (used by some handlers). Example: region
     *
     * @response 200 {
     *   "dataset": {
     *     "id": 20,
     *     "dataset_code": "SP010101",
     *     "dataset_name": "Penduduk menurut kelompok umur dan jenis kelamin",
     *     "subject": "Penduduk",
     *     "category": "Statistik Demografi",
     *     "unit": "Jiwa"
     *   },
     *   "available_years": [2023, 2022, 2021, 2020],
     *   "current_year": 2023,
     *   "table": {
     *     "headers": ["Kelompok Umur", "Laki-Laki", "Perempuan", "Total"],
     *     "rows": [
     *       ["0-4 tahun", "5000000", "4800000", "9800000"]
     *     ]
     *   },
     *   "chart": {
     *     "type": "bar",
     *     "title": "Penduduk menurut Kelompok Umur",
     *     "data": {}
     *   },
     *   "insights": ["Populasi total di tahun 2023 adalah 275 juta jiwa"]
     * }
     *
     * @response 404 {"error": "Dataset not found"}
     */
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

    /**
     * Get historical data for a specific dataset
     *
     * Retrieves historical trends and time-series data for a dataset.
     * The data structure depends on the handler's implementation of getHistoryData().
     *
     * @urlParam dataset integer required The dataset ID. Example: 20
     * @queryParam year integer optional Filter history by starting year. Example: 2020
     *
     * @response 200 {
     *   "dataset": {
     *     "id": 20,
     *     "dataset_code": "SP010101",
     *     "dataset_name": "Penduduk menurut kelompok umur dan jenis kelamin"
     *   },
     *   "history": [
     *     {"year": 2023, "value": 275000000},
     *     {"year": 2022, "value": 273000000}
     *   ]
     * }
     *
     * @response 404 {"error": "Dataset not found"}
     */
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

    /**
     * Get insight and summary information for a specific dataset
     *
     * Provides key findings, statistics, and analysis for a dataset.
     * Insights may include percentage calculations, comparative data, and significant findings.
     *
     * @urlParam dataset integer required The dataset ID. Example: 20
     * @queryParam year integer optional The year to generate insights for. Example: 2023
     *
     * @response 200 {
     *   "dataset": {
     *     "id": 20,
     *     "dataset_code": "SP010101",
     *     "dataset_name": "Penduduk menurut kelompok umur dan jenis kelamin"
     *   },
     *   "insights": [
     *     "Populasi total tahun 2023 adalah 275 juta jiwa",
     *     "Pertumbuhan populasi 0.7% dibanding tahun lalu",
     *     "Rasio gender 50:50 menunjukkan keseimbangan sempurna"
     *   ]
     * }
     *
     * @response 404 {"error": "Dataset not found"}
     */
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
     * Get list of datasets filtered by subject or search query
     *
     * Returns a paginated list of datasets with optional filtering by subject
     * or full-text search on dataset name. This endpoint is typically used for
     * the dataset list view in Layer 3 of the UI navigation hierarchy.
     *
     * @queryParam subject string optional Filter by subject name. Example: Penduduk
     * @queryParam q string optional Search by dataset name (partial match). Example: kelompok umur
     * @queryParam fields string optional Comma-separated field names to return. Allowed: id, dataset_code, dataset_name, subject, category, updated_at. Default: id,dataset_name,subject,category. Example: id,dataset_name
     *
     * @response 200 {
     *   "status": "success",
     *   "count": 15,
     *   "data": [
     *     {
     *       "id": 20,
     *       "dataset_name": "Penduduk menurut kelompok umur dan jenis kelamin",
     *       "subject": "Penduduk",
     *       "category": "Statistik Demografi"
     *     },
     *     {
     *       "id": 21,
     *       "dataset_name": "Penduduk menurut kabupaten/kota dan jenis kelamin",
     *       "subject": "Penduduk",
     *       "category": "Statistik Demografi"
     *     }
     *   ]
     * }
     *
     * @response 500 {"status": "error", "message": "Terjadi kesalahan pada server."}
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
     * Get all categories with their subjects for navigation
     *
     * Returns a hierarchical structure of all available BPS data categories
     * and their corresponding subjects. This is used in Layer 2 of the UI
     * to display category and subject selection options.
     *
     * The response is organized as an object where keys are category names
     * and values contain the list of subjects within that category.
     *
     * @response 200 {
     *   "Statistik Demografi": {
     *     "category": "Statistik Demografi",
     *     "subjects": ["Penduduk", "Migrasi", "Kelahiran"]
     *   },
     *   "Statistik Ekonomi": {
     *     "category": "Statistik Ekonomi",
     *     "subjects": ["PDRB", "Inflasi", "Perdagangan"]
     *   }
     * }
     *
     * @response 500 {"error": "Terjadi kesalahan pada server.", "message": "..."}
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
     * Get grid menu of statistics categories with dataset counts
     *
     * Returns a grid layout (typically 12 items for mobile UI) representing
     * major statistics categories. Each grid item includes:
     * - Title: Display name of the category
     * - Slug: URL-friendly identifier
     * - Dataset count: Number of datasets in that category
     *
     * The grid includes categories like Penduduk, Tenaga Kerja, Pengangguran,
     * Kemiskinan, IPM, Inflasi, Ekonomi, PDRB, Pendidikan, Perumahan, Pertanian,
     * and Lainnya (Others).
     *
     * @response 200 {
     *   "status": "success",
     *   "data": [
     *     {
     *       "title": "Penduduk",
     *       "slug": "kependudukan",
     *       "dataset_count": 12
     *     },
     *     {
     *       "title": "Tenaga Kerja",
     *       "slug": "tenaga-kerja",
     *       "dataset_count": 8
     *     }
     *   ]
     * }
     *
     * @response 500 {"status": "error"}
     */
    public function getGrid(Request $request)
    {
        try {
            // Ambil data ringan saja
            $datasets = BpsDataset::select('id', 'dataset_name', 'subject')->get();

            // Siapkan wadah counts
            $slots = [];
            foreach (self::GRID_SLOTS as $slug => $cfg) {
                $slots[$slug] = [
                    'title' => $cfg['title'],
                    'slug' => $slug,
                    'dataset_count' => 0,
                ];
            }

            // Wadah untuk 'Lainnya'
            $slots['others'] = [
                'title' => 'Lainnya',
                'slug' => 'others',
                'dataset_count' => 0,
            ];

            // --- LOGIC MATCHING YANG BARU (Prioritas: Subject > Keywords) ---
            foreach ($datasets as $ds) {
                $placed = false;

                $name = strtolower($ds->dataset_name ?? '');
                $subject = strtolower($ds->subject ?? '');

                foreach (self::GRID_SLOTS as $slug => $cfg) {
                    $isMatch = false;

                    // 1. Prioritas UTAMA: Cek Subject (Exact Match)
                    // Jika subject !== null, gunakan subject matching saja
                    if ($cfg['subject'] !== null) {
                        if ($subject === strtolower($cfg['subject'])) {
                            $isMatch = true;
                        }
                    }
                    // 2. Fallback: Jika subject === null dan ada keywords, gunakan keywords
                    else if (!empty($cfg['keywords'])) {
                        foreach ($cfg['keywords'] as $kw) {
                            if ($kw !== '' && str_contains($name, strtolower($kw))) {
                                $isMatch = true;
                                break;
                            }
                        }
                    }

                    // Jika cocok, tambahkan ke slot ini
                    if ($isMatch) {
                        $slots[$slug]['dataset_count']++;
                        $placed = true;
                        break; // PENTING: Berhenti setelah menemukan slot pertama yang cocok
                        // Ini mencegah duplikasi dataset di beberapa slot
                    }
                }

                // Jika tidak cocok dengan kategori apapun, masuk ke 'others'
                if (!$placed) {
                    $slots['others']['dataset_count']++;
                }
            }

            // Rapikan array hasil (array_values)
            return response()->json([
                'status' => 'success',
                'data' => array_values($slots)
            ]);
        } catch (\Exception $e) {
            Log::error('Grid Error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Get detailed list of datasets for a specific grid category
     *
     * Returns all datasets belonging to a specific category (identified by slug).
     * Datasets are matched using keywords and subject names configured in GRID_SLOTS.
     *
     * Available slugs: kependudukan, tenaga-kerja, pengangguran, kemiskinan, rasio-gini,
     * ipm, inflasi, ekonomi, pdrb, pendidikan, perumahan, pertanian, others
     *
     * @urlParam slug string required The category slug. Example: kependudukan
     * @queryParam fields string optional Comma-separated field names to return. Allowed: id, dataset_code, dataset_name, updated_at, subject, category. Default: id,dataset_code,dataset_name,updated_at. Example: id,dataset_name,subject
     *
     * @response 200 {
     *   "status": "success",
     *   "category": "Penduduk",
     *   "datasets": [
     *     {
     *       "id": 20,
     *       "dataset_code": "SP010101",
     *       "dataset_name": "Penduduk menurut kelompok umur dan jenis kelamin",
     *       "last_update": "2023-12-01",
     *       "subject": "Penduduk"
     *     }
     *   ]
     * }
     *
     * @response 404 {"status": "error", "message": "Grid slot tidak ditemukan"}
     * @response 500 {"status": "error", "message": "Terjadi kesalahan pada server."}
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
                $allowedFields = ['id', 'dataset_code', 'dataset_name', 'last_update', 'subject', 'category'];
                $fields = array_intersect($fields, $allowedFields);
            } else {
                $fields = ['id', 'dataset_code', 'dataset_name', 'last_update', 'subject'];
            }

            if (!in_array('id', $fields)) {
                array_unshift($fields, 'id');
            }

            // Build query: match by subject ATAU keyword, tapi preferensikan subject
            $query = BpsDataset::query();
            $hasMatchingCriteria = false;

            // 1. Jika subject !== null, gunakan subject match
            if ($slotConfig['subject'] !== null) {
                $query->where('subject', $slotConfig['subject']);
                $hasMatchingCriteria = true;
            }
            // 2. Jika subject === null dan ada keywords, gunakan keywords
            elseif (!empty($slotConfig['keywords'])) {
                $query->where(function ($q) use ($slotConfig) {
                    foreach ($slotConfig['keywords'] as $kw) {
                        if ($kw === '') continue;
                        $q->orWhere('dataset_name', 'like', '%' . $kw . '%');
                    }
                });
                $hasMatchingCriteria = true;
            }

            // Jika tidak ada kriteria matching (subject=null dan keywords=[]), return empty
            if (!$hasMatchingCriteria) {
                return response()->json([
                    'status' => 'success',
                    'category' => $slotConfig['title'],
                    'datasets' => [],
                    'message' => 'Slot ini tidak memiliki kriteria matching'
                ]);
            }

            $datasets = $query->select($fields)->get();

            $gridDetail = $datasets->map(function ($dataset) {
                $result = $dataset->toArray();
                // Format last_update dari BPS database (bukan updated_at)
                if (isset($result['last_update'])) {
                    $result['last_update'] = $result['last_update'] ? \Carbon\Carbon::parse($result['last_update'])->format('Y-m-d') : null;
                }
                // Hapus Laravel tracking fields jika ada
                unset($result['updated_at']);
                unset($result['created_at']);
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
