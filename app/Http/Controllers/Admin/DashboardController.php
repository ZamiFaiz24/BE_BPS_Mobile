<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BpsDataset;
use App\Models\BpsDatavalue;
use App\Models\SyncLog;
use App\Models\DatasetOverride;
use App\Services\DatasetConfigService;
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
            $query->whereIn('category', (array) $request->category);
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
        $perPage = $request->input('per_page', 10);

        return $query->orderBy('dataset_name')
            ->paginate($perPage)
            ->withQueryString();
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
        // Preserve filter & pagination context if provided
        $preserve = request()->only(['category', 'subject', 'q', 'sort', 'order', 'page', 'per_page']);
        return redirect()->route('admin.dashboard', $preserve)->with('status', 'Dataset berhasil dihapus.');
    }

    public function edit(BpsDataset $dataset)
    {
        // Pass along current filter context implicitly via query params
        // Ambil daftar subject untuk dropdown (opsional)
        $subjects = BpsDataset::select('subject')
            ->distinct()
            ->whereNotNull('subject')
            ->orderBy('subject')
            ->get();

        return view('admin.datasets.edit', compact('dataset', 'subjects'));
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
        // Preserve filter & pagination context
        $preserve = $request->only(['category', 'subject', 'q', 'sort', 'order', 'page', 'per_page']);
        return redirect()->route('admin.dashboard', $preserve)->with('status', 'Tipe insight berhasil diubah.');
    }

    /**
     * Update dataset config (tahun_mulai, tahun_akhir, dll)
     * Route: POST /admin/datasets/{datasetId}/update-config
     */
    public function updateConfig(Request $request, $datasetId)
    {
        try {
            $request->validate([
                'tahun_mulai' => 'required|integer|min:1900|max:2100',
                'tahun_akhir' => 'required|integer|min:1900|max:2100|gte:tahun_mulai',
            ]);

            $configService = new DatasetConfigService();
            $configService->updateDatasetConfig($datasetId, [
                'tahun_mulai' => $request->tahun_mulai,
                'tahun_akhir' => $request->tahun_akhir,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Konfigurasi dataset berhasil diperbarui.'
                ]);
            }

            return redirect()->back()->with('status', 'Konfigurasi dataset berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error("Error updating config for {$datasetId}: " . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui konfigurasi: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->withErrors(['error' => 'Gagal memperbarui konfigurasi: ' . $e->getMessage()]);
        }
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

    /**
     * Sync single dataset manually
     * Route: POST /admin/datasets/{datasetId}/sync
     */
    public function syncSingleDataset(Request $request, $datasetId)
    {
        try {
            // 1. Kunci untuk prevent double click
            $lock = Cache::lock("sync:dataset:{$datasetId}", 10);
            if (!$lock->get()) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Dataset sedang disinkronisasi. Coba lagi nanti.'], 429);
                }
                return redirect()->back()->withErrors(['sync_error' => 'Dataset sedang disinkronisasi. Coba lagi nanti.']);
            }

            // 2. Validasi dataset exists di config
            $configService = new DatasetConfigService();
            $dataset = $configService->getDataset($datasetId);

            // Fallback: jika dataset tidak ada di config (dataset baru), cari di DB
            if (!$dataset) {
                $dbDataset = BpsDataset::where('dataset_code', $datasetId)->first();
                if ($dbDataset) {
                    $dataset = [
                        'id' => $dbDataset->dataset_code,
                        'name' => $dbDataset->dataset_name,
                        'enabled' => false,
                    ];
                } else {
                    if ($request->expectsJson()) {
                        return response()->json(['success' => false, 'message' => 'Dataset tidak ditemukan.'], 404);
                    }
                    return redirect()->back()->withErrors(['sync_error' => 'Dataset tidak ditemukan.']);
                }
            }

            // 3. Cek apakah dataset enabled
            if (!$dataset['enabled']) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Dataset tidak aktif (disabled).'], 422);
                }
                return redirect()->back()->withErrors(['sync_error' => 'Dataset tidak aktif (disabled). Silakan aktifkan terlebih dahulu.']);
            }

            // 4. Buat temporary override untuk hanya enable dataset ini
            // Sementara disable semua dataset lain dengan cara set flag khusus
            $userId = Auth::id();

            // Buat sync log
            $syncLog = SyncLog::create([
                'user_id' => $userId,
                'status' => 'berjalan',
                'started_at' => now(),
                'summary_message' => "Sinkronisasi dataset '{$dataset['name']}' dimulai..."
            ]);

            Log::info("Sync Single Dataset '{$datasetId}' triggered by User ID: {$userId}, Log ID: {$syncLog->id}");

            // Queue sync dengan parameter log_id dan dataset_id
            Artisan::queue('bps:fetch-data', [
                '--log_id' => $syncLog->id,
                '--dataset_id' => $datasetId
            ])->onQueue('sync-jobs');

            $message = "Sinkronisasi dataset '{$dataset['name']}' telah dimasukkan ke antrean.";

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => $message]);
            }

            return redirect()->back()->with('status', $message);
        } catch (\Exception $e) {
            Log::error("Error syncing dataset {$datasetId}: " . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal memicu sinkronisasi: ' . $e->getMessage()], 500);
            }

            return redirect()->back()->withErrors(['sync_error' => 'Gagal memicu sinkronisasi: ' . $e->getMessage()]);
        }
    }

    /**
     * Toggle dataset enable/disable
     * Route: POST /admin/datasets/{datasetId}/toggle
     */
    public function toggleDataset(Request $request, $datasetId)
    {
        try {
            $request->validate([
                'enabled' => 'required|boolean',
            ]);

            // Validasi dataset exists
            $configService = new DatasetConfigService();
            $dataset = $configService->getDataset($datasetId);

            // Fallback: jika tidak ada di config (dataset baru), coba ambil dari DB
            if (!$dataset) {
                $dbDataset = BpsDataset::where('dataset_code', $datasetId)->first();
                if ($dbDataset) {
                    $dataset = [
                        'id' => $dbDataset->dataset_code,
                        'name' => $dbDataset->dataset_name,
                        'enabled' => false,
                    ];
                } else {
                    if ($request->expectsJson()) {
                        return response()->json(['success' => false, 'message' => 'Dataset tidak ditemukan.'], 404);
                    }
                    return redirect()->back()->withErrors(['error' => 'Dataset tidak ditemukan.']);
                }
            }

            // Toggle di database
            $enabled = $request->boolean('enabled');
            $configService->toggleDataset($datasetId, $enabled);

            $status = $enabled ? 'diaktifkan' : 'dinonaktifkan';
            $message = "Dataset '{$dataset['name']}' berhasil {$status}.";

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => $message, 'enabled' => $enabled]);
            }

            return redirect()->back()->with('status', $message);
        } catch (\Exception $e) {
            Log::error("Error toggling dataset {$datasetId}: " . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal mengubah status: ' . $e->getMessage()], 500);
            }

            return redirect()->back()->withErrors(['error' => 'Gagal mengubah status: ' . $e->getMessage()]);
        }
    }

    /**
     * Get datasets list untuk dashboard management
     * Route: GET /admin/datasets/management/list
     */
    public function getDatasetsList()
    {
        try {
            $configService = new DatasetConfigService();
            $datasets = $configService->getDatasetsList();

            return response()->json(['success' => true, 'data' => $datasets]);
        } catch (\Exception $e) {
            Log::error("Error fetching datasets list: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show datasets management page
     * Route: GET /admin/datasets/management
     */
    public function management()
    {
        return view('admin.datasets.management');
    }
}
