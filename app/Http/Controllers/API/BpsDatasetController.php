<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BpsDataset; // Menggunakan model Anda
use Illuminate\Support\Facades\App;

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
