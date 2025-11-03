<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DashboardContentController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Admin\SyncController;

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

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/sync/all', [DashboardController::class, 'syncAllDatasets'])->name('sync.all');
    Route::post('/sync/manual', [SyncController::class, 'manual'])->name('sync.manual');

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

    Route::prefix('settings')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings');
        Route::post('/', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        Route::get('/backup', [\App\Http\Controllers\Admin\SettingController::class, 'backup'])->name('settings.backup');
    });
;
});

require __DIR__ . '/auth.php';
