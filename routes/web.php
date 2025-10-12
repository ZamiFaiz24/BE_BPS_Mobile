<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DashboardContentController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    return view('welcome');
});

// Ini adalah route dashboard untuk user biasa
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Ini adalah route untuk halaman profile user
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/sync-all-datasets', [DashboardController::class, 'syncAllDatasets'])->name('sync.all');

    Route::get('/datasets/ajax-search', [DashboardController::class, 'ajaxSearch'])->name('datasets.ajax-search');

    Route::patch('/datasets/{dataset}/update-insight', [DashboardController::class, 'updateInsightType'])->name('datasets.update_insight');
    Route::get('/datasets/{dataset}', [DashboardController::class, 'showData'])->name('datasets.show');
    Route::delete('/datasets/{dataset}', [DashboardController::class, 'destroy'])->name('datasets.destroy');
    Route::get('/datasets/{dataset}/edit', [DashboardController::class, 'edit'])->name('datasets.edit');
    Route::patch('/datasets/{dataset}', [DashboardController::class, 'update'])->name('datasets.update');

    Route::resource('contents', DashboardContentController::class);
});

require __DIR__ . '/auth.php';
