<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BpsDataset;
use App\Models\BpsDataValue;
use App\Jobs\SyncBpsDataJob;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\BpsDataset::query();

        // Filter subject
        if ($request->filled('subject')) {
            $query->where('subject', $request->subject);
        }

        // Search nama dataset
        if ($request->filled('q')) {
            $query->where('dataset_name', 'like', '%' . $request->q . '%');
        }

        $perPage = $request->get('per_page', 10); // default 10, bisa 25/50
        $datasets = $query->orderBy('dataset_name')->paginate($perPage)->withQueryString();

        // Ambil data statistik dari database
        $datasetCount = BpsDataset::count();
        $valueCount = BpsDataValue::count();
        $lastValue = BpsDataValue::latest('updated_at')->first();
        $lastSync = 'Belum pernah';
        if ($lastValue) {
            $lastSync = $lastValue->updated_at->translatedFormat('d M Y, H:i') . ' WIB';
        }

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

    public function syncAllDatasets()
    {
        // 1. Baca daftar target dari file konfigurasi
        $targets = config('bps_targets.datasets');

        if (empty($targets)) {
            return redirect()->back()->with('error', 'Tidak ada target dataset untuk disinkronkan.');
        }

        // 2. Looping untuk setiap target dan KIRIM TUGAS KE ANTRIAN
        foreach ($targets as $target) {
            SyncBpsDataJob::dispatch(
                $target['params']['domain'],
                $target['variable_id'],
                $target['tahun_mulai'],
                $target['tahun_akhir'],
                $target['name']
            );
        }

        // 3. Langsung berikan respon ke user, jangan menunggu proses selesai
        return redirect()->route('admin.dashboard')->with('status', 'Semua dataset telah dimasukkan ke dalam antrian untuk disinkronkan.');
    }

    public function show(BpsDataset $dataset)
    {

        $values = $dataset->values()->orderBy('year', 'desc')->get();

        return view('admin.datasets.show', [
            'dataset' => $dataset,
            'values' => $values,
        ]);
    }
    public function destroy(BpsDataset $dataset)
    {
        $dataset->delete();

        return redirect()->route('admin.dashboard')->with('status', 'Dataset berhasil dihapus.');
    }
    public function edit(BpsDataset $dataset)
    {
        return view('admin.datasets.edit', compact('dataset'));
    }

    public function update(Request $request, BpsDataset $dataset)
    {
        $request->validate([
            'insight_type' => 'required|string',
            // tambahkan validasi lain jika perlu
        ]);
        $dataset->update([
            'insight_type' => $request->insight_type,
        ]);
        return redirect()->route('admin.dashboard')->with('status', 'Tipe insight berhasil diubah.');
    }
    public function ajaxSearch(Request $request)
    {
        $query = BpsDataset::query();

        if ($request->filled('subject')) {
            $query->where('subject', $request->subject);
        }
        if ($request->filled('q')) {
            $query->where('dataset_name', 'like', '%' . $request->q . '%');
        }
        $perPage = $request->get('per_page', 10);
        $datasets = $query->orderBy('dataset_name')->paginate($perPage);

        // Return partial blade (hanya tbody dan pagination)
        return view('admin.datasets.partials.table', compact('datasets'))->render();
    }
}
