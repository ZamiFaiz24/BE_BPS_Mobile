<?php

namespace App\Services;

use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Panther\Client;

class BpsContentService
{
    protected string $baseUrl = 'https://kebumenkab.bps.go.id';

    /**
     * Mengambil daftar Publikasi terbaru menggunakan ScrapingBee.
     */
    public function fetchLatestPublications()
    {
        return Cache::remember('bps_latest_publications_sb', 600, function () { // Cache 10 menit
            try {
                Log::info('SCRAPER: Mengambil publikasi dari BPS Kebumen...');

                $response = Http::get('https://app.scrapingbee.com/api/v1/', [
                    'api_key' => env('SCRAPINGBEE_API_KEY'),
                    'url' => 'https://kebumenkab.bps.go.id/publication.html',
                ]);

                if (!$response->successful()) {
                    Log::error('SCRAPER: Gagal mengambil publikasi. Status: ' . $response->status());
                    return [];
                }

                $crawler = new Crawler($response->body());
                $items = $crawler->filter('a[href*="/publication/"]'); // Selector publikasi

                Log::info('SCRAPER: Jumlah publikasi ditemukan: ' . $items->count());

                return $items->each(function ($node) {
                    return [
                        'title'       => trim($node->filter('p.text-main-primary')->text()),
                        'link'        => $node->attr('href'),
                        'date'        => trim($node->filter('small.text-black-body')->text()),
                        'image_url'   => $node->filter('img')->attr('src'),
                        'description' => trim($node->filter('div.Abstract_abstract__J_Qgq')->text()),
                    ];
                });
            } catch (\Exception $e) {
                Log::error('SCRAPER: Error mengambil publikasi. ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Mengambil daftar Berita Resmi Statistik terbaru menggunakan ScrapingBee.
     */
    public function fetchLatestNews()
    {
        return Cache::remember('bps_latest_news_sb', 10800, function () { // Cache 3 jam
            try {
                Log::info('SCRAPER: Mencoba mengambil BERITA dengan ScrapingBee...');

                $response = Http::get('https://app.scrapingbee.com/api/v1/', [
                    'api_key' => env('SCRAPINGBEE_API_KEY'),
                    'url' => $this->baseUrl . '/pressrelease.html', // URL Diperbaiki
                ]);

                if (!$response->successful()) {
                    Log::error('SCRAPER: Gagal mengambil BERITA via ScrapingBee. Status: ' . $response->status());
                    return [];
                }

                $crawler = new Crawler($response->body());
                $items = $crawler->filter('a[href*="/pressrelease/"]'); // Selector Diperbaiki
                Log::info('SCRAPER: Selector BERITA menemukan item sejumlah: ' . $items->count());

                return $items->each(function ($node) {
                    return [
                        'title'       => trim($node->filter('p.text-main-primary')->text()),
                        'link'        => $node->attr('href'),
                        'date'        => trim($node->filter('small.text-black-body')->text()), // Selector Diperbaiki
                        'image_url'   => $node->filter('img')->attr('src'),
                        'description' => trim($node->filter('div.Abstract_abstract__J_Qgq')->text()), // Selector Diperbaiki
                    ];
                });
            } catch (\Exception $e) {
                Log::error('SCRAPER: Gagal mengambil BERITA. Error: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Mengambil daftar Infografik terbaru menggunakan ScrapingBee.
     */
    public function fetchInfographics()
    {
        return Cache::remember('bps_latest_infographics_sb', 21600, function () { // Cache 6 jam
            try {
                Log::info('SCRAPER: Mencoba mengambil INFOGRAFIK dengan ScrapingBee...');

                $response = Http::get('https://app.scrapingbee.com/api/v1/', [
                    'api_key' => env('SCRAPINGBEE_API_KEY'),
                    'url' => $this->baseUrl . '/id/infographic', // URL Diperbaiki
                ]);

                if (!$response->successful()) {
                    Log::error('SCRAPER: Gagal mengambil INFOGRAFIK via ScrapingBee. Status: ' . $response->status());
                    return [];
                }

                $crawler = new Crawler($response->body());
                $items = $crawler->filter('a[href*="/infographic?id="]'); // Selector Diperbaiki
                Log::info('SCRAPER: Selector INFOGRAFIK menemukan item sejumlah: ' . $items->count());

                return $items->each(function ($node) {
                    $imgNode = $node->filter('img');
                    return [
                        'title'     => trim($imgNode->attr('alt')), // Ambil dari 'alt'
                        'link'      => $node->attr('href'),
                        'image_url' => $imgNode->attr('src'),
                    ];
                });
            } catch (\Exception $e) {
                Log::error('SCRAPER: Gagal mengambil INFOGRAFIK. Error: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Mengambil daftar Publikasi terbaru menggunakan Panther.
     */
    public function fetchLatestPublicationsWithPanther()
    {
        try {
            // Inisialisasi browser Panther dan beritahu lokasi driver-nya
            $client = Client::createChromeClient(base_path('chromedriver.exe'));

            // Kunjungi halaman publikasi BPS
            $crawler = $client->request('GET', 'https://kebumenkab.bps.go.id/publication.html');

            // Tunggu sampai elemen publikasi muncul
            $client->waitFor('p.text-main-primary');

            // Ambil semua publikasi
            $items = $crawler->filter('a[href*="/publication/"]');

            $result = $items->each(function ($node) {
                return [
                    'title'       => trim($node->filter('p.text-main-primary')->text()),
                    'link'        => $node->attr('href'),
                    // Selector untuk tanggal di halaman publikasi
                    'date'        => $node->filter('p.caption')->count() ? trim($node->filter('p.caption')->text()) : null,
                    'image_url'   => $node->filter('img')->count() ? $node->filter('img')->attr('src') : null,
                    // Selector untuk deskripsi di halaman publikasi
                    'description' => $node->filter('p.overflow-text-ellipsis.text-black-body')->count() ? trim($node->filter('p.overflow-text-ellipsis.text-black-body')->text()) : null,
                ];
            });

            $client->quit(); // Tutup browser

            return $result;
        } catch (\Exception $e) {
            Log::error('PANTHER: Error mengambil publikasi. ' . $e->getMessage());
            return [];
        }
    }
}
