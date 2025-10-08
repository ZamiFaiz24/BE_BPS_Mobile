<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\BpsContentService;
use Illuminate\Http\JsonResponse;

class ContentController extends Controller
{
    /**
     * Mengambil daftar publikasi terbaru.
     */
    public function publications(BpsContentService $contentService): JsonResponse
    {
        $publications = $contentService->fetchLatestPublications();

        return response()->json([
            'message' => 'Daftar publikasi berhasil diambil',
            'data' => $publications
        ]);
    }

    /**
     * Mengambil daftar berita resmi statistik terbaru.
     */
    public function news(BpsContentService $contentService): JsonResponse
    {
        $news = $contentService->fetchLatestNews();

        return response()->json([
            'message' => 'Daftar berita berhasil diambil',
            'data' => $news
        ]);
    }

    /**
     * Mengambil daftar infografik terbaru.
     */
    public function infographics(BpsContentService $contentService): JsonResponse
    {
        $infographics = $contentService->fetchInfographics();

        return response()->json([
            'message' => 'Daftar infografik berhasil diambil',
            'data' => $infographics
        ]);
    }

    public function getPublicationsWithPanther()
    {
        $data = app(\App\Services\BpsContentService::class)->fetchLatestPublicationsWithPanther();
        return response()->json($data);
    }
}
