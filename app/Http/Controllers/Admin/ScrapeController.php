<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BpsContentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ScrapeController extends Controller
{
    protected BpsContentService $bpsService;

    public function __construct(BpsContentService $bpsService)
    {
        $this->bpsService = $bpsService;
    }

    /**
     * Method ini namanya 'scrape' agar cocok dengan route di web.php
     */
    public function scrape(Request $request): JsonResponse
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        $url = $request->url;

        $script = storage_path('app/scripts/scraper_api.py');

        // Cek apakah script exist
        if (!file_exists($script)) {
            Log::error('Scraper script not found', ['path' => $script]);
            return response()->json([
                'success' => false,
                'message' => 'Script scraper tidak ditemukan: ' . $script
            ], 500);
        }

        // Gunakan 'python' untuk Windows, 'python3' untuk Linux/Mac
        $pythonCmd = PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3';

        // Di Windows, gunakan double quotes untuk path dan argument
        if (PHP_OS_FAMILY === 'Windows') {
            $cmd = "\"$pythonCmd\" \"$script\" \"$url\" 2>&1";
        } else {
            $escapedUrl = escapeshellarg($url);
            $cmd = "$pythonCmd \"$script\" $escapedUrl 2>&1";
        }

        Log::info('Running scraper', ['command' => $cmd, 'url' => $url]);

        $output = shell_exec($cmd);
        if (!$output) {
            Log::error('Python script no output', ['command' => $cmd]);
            return response()->json([
                'success' => false,
                'message' => 'Python script tidak menghasilkan output. Pastikan Python terinstall dan script berfungsi dengan baik.'
            ], 500);
        }

        $json = json_decode($output, true);

        if (!$json) {
            return response()->json([
                'success' => false,
                'message' => 'Output Python tidak valid JSON',
                'raw_output' => $output
            ], 500);
        }

        // Return data JSON untuk auto-fill form (tidak langsung simpan ke DB)
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diambil',
            'data' => $json
        ]);
    }
}
