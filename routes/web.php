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

    // Kelompokkan semua route dataset
    Route::prefix('datasets')->name('datasets.')->group(function () {
        Route::get('/ajax-filter', [DashboardController::class, 'ajaxFilter'])->name('ajax-filter');
        Route::get('/ajax-search', [DashboardController::class, 'ajaxSearch'])->name('ajax-search');
        Route::patch('/{dataset}/update-insight', [DashboardController::class, 'updateInsightType'])->name('update_insight');
        Route::get('/{dataset}', [DashboardController::class, 'show'])->name('show');
        Route::delete('/{dataset}', [DashboardController::class, 'destroy'])->name('destroy');
        Route::get('/{dataset}/edit', [DashboardController::class, 'edit'])->name('edit');
        Route::patch('/{dataset}', [DashboardController::class, 'update'])->name('update');
    });

    Route::resource('contents', DashboardContentController::class);
});

require __DIR__ . '/auth.php';
