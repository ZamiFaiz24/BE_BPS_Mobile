<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BpsDataset;
use App\Models\BpsDatavalue;
use App\Models\SyncLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = BpsDataset::query();

        // Filter kategori
        if ($request->filled('category')) {
            $query->whereIn('category_id', (array) $request->category);
        }

        // Filter subjek
        if ($request->filled('subject')) {
            $query->whereIn('subject', (array) $request->subject);
        }

        // Pencarian
        if ($request->filled('q')) {
            $query->where('dataset_name', 'like', '%' . $request->q . '%');
        }

        // Sorting
        $sortField = $request->get('sort', 'last_update');
        $sortOrder = $request->get('order', 'desc');

        $allowedSorts = ['dataset_name', 'subject', 'last_update', 'source', 'created_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortOrder);
        } else {
            $query->orderBy('last_update', 'desc');
        }

        $perPage = $request->input('per_page', 10);

        $datasets = $query->paginate($perPage)->withQueryString();

        $datasetCount = \App\Models\BpsDataset::count();
        $valueCount = \App\Models\BpsDatavalue::count();

        // -----------------------------------------------------------
        // PERBAIKAN: Ambil $lastSync dari tabel log baru kita
        // -----------------------------------------------------------
        $latestLog = SyncLog::where('status', 'sukses')
            ->latest('finished_at')
            ->first();

        $lastSync = $latestLog ? $latestLog->finished_at->translatedFormat('d M Y, H:i') . ' WIB' : 'Belum pernah';
        // -----------------------------------------------------------

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

        return view('admin.dashboard', [
            'datasetCount' => $datasetCount,
            'valueCount' => $valueCount,
            'lastSync' => $lastSync, // Variabel ini sekarang jauh lebih akurat
            'datasets' => $datasets,
            'categories' => $categories,
        ]);
    }

    public function ajaxFilter(Request $request)
    {
        $datasets = $this->getFilteredDatasets($request);
        return view('admin.datasets.partials.table-and-pagination', compact('datasets'));
    }

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
            'insight_types' => 'required|array',
            'insight_types.*' => 'required|string|in:default,percent_lower_is_better,percent_higher_is_better,number_lower_is_better,number_higher_is_better',
        ]);

        foreach ($validated['insight_types'] as $datasetId => $insightType) {
            BpsDataset::where('id', $datasetId)->update(['insight_type' => $insightType]);
        }

        return redirect()->route('admin.dashboard')->with('status', 'Semua perubahan tipe insight berhasil disimpan.');
    }

    /**
     * PERBAIKAN: Method ini sekarang menjadi "tipis" dan memanggil
     * "otak" yang sama dengan SyncController (yaitu 'bps:fetch-data').
     */
    public function syncAllDatasets()
    {
        // 1. Kunci agar tidak di-klik ganda
        $lock = Cache::lock('sync:manual', 10);
        if (!$lock->get()) {
            return redirect()->back()->withErrors(['status' => 'Sinkronisasi baru saja dipicu. Coba lagi nanti.']);
        }

        try {
            // 2. Dapatkan ID user yang sedang login
            $userId = Auth::id();
            Log::info("Sync All triggered by User ID: $userId");

            // 3. Masukkan "Otak" kita (Command) ke dalam Antrean (Queue)
            Artisan::queue('bps:fetch-data', [
                '--user_id' => $userId
            ])->onQueue('sync-jobs');

            // 4. Kembalikan respons sukses
            return redirect()->back()->with('status', 'Sinkronisasi semua dataset telah dimasukkan ke antrean.');
        } catch (\Exception $e) {
            Log::error('Gagal memicu Sync All: ' . $e->getMessage());
            return redirect()->back()->withErrors(['sync_error' => 'Gagal memicu sinkronisasi: ' . $e->getMessage()]);
        }
    }

    // --- Sisa method Anda (show, destroy, edit, update) sudah benar ---

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

        return view('admin.datasets.partials.table', compact('datasets'))->render();
    }
}
