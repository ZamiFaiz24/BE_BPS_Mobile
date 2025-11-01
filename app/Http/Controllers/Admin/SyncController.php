<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SyncController extends Controller
{
    public function manual(Request $request)
    {
        $lock = Cache::lock('sync:manual', 60);
        if (!$lock->get()) {
            return back()->withErrors(['status' => 'Sinkronisasi sedang berjalan. Coba lagi nanti.']);
        }

        try {
            // TODO: Panggil job/service sinkronisasi Anda di sini
            // dispatch(new \App\Jobs\SyncBpsContentJob());
            Log::info('Manual sync triggered by user ID: ' . ($request->user()->id ?? 'unknown'));

            return back()->with('status', 'Sinkronisasi manual dipicu. Periksa log untuk progres.');
        } finally {
            optional($lock)->release();
        }
    }
}