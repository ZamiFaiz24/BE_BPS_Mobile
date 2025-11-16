<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
class SyncController extends Controller
{
    public function manual(Request $request)
    {
        $lock = Cache::lock('sync:manual', 10);
        if (!$lock->get()) {
            return back()->withErrors(['status' => 'Sinkronisasi baru saja dipicu. Coba lagi nanti.']);
        }

        try {
            $userId = Auth::id();
            Log::info("Manual sync triggered by User ID: $userId");

            /// -----------------------------------------------------------
            // GANTI INI:
            // Artisan::call('bps:fetch-data', [
            //     '--user_id' => $userId
            // ]);

            // MENJADI INI:
            Artisan::queue('bps:fetch-data', [
                '--user_id' => $userId
            ])->onQueue('sync-jobs'); // (Opsional: kita beri nama antrean)
            // -----------------------------------------------------------

            return back()->with('status', 'Sinkronisasi manual telah dimasukkan ke antrean. Lihat log untuk progres.');

        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            Log::error('Gagal memicu sinkronisasi manual: ' . $errorMessage);
            return back()->withErrors(['sync_error' => 'Gagal memicu sinkronisasi: ' . $errorMessage]);
        }
    }
}
