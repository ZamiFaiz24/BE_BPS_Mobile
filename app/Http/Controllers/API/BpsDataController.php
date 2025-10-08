<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BpsDataset;
use Illuminate\Support\Facades\DB;

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
}
