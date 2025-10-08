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

        $urlPath = "";
        foreach ($params as $paramKey => $paramValue) {
            $urlPath .= "/{$paramKey}/{$paramValue}";
        }

        // URL BPS versi lama menggunakan /list/model/{model}, mari kita coba yang lebih umum
        // URL dasar BPS adalah /v1/api/{model}/...
        // Mari kita coba struktur URL yang lebih sesuai dengan dokumentasi di beberapa kasus
        // Kita akan coba /v1/api/list/model/{model}/... seperti sebelumnya

        $baseUrl = "https://webapi.bps.go.id/v1/api/list/model/{$model}/lang/ind";
        $url = "{$baseUrl}{$urlPath}/key/{$key}";

        // =================================================================
        // LOG "MATA-MATA" 1: Catat URL yang akan kita panggil
        // =================================================================
        Log::info('Attempting to fetch BPS URL: ' . $url);

        try {
            $response = Http::timeout(60)->get($url);

            if ($response->successful() && isset($response->json()['datacontent'])) {
                return $response->json();
            }

            // =================================================================
            // LOG "MATA-MATA" 2: Jika gagal, catat semua response dari BPS
            // =================================================================
            Log::warning("Data not available or request failed.", [
                'url'           => $url,
                'status_code'   => $response->status(),
                'response_body' => $response->body(), // Ini yang paling penting!
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error("Connection exception to BPS URL {$url}: " . $e->getMessage());
            return null;
        }
    }
}