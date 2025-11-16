<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Mail\SyncFailedNotification;
use App\Mail\SyncSuccessNotification; 
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Facades\Log;
use App\Models\SyncLog;
use App\Models\SyncLogDetail;
use Illuminate\Support\Facades\Auth;

class SyncController extends Controller
{
    public function manual(Request $request)
    {
        // Kunci sinkronisasi agar tidak berjalan ganda
        $lock = Cache::lock('sync:manual', 60);
        if (!$lock->get()) {
            return back()->withErrors(['status' => 'Sinkronisasi sedang berjalan. Coba lagi nanti.']);
        }

        // 1. BUAT "STRUK" LOG UTAMA
        // Kita buat log-nya DILUAR try...catch agar bisa di-update jika gagal
        $log = SyncLog::create([
            'status' => 'berjalan', // Status awal
            'user_id' => Auth::id(), // ID user yang mengklik tombol
            'started_at' => now(),
        ]);

        try {
            Log::info('Manual sync (Log ID: ' . $log->id . ') triggered by user ID: ' . ($request->user()->id ?? 'unknown'));

            // -----------------------------------------------------------
            // --- (GANTI BLOK INI DENGAN LOGIC API BPS ANDA) ---
            // -----------------------------------------------------------
            // Ini hanya CONTOH. Logic Anda yang sebenarnya (loop API) ada di sini.
            // Anda perlu menghitung $countAdded, $countUpdated, $countFailed

            $countAdded = 0;
            $countUpdated = 0;
            $countFailed = 0;

            // --- CONTOH LOOP ---
            // Asumsi $datasetsFromApi adalah hasil dari API BPS Anda
            $datasetsFromApi = [
                ['title' => 'Angka Kemiskinan 2024', 'action' => 'diperbarui', 'success' => true],
                ['title' => 'Jumlah Penduduk 2025', 'action' => 'ditambah', 'success' => true],
                ['title' => 'Laju Inflasi', 'action' => 'gagal', 'success' => false, 'error' => 'API Timeout'],
            ];

            foreach ($datasetsFromApi as $data) {
                // Di sini Anda akan menjalankan Dataset::updateOrCreate(...)

                if ($data['success']) {
                    // 2A. CATAT DETAIL JIKA SUKSES
                    SyncLogDetail::create([
                        'sync_log_id' => $log->id,
                        'action' => $data['action'], // 'ditambah' atau 'diperbarui'
                        'dataset_title' => $data['title'],
                        'message' => 'Sukses',
                    ]);

                    if ($data['action'] == 'ditambah') $countAdded++;
                    if ($data['action'] == 'diperbarui') $countUpdated++;
                } else {
                    // 2B. CATAT DETAIL JIKA GAGAL
                    SyncLogDetail::create([
                        'sync_log_id' => $log->id,
                        'action' => 'gagal',
                        'dataset_title' => $data['title'],
                        'message' => $data['error'], // Simpan pesan error-nya
                    ]);
                    $countFailed++;
                }
            }
            // --- END CONTOH LOGIC ---
            // -----------------------------------------------------------

            // (PENTING: Baris ini untuk tes GAGAL. Beri komentar untuk tes SUKSES)
            // throw new \Exception("Ini adalah tes email gagal yang disengaja.");

            // 3A. BUAT RINGKASAN SUKSES
            $summary = "Sinkronisasi berhasil: $countAdded ditambah, $countUpdated diperbarui, $countFailed gagal.";
            Log::info($summary . ' (Log ID: ' . $log->id . ')');

            // 4A. UPDATE "STRUK" LOG MENJADI SUKSES
            $log->update([
                'status' => 'sukses',
                'finished_at' => now(),
                'summary_message' => $summary,
            ]);

            // Kirim notifikasi SUKSES
            if (setting('email_notifications', false)) {
                $adminEmail = setting('admin_email');
                if ($adminEmail) {
                    try {
                        Mail::to($adminEmail)->send(new SyncSuccessNotification());
                        Log::info('Email notifikasi SUKSES terkirim ke ' . $adminEmail);
                    } catch (\Exception $mailEx) {
                        Log::error('Gagal mengirim email notifikasi SUKSES: ' . $mailEx->getMessage());
                    }
                }
            }

            return back()->with('status', 'Sinkronisasi manual berhasil. Lihat log untuk rincian.');
        } catch (\Exception $e) {

            $errorMessage = $e->getMessage();
            Log::error('SINKRONISASI MANUAL GAGAL: ' . $errorMessage . ' (Log ID: ' . $log->id . ')');

            // 4B. UPDATE "STRUK" LOG MENJADI GAGAL
            $log->update([
                'status' => 'gagal',
                'finished_at' => now(),
                'summary_message' => $errorMessage, // Simpan error utama
            ]);

            // Kirim notifikasi GAGAL
            if (setting('email_notifications', false)) {
                $adminEmail = setting('admin_email');
                if ($adminEmail) {
                    try {
                        Mail::to($adminEmail)->send(new SyncFailedNotification($errorMessage));
                        Log::info('Email notifikasi kegagalan terkirim ke ' . $adminEmail);
                    } catch (\Exception $mailEx) {
                        Log::error('Gagal mengirim email notifikasi GAGAL: ' . $mailEx->getMessage());
                    }
                }
            }

            return back()->withErrors(['sync_error' => 'Sinkronisasi gagal: ' . $errorMessage]);
        } finally {
            // Selalu lepas kunci, baik sukses maupun gagal
            optional($lock)->release();
        }
    }
}