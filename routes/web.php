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

    // Route untuk menampilkan halaman admin dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // [DIUBAH] Ganti metode dari POST ke GET dan sesuaikan namanya
    Route::get('/fetch-bps-data', function () {
        try {
            Artisan::call('bps:fetch-data');
            return redirect()->route('admin.dashboard')->with('status', 'Sukses! Proses sinkronisasi dan pengambilan data BPS telah berhasil.');
        } catch (\Exception $e) {
            Log::error('Manual BPS Fetch Failed: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', 'Gagal memicu pembaruan data BPS.');
        }
    })->name('fetch.bps.get'); // <-- Nama disesuaikan menjadi 'fetch.bps.get'

    // Route untuk menyimpan perubahan tipe insight per baris
    Route::patch('/datasets/{dataset}/update-insight', [DashboardController::class, 'updateInsightType'])
        ->name('datasets.update_insight');

    // Route untuk menampilkan detail data
    Route::get('/datasets/{dataset}', [DashboardController::class, 'showData'])
        ->name('datasets.show');

    Route::resource('contents', DashboardContentController::class);
});

require __DIR__ . '/auth.php';
