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

    public function index(Request $request)
    {
        try {
            // 1. Tentukan nama Model Anda.
            // (Ganti jika nama Model Anda bukan \App\Models\BpsDataset)
            $modelClass = \App\Models\BpsDataset::class;

            if (!class_exists($modelClass)) {
                // Jika model tidak ada, kirim error
                throw new Exception('Server setup error: Model not found.');
            }

            // 2. Ambil filter dari URL query (ganti subject -> category)
            $category = $request->query('category');
            $q = $request->query('q'); // 'q' untuk search

            // 3. Mulai Query Builder
            $query = $modelClass::query();

            // 4. Pilih kolom ringan (ganti subject -> category)
            $query->select([
                'id',
                'dataset_name',
                'category',
            ]);

            // 5. Terapkan filter 'category' (jika ada)
            if ($category) {
                $query->where('category', $category);
            }

            // 6. Pencarian judul/kode
            if ($q) {
                $query->where('dataset_name', 'like', "%{$q}%");
            }

            // 7. Ambil data (maks 100)
            $datasets = $query->limit(100)->get();

            // 8. Response ringan
            return response()->json($datasets);
        } catch (\Exception $e) {
            // 9. JIKA TERJADI ERROR DI ATAS (misal, kolom tidak ada)
            // Catat error di log server
            Log::error('Error in BpsDatasetController@index: ' . $e->getMessage());

            // Kembalikan pesan error sebagai JSON (agar bisa dibaca di Android/Postman)
            return response()->json([
                'error_A' => 'Terjadi kesalahan pada server.',
                'error_B_message' => $e->getMessage(),
                'error_C_file' => $e->getFile(),
                'error_D_line' => $e->getLine()
            ], 500); // Kembalikan status 500
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
}
