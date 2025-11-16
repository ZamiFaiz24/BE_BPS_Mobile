<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SyncLog;
use Illuminate\Http\Request;

class SyncLogController extends Controller
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

    /**
     * Check sync status for real-time updates
     */
    public function checkStatus(SyncLog $log)
    {
        // Refresh from database to get latest data
        $log->refresh();

        return response()->json([
            'status' => $log->status,
            'progress' => $log->details()->count(),
            'finished_at' => $log->finished_at ? \Carbon\Carbon::parse($log->finished_at)->format('d M Y, H:i:s') : null,
            'summary_message' => $log->summary_message,
            'is_running' => $log->status === 'berjalan',
        ]);
    }
}
