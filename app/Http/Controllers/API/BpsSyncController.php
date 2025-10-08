<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\SyncBpsDataJob;
use Illuminate\Http\Request;

class BpsSyncController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'domain' => 'required|integer',
            'variable' => 'required|integer',
            'tahun_mulai' => 'required|integer|digits:4',
            'tahun_akhir' => 'required|integer|digits:4|gte:tahun_mulai',
            'label' => 'required|string|max:255',
        ]);

        SyncBpsDataJob::dispatch(
            $validated['domain'],
            $validated['variable'],
            $validated['tahun_mulai'],
            $validated['tahun_akhir'],
            $validated['label']
        );

        return response()->json(['message' => 'Proses sinkronisasi untuk dataset ' . $validated['label'] . ' telah dimulai.']);
    }
}
