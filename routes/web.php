<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DashboardContentController;
use App\Http\Controllers\Admin\ScrapeController;
use App\Http\Controllers\Admin\SyncController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SyncLogController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

// --- RUTE PUBLIK & AUTENTIKASI DASAR ---

Route::get('/', function () {
    return redirect()->route('login');
});

// Ini adalah route dashboard BREEZE default (redirect ke admin dashboard)
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rute untuk Halaman PROFIL (bisa diakses semua user)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// --- RUTE PANEL ADMIN (/admin) ---
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Alias kompatibel lama
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');

    /*
    |--------------------------------------------------------------------------
    | GRUP 1: RUTE KONTEN (Akses: Super Admin & Operator)
    |--------------------------------------------------------------------------
    | Rute di sini dilindungi oleh izin 'view content', 
    | yang dimiliki oleh kedua role.
    */
    Route::middleware(['can:view content'])->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Resource Konten
        Route::resource('contents', DashboardContentController::class);

        Route::post('/contents/scrape', [ScrapeController::class, 'scrape'])
            ->name('contents.scrape');

        // Kelompok Rute Dataset
        Route::prefix('datasets')->name('datasets.')->group(function () {
            Route::get('/ajax-filter', [DashboardController::class, 'ajaxFilter'])->name('ajax-filter');
            Route::get('/ajax-search', [DashboardController::class, 'ajaxSearch'])->name('ajax-search');
            Route::get('/management', [DashboardController::class, 'management'])->name('management');
            Route::post('/{datasetId}/sync', [DashboardController::class, 'syncSingleDataset'])->name('sync');
            Route::post('/{datasetId}/toggle', [DashboardController::class, 'toggleDataset'])->name('toggle');
            Route::get('/management/list', [DashboardController::class, 'getDatasetsList'])->name('management.list');
            Route::get('/{dataset}', [DashboardController::class, 'show'])->name('show');
            Route::delete('/{dataset}', [DashboardController::class, 'destroy'])->name('destroy');
            Route::get('/{dataset}/edit', [DashboardController::class, 'edit'])->name('edit');
            Route::patch('/{dataset}', [DashboardController::class, 'update'])->name('update');
        });
    });


    /*
    |--------------------------------------------------------------------------
    | GRUP 2: RUTE SISTEM (Akses: HANYA Super Admin)
    |--------------------------------------------------------------------------
    | Rute di sini dilindungi oleh izin 'view settings', 
    | yang HANYA dimiliki oleh super-admin.
    */
    Route::middleware(['can:view settings'])->group(function () {

        // Kelompok Rute Pengaturan
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index'); // -> admin.settings.index
            Route::post('/', [SettingController::class, 'update'])->name('update'); // -> admin.settings.update
            Route::get('/backup', [SettingController::class, 'backup'])->name('backup'); // -> admin.settings.backup
        });

        // Rute Sinkronisasi
        Route::post('/sync/all', [DashboardController::class, 'syncAllDatasets'])->name('sync.all');
        Route::post('/sync/manual', [SyncController::class, 'manual'])->name('sync.manual');

        // Rute Dataset Management
        Route::post('/datasets/{datasetId}/toggle', [DashboardController::class, 'toggleDataset'])->name('datasets.toggle');
        Route::post('/datasets/{datasetId}/sync', [DashboardController::class, 'syncSingleDataset'])->name('datasets.sync');
        Route::post('/datasets/{datasetId}/update-config', [DashboardController::class, 'updateConfig'])->name('datasets.updateConfig');

        Route::get('logs', [SyncLogController::class, 'index'])->name('logs.index');
        Route::get('logs/{log}', [SyncLogController::class, 'show'])->name('logs.show');
        Route::get('/sync/status/{log}', [SyncLogController::class, 'checkStatus'])->name('sync.status');
    });

    /*
    |--------------------------------------------------------------------------
    | GRUP 3: KELOLA USER (Akses: HANYA Superadmin)
    |--------------------------------------------------------------------------
    | Rute untuk CRUD user, hanya bisa diakses oleh superadmin
    */
    Route::middleware(['can:manage users'])->group(function () {
        Route::resource('users', UserController::class);
    });
});


// File auth bawaan
require __DIR__ . '/auth.php';
