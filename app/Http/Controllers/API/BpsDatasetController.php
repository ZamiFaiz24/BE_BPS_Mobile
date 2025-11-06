<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BpsDataset; // Menggunakan model Anda
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

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
     * Lightweight list of datasets for mobile (GET /api/datasets)
     * Query params:
     * - subject (string) : filter by subject/category (partial match)
     * - q (string)       : search in title or code
     * - sort (string)    : publish_date_desc (default) | publish_date_asc
     * - limit (int)      : max items (default 100)
     */
    public function index(Request $request)
    {
        $subject = $request->query('subject');
        $q = $request->query('q');
        $sort = $request->query('sort', 'publish_date_desc');
        $limit = (int) $request->query('limit', 100);
        $limit = $limit > 0 && $limit <= 500 ? $limit : 100; // safety cap

        // Try Eloquent model if available (BpsDataset or Dataset), otherwise fallback to DB table 'datasets'
        $modelQuery = null;
        if (class_exists(\App\Models\BpsDataset::class)) {
            $modelQuery = \App\Models\BpsDataset::query();
        } elseif (class_exists(\App\Models\BpsDataset::class)) {
            $modelQuery = \App\Models\BpsDataset::query();
        }

        if ($modelQuery) {
            // Select only light columns
            $modelQuery->select([
                'id',
                'code',
                'title',
                'subject',       // try commonly used column names
                'category as subject', // in case DB uses 'category' (will be ignored if not present)
                'description',
                'publish_date',
                'release_date',
                'url',
            ]);
            // Apply filters (use whereRaw/coalesce approach safe for missing columns)
            if ($subject) {
                $modelQuery->where(function ($qWhere) use ($subject) {
                    $qWhere->whereRaw("COALESCE(subject, '') LIKE ?", ["%{$subject}%"])
                        ->orWhereRaw("COALESCE(category, '') LIKE ?", ["%{$subject}%"]);
                });
            }
            if ($q) {
                $modelQuery->where(function ($s) use ($q) {
                    $s->where('title', 'like', "%{$q}%")
                        ->orWhere('code', 'like', "%{$q}%");
                });
            }
            // Sorting fallback fields
            if ($sort === 'publish_date_asc') {
                $modelQuery->orderByRaw("COALESCE(publish_date, release_date, '1970-01-01') ASC");
            } else {
                $modelQuery->orderByRaw("COALESCE(publish_date, release_date, '1970-01-01') DESC");
            }

            $rows = $modelQuery->limit($limit)->get()->map(function ($r) {
                return [
                    'id' => $r->id ?? null,
                    'code' => $r->code ?? null,
                    'title' => $r->title ?? null,
                    'subject' => $r->subject ?? ($r->category ?? null),
                    'description' => $r->description ?? null,
                    'last_updated' => $r->publish_date ?? ($r->release_date ?? null),
                    'url' => $r->url ?? null,
                ];
            })->values();
        } else {
            // Fallback DB table - assume table 'datasets' exists with common columns
            $query = DB::table('datasets')->select([
                'id',
                DB::raw("COALESCE(code, '') as code"),
                DB::raw("COALESCE(title, '') as title"),
                DB::raw("COALESCE(subject, COALESCE(category, '')) as subject"),
                DB::raw("COALESCE(description, '') as description"),
                DB::raw("COALESCE(publish_date, release_date) as last_updated"),
                DB::raw("COALESCE(url, '') as url"),
            ]);

            if ($subject) {
                $query->where(function ($qb) use ($subject) {
                    $qb->where('subject', 'like', "%{$subject}%")
                        ->orWhere('category', 'like', "%{$subject}%");
                });
            }
            if ($q) {
                $query->where(function ($qb) use ($q) {
                    $qb->where('title', 'like', "%{$q}%")
                        ->orWhere('code', 'like', "%{$q}%");
                });
            }

            if ($sort === 'publish_date_asc') {
                $query->orderByRaw("COALESCE(publish_date, release_date, '1970-01-01') ASC");
            } else {
                $query->orderByRaw("COALESCE(publish_date, release_date, '1970-01-01') DESC");
            }

            $rows = $query->limit($limit)->get()->map(function ($r) {
                return [
                    'id' => $r->id,
                    'code' => $r->code ?: null,
                    'title' => $r->title ?: null,
                    'subject' => $r->subject ?: null,
                    'description' => $r->description ?: null,
                    'last_updated' => $r->last_updated ?: null,
                    'url' => $r->url ?: null,
                ];
            })->values();
        }

        return response()->json($rows, 200);
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
}
