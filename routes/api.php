<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BpsSyncController;
use App\Http\Controllers\API\BpsDataController;
use App\Http\Controllers\API\ContentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route untuk memulai proses sinkronisasi (sebagai admin)
Route::post('/sync', [BpsSyncController::class, 'store']);

// Route untuk mengambil data yang sudah ada (untuk frontend)
Route::get('/data/{dataset_code}', [BpsDataController::class, 'show']);

// Route untuk mengambil data untuk chart
Route::get('/chart/gender/{dataset_code}/{year}', [BpsDataController::class, 'getGenderChartData']);

Route::get('/v1/publications', [ContentController::class, 'publications']);
Route::get('/v1/news', [ContentController::class, 'news']);
Route::get('/v1/infographics', [ContentController::class, 'infographics']);

Route::get('/bps/publications/panther', [ContentController::class, 'getPublicationsWithPanther']);