<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BpsDatasetController;
use App\Http\Controllers\API\BpsSyncController;
use App\Http\Controllers\API\BpsDataController;
use App\Http\Controllers\API\BpsContentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route untuk memulai proses sinkronisasi (sebagai admin)
Route::post('/sync', [BpsSyncController::class, 'store']);

// Route untuk mengambil data yang sudah ada (untuk frontend)
Route::get('/data/{dataset_code}', [BpsDataController::class, 'show']);

// Route untuk mengambil data untuk chart
Route::get('/chart/gender/{dataset_code}/{year}', [BpsDataController::class, 'getGenderChartData']);

Route::prefix('content')->group(function () {
    Route::post('/news', [BpsContentController::class, 'storeNews']);
    Route::post('/press-releases', [BpsContentController::class, 'storePressRelease']);
    Route::post('/infographics', [BpsContentController::class, 'storeInfographic']);
    Route::post('/publications', [BpsContentController::class, 'storePublication']);
});

Route::prefix('datasets')->group(function () {
    Route::get('{dataset}', [\App\Http\Controllers\API\BpsDatasetController::class, 'show']);
    Route::get('{dataset}/history', [\App\Http\Controllers\API\BpsDatasetController::class, 'history']);
    Route::get('{dataset}/insights', [\App\Http\Controllers\API\BpsDatasetController::class, 'insights']);
});
