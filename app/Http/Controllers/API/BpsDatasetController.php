<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BpsDataset; // Menggunakan model Anda
use Illuminate\Support\Facades\App;

// Impor semua kelas Handler yang akan Anda gunakan
use App\Services\DatasetHandlers\PopulationByAgeGroupAndGenderHandler;
use App\Services\DatasetHandlers\PopulationByGenderAndRegionHandler;
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
}
