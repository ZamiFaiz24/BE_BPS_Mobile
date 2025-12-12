<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BpsDatasetController;
use App\Http\Controllers\API\BpsSyncController;
use App\Http\Controllers\API\BpsDataController;
use App\Http\Controllers\API\BpsContentController;
use App\Http\Controllers\Admin\ScrapeController;
use Symfony\Component\Routing\Route as RoutingRoute;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route untuk memulai proses sinkronisasi (sebagai admin)
// Route::post('/sync', [BpsSyncController::class, 'store']);

// Route untuk mengambil data yang sudah ada (untuk frontend)
Route::get('/data/{dataset_code}', [BpsDataController::class, 'show']);

// Route untuk mengambil data untuk chart
Route::get('/chart/gender/{dataset_code}/{year}', [BpsDataController::class, 'getGenderChartData']);

// Route untuk mengambil insight indicators dari multiple datasets

// Route untuk update unit dataset (manual fix)
Route::post('/update-dataset-unit', [BpsDataController::class, 'updateDatasetUnit']);

// Route untuk batch update unit multiple datasets
Route::post('/batch-update-dataset-units', [BpsDataController::class, 'batchUpdateDatasetUnits']);

// Route untuk auto-fix semua dataset units
Route::post('/auto-fix-dataset-units', [BpsDataController::class, 'autoFixDatasetUnits']);

// Route untuk lihat semua unit di semua dataset
Route::get('/dataset-units', [BpsDataController::class, 'getDatasetUnits']);

// Route untuk lihat unit di dataset tertentu
Route::get('/dataset-units/{datasetId}', [BpsDataController::class, 'getDatasetUnitsById']);

Route::prefix('content')->group(function () {
    Route::post('/news', [BpsContentController::class, 'storeNews']);
    Route::post('/press-releases', [BpsContentController::class, 'storePressRelease']);
    Route::post('/infographics', [BpsContentController::class, 'storeInfographic']);
    Route::post('/publications', [BpsContentController::class, 'storePublication']);

    Route::get('/news', [BpsContentController::class, 'getNews']);
    Route::get('/press-releases', [BpsContentController::class, 'getPressReleases']);
    Route::get('/infographics', [BpsContentController::class, 'getInfographics']);
    Route::get('/publications', [BpsContentController::class, 'getPublications']);
});

Route::prefix('homepage')->group(function () {

    Route::get('/indicators', [BpsDataController::class, 'getInsightIndicators']);

    // Grid endpoints (letakkan sebelum routes dengan parameter dinamis)
    Route::get('grid', [BpsDatasetController::class, 'getGrid']);
    Route::get('grid/{slug}', [BpsDatasetController::class, 'getGridDetail']);
});

Route::prefix('datasets')->group(function () {
    Route::get('categories', [BpsDatasetController::class, 'getCategories']);

    // Route untuk daftar dataset (ringan)
    Route::get('/', [BpsDatasetController::class, 'index']);

    // Route dengan parameter dinamis harus di bawah
    Route::get('{dataset}', [BpsDatasetController::class, 'show']);
    Route::get('{dataset}/history', [BpsDatasetController::class, 'history']);
    Route::get('{dataset}/insights', [BpsDatasetController::class, 'insights']);
});

// Route untuk testing scraper (Admin only - tambahkan middleware auth nanti)
Route::prefix('admin/scrape')->group(function () {
    // Test scraping dengan auto-detect tipe
    Route::match(['get', 'post'], '/test', [ScrapeController::class, 'test']);

    // Scrape per tipe konten
    Route::post('/publication', [ScrapeController::class, 'scrapePublication']);
    Route::post('/pressrelease', [ScrapeController::class, 'scrapePressRelease']);
    Route::post('/news', [ScrapeController::class, 'scrapeNews']);
    Route::post('/infographic', [ScrapeController::class, 'scrapeInfographic']);
});
