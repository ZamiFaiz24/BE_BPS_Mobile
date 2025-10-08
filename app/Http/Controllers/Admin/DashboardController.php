<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BpsDataset;
use App\Models\BpsDataValue;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data statistik dari database
        $datasetCount = BpsDataset::count();
        $valueCount = BpsDataValue::count();
        $lastValue = BpsDataValue::latest('updated_at')->first();
        $lastSync = 'Belum pernah';
        if ($lastValue) {
            $lastSync = $lastValue->updated_at->translatedFormat('d M Y, H:i') . ' WIB';
        }
        $datasets = BpsDataset::orderBy('dataset_name')->get();

        // Kirim semua data ke view
        return view('admin.dashboard', [
            'datasetCount' => $datasetCount,
            'valueCount' => $valueCount,
            'lastSync' => $lastSync,
            'datasets' => $datasets,
        ]);
    }

    public function updateInsightType(Request $request, BpsDataset $dataset)
    {
        // Validasi input
        $request->validate([
            'insight_type' => 'required|string|in:default,percent_lower_is_better,percent_higher_is_better,number_lower_is_better,number_higher_is_better',
        ]);

        $dataset->insight_type = $request->input('insight_type');
        $dataset->save();

        return redirect()->back()->with('status', 'Tipe insight untuk dataset berhasil diperbarui.');
    }

    public function updateAllInsightTypes(Request $request)
    {
        $validated = $request->validate([
            // Memvalidasi bahwa input adalah array
            'insight_types' => 'required|array',
            // Memvalidasi setiap value di dalam array
            'insight_types.*' => 'required|string|in:default,percent_lower_is_better,percent_higher_is_better,number_lower_is_better,number_higher_is_better',
        ]);

        // Looping untuk menyimpan setiap perubahan
        foreach ($validated['insight_types'] as $datasetId => $insightType) {
            BpsDataset::where('id', $datasetId)->update(['insight_type' => $insightType]);
        }

        return redirect()->route('admin.dashboard')->with('status', 'Semua perubahan tipe insight berhasil disimpan.');
    }

    public function showData(BpsDataset $dataset)
    {
       
        $values = $dataset->values()->orderBy('year', 'desc')->get();

        return view('admin.datasets.show', [
            'dataset' => $dataset,
            'values' => $values,
        ]);
    }
}
