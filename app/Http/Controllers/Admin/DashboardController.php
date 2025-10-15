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
        // --- 1. LOGIKA QUERY UTAMA ---
        $query = \App\Models\BpsDataset::query();

        // Filter berdasarkan SATU kategori (dari radio button)
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter berdasarkan BANYAK subject (dari checkbox)
        if ($request->filled('subject')) {
            // whereIn siap menerima array dari checkbox 'subject[]'
            $query->whereIn('subject', (array) $request->subject);
        }

        if ($request->filled('q')) {
            $query->where('dataset_name', 'like', '%' . $request->q . '%');
        }

        $datasets = $query->orderBy('dataset_name')->paginate(10)->withQueryString();

        $datasetCount = \App\Models\BpsDataset::count();
        $valueCount = \App\Models\BpsDataValue::count();
        $lastValue = \App\Models\BpsDataValue::latest('updated_at')->first();
        $lastSync = $lastValue ? $lastValue->updated_at->translatedFormat('d M Y, H:i') . ' WIB' : 'Belum pernah';

        // --- 3. DATA UNTUK JAVASCRIPT DI MODAL (Struktur Paling Penting) ---
        $categories = \App\Models\BpsDataset::select('category', 'subject')
            ->whereNotNull('category')->get()
            ->groupBy('category')
            ->map(function ($group, $categoryKey) {
                return [
                    'id' => $categoryKey,
                    'name' => \App\Models\BpsDataset::CATEGORIES[$categoryKey] ?? 'Kategori ' . $categoryKey,
                    'subjects' => $group->pluck('subject')->unique()->values()
                ];
            })
            ->sortBy('id')->values();

        // --- 4. KIRIM SEMUA DATA KE VIEW ---
        return view('admin.dashboard', [
            'datasetCount' => $datasetCount,
            'valueCount' => $valueCount,
            'lastSync' => $lastSync,
            'datasets' => $datasets,
            'categories' => $categories,
        ]);
    }

    /**
     * Method 2: Menangani request filter via JavaScript (AJAX).
     * Tugasnya hanya mengembalikan potongan HTML dari tabel & paginasi.
     */
    public function ajaxFilter(Request $request)
    {
        $datasets = $this->getFilteredDatasets($request);
        return view('admin.datasets.partials.table-and-pagination', compact('datasets'));
    }

    /**
     * Method 3: Helper private untuk query dataset.
     * Menghindari duplikasi kode antara index() dan ajaxFilter().
     */
    private function getFilteredDatasets(Request $request)
    {
        $query = BpsDataset::query();

        if ($request->filled('category')) {
            $query->whereIn('category', (array) $request->category);
        }
        if ($request->filled('subject')) {
            $query->whereIn('subject', (array) $request->subject);
        }
        if ($request->filled('q')) {
            $query->where('dataset_name', 'like', '%' . $request->q . '%');
        }

        return $query->orderBy('dataset_name')->paginate(10)->withQueryString();
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
                $target['name'],
                $target['category']
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
