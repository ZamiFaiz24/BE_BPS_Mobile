<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BpsApiService
{
    public function tahunKeKode(int $tahun): int
    {
        return $tahun - 1900;
    }

    public function fetchData(string $model, array $params): ?array
    {
        $key = env('BPS_API_KEY');
        if (!$key) {
            Log::error('BPS API Key is not set.');
            return null;
        }

        $domain = isset($params['domain']) ? $params['domain'] : '3305';
        unset($params['domain']);

        $urlPath = "";
        foreach ($params as $paramKey => $paramValue) {
            if (is_string($paramKey)) {
                $urlPath .= "/{$paramKey}/{$paramValue}";
            }
        }

        $baseUrl = "https://webapi.bps.go.id/v1/api/list/model/data/lang/ind/domain/{$domain}{$urlPath}/key/{$key}";

        // Log 1: Mencatat percobaan koneksi (sudah ada)
        Log::info('Attempting to fetch BPS URL: ' . $baseUrl);

        try {
            // Kita tambahkan opsi untuk mencegah error SSL acak
            $response = Http::withOptions([
                'verify' => false, // Nonaktifkan SSL verification (HANYA UNTUK TESTING!)
                'curl' => [
                    CURLOPT_FORBID_REUSE => true,
                    CURLOPT_CONNECTTIMEOUT => 30, // Connection timeout 30 detik
                    CURLOPT_TIMEOUT => 120, // Total timeout 120 detik
                ],
            ])->timeout(120)->get($baseUrl);

            $responseData = $response->json();

            // ==========================================================
            // ===== 👇 BAGIAN LOGGING YANG DISEMPURNAKAN ADA DI SINI 👇 =====
            // ==========================================================

            // Kondisi 1: Permintaan BERHASIL dan data tersedia
            if ($response->successful() && isset($responseData['datacontent'])) {
                Log::info("✅ BPS API response received successfully.", [
                    'url' => $baseUrl,
                    'variable' => $responseData['var'][0]['label'] ?? 'N/A',
                    'last_update' => $responseData['last_update'] ?? 'N/A',
                    'data_count' => count($responseData['datacontent']),
                ]);
                return $responseData;
            }

            // Kondisi 2: GAGAL karena ada pesan error spesifik dari BPS (misal: API Key salah)
            if (isset($responseData['message'])) {
                Log::error("❌ BPS API returned a specific error.", [
                    'url' => $baseUrl,
                    'status_code' => $response->status(),
                    'error_message' => $responseData['message'],
                ]);
                return null;
            }

            // Kondisi 3: GAGAL karena data tidak tersedia (tapi koneksi berhasil)
            if ($response->successful() && !isset($responseData['datacontent'])) {
                Log::warning("⚠️ BPS API request successful, but data is not available.", [
                    'url' => $baseUrl,
                    'data_availability' => $responseData['data-availability'] ?? 'unknown',
                ]);
                return null;
            }

            // Kondisi 4: GAGAL karena sebab lain (misal: error 500 dari server BPS)
            Log::error("❌ BPS API request failed with a non-2xx status code.", [
                'url' => $baseUrl,
                'status_code' => $response->status(),
                'response_body' => $response->body(),
            ]);
            return null;
        } catch (\Exception $e) {
            // Log 5: Gagal karena masalah koneksi (sudah ada)
            Log::error("🔥 Connection exception to BPS URL {$baseUrl}: " . $e->getMessage());
            return null;
        }
    }
}
