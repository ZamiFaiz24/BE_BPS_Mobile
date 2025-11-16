<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use App\Models\SyncLog;

class SyncController extends Controller
{
    public function manual(Request $request)
    {
        $lock = Cache::lock('sync:manual', 10);
        if (!$lock->get()) {
            return response()->json([
                'success' => false,
                'message' => 'Sinkronisasi baru saja dipicu. Coba lagi nanti.'
            ], 429);
        }

        try {
            $userId = Auth::id();

            // Buat "Struk" log terlebih dahulu
            $syncLog = SyncLog::create([
                'user_id' => $userId,
                'status' => 'berjalan',
                'started_at' => now(),
                'summary_message' => 'Sinkronisasi dimulai...'
            ]);

            Log::info("Manual sync triggered by User ID: $userId, Log ID: {$syncLog->id}");

            // Dispatch queue job dengan log_id
            Artisan::queue('bps:fetch-data', [
                '--log_id' => $syncLog->id
            ])->onQueue('sync-jobs');

            // Return JSON response
            return response()->json([
                'success' => true,
                'message' => 'Sinkronisasi manual telah dimasukkan ke antrean.',
                'log_id' => $syncLog->id,
                'check_url' => route('admin.sync.status', $syncLog->id)
            ]);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            Log::error('Gagal memicu sinkronisasi manual: ' . $errorMessage);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memicu sinkronisasi: ' . $errorMessage
            ], 500);
        } finally {
            $lock->release();
        }
    }
}
