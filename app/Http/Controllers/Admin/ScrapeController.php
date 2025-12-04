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

        // Gunakan exec() dengan output array untuk mendapat semua baris output
        $outputLines = [];
        $returnCode = 0;
        exec($cmd, $outputLines, $returnCode);

        // Gabungkan semua baris output
        $output = implode("\n", $outputLines);

        Log::info('Python execution completed', [
            'return_code' => $returnCode,
            'lines_count' => count($outputLines),
            'first_line' => $outputLines[0] ?? 'EMPTY',
            'last_line' => $outputLines[count($outputLines) - 1] ?? 'EMPTY'
        ]);

        if (!$output || count($outputLines) === 0) {
            Log::error('Python script no output', [
                'command' => $cmd,
                'return_code' => $returnCode
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Python script tidak menghasilkan output. Pastikan Python terinstall dan Playwright sudah disetup. Return code: ' . $returnCode
            ], 500);
        }

        // Log raw output untuk debugging
        Log::info('Python raw output', ['output' => $output]);

        // Coba ambil baris terakhir yang berisi JSON (ignore error messages)
        $lines = explode("\n", trim($output));
        $jsonLine = null;

        // Cari dari belakang untuk mendapatkan baris JSON yang valid
        for ($i = count($lines) - 1; $i >= 0; $i--) {
            $line = trim($lines[$i]);
            if (!$line) continue;

            // Cek apakah ini baris JSON yang valid (dimulai dengan { atau [)
            if (isset($line[0]) && ($line[0] === '{' || $line[0] === '[')) {
                // Validasi lebih lanjut: coba decode
                $testDecode = json_decode($line, true);
                if ($testDecode !== null) {
                    $jsonLine = $line;
                    break;
                }
            }
        }

        if (!$jsonLine) {
            Log::error('No valid JSON line found', [
                'raw_output' => $output,
                'lines_count' => count($lines),
                'last_5_lines' => array_slice($lines, -5)
            ]);

            // Cek apakah ada error umum
            if (stripos($output, 'playwright') !== false || stripos($output, 'ModuleNotFoundError') !== false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Playwright belum terinstall. Jalankan: pip install playwright && playwright install chromium'
                ], 500);
            }

            if (stripos($output, 'TimeoutError') !== false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Timeout saat mengakses halaman. Coba lagi atau periksa koneksi internet.'
                ], 500);
            }

            if (stripos($output, 'Error') !== false || stripos($output, 'Exception') !== false) {
                // Ambil baris error
                $errorLines = array_filter($lines, function ($line) {
                    return stripos($line, 'Error') !== false || stripos($line, 'Exception') !== false;
                });

                return response()->json([
                    'success' => false,
                    'message' => 'Error dari Python scraper: ' . implode(' | ', array_slice($errorLines, 0, 3)),
                    'raw_output' => substr($output, 0, 2000)
                ], 500);
            }

            return response()->json([
                'success' => false,
                'message' => 'Output Python tidak valid JSON. Periksa log untuk detail lengkap.',
                'raw_output' => substr($output, 0, 1000)
            ], 500);
        }

        $json = json_decode($jsonLine, true);

        if (!$json) {
            Log::error('JSON decode failed', ['json_line' => $jsonLine, 'decode_error' => json_last_error_msg()]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal decode JSON: ' . json_last_error_msg(),
                'raw_output' => substr($output, 0, 1000)
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
