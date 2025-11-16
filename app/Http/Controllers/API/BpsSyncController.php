<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SyncLog;
use App\Jobs\SyncBpsDataJob;
use Illuminate\Http\Request;

class BpsSyncController extends Controller
{

    public function index()
    {
        // Ambil semua log, urutkan dari yang terbaru
        $logs = SyncLog::with('user') // Ambil juga relasi user-nya
            ->latest() // Urutkan dari yang terbaru
            ->paginate(20); // Tampilkan 20 per halaman

        return view('admin.logs.index', compact('logs'));
    }

    /**
     * Menampilkan detail dari satu log sinkronisasi (Halaman 2).
     */
    public function show(SyncLog $log) // Gunakan Route Model Binding
    {
        // Ambil log ini beserta detail-detailnya
        $log->load('details', 'user');

        return view('admin.logs.show', compact('log'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'domain' => 'required|integer',
            'variable' => 'required|integer',
            'tahun_mulai' => 'required|integer|digits:4',
            'tahun_akhir' => 'required|integer|digits:4|gte:tahun_mulai',
            'label' => 'required|string|max:255',
            'category' => 'required|integer|exists:bps_categories,id',
        ]);

        SyncBpsDataJob::dispatch(
            $validated['domain'],
            $validated['variable'],
            $validated['tahun_mulai'],
            $validated['tahun_akhir'],
            $validated['label'],
            $validated['category'] // <-- Change this line
        );

        return response()->json(['message' => 'Proses sinkronisasi untuk dataset ' . $validated['label'] . ' telah dimulai.']);
    }
}
