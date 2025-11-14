<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Mail\SyncFailedNotification; 
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Facades\Log;

class SyncController extends Controller
{
    public function manual(Request $request)
    {
        // 1. Kunci agar tidak bisa dijalankan ganda. Ini sudah benar.
        $lock = Cache::lock('sync:manual', 60); // Kunci selama 60 detik
        if (!$lock->get()) {
            return back()->withErrors(['status' => 'Sinkronisasi sedang berjalan. Coba lagi nanti.']);
        }

        // 2. Kita gunakan try...catch...finally
        // Ini adalah pola yang SANGAT BAGUS.
        try {

            // -----------------------------------------------------------
            // 3. LOGIC SINKRONISASI ANDA
            // -----------------------------------------------------------
            Log::info('Manual sync triggered by user ID: ' . ($request->user()->id ?? 'unknown'));

            // GANTI KOMENTAR INI DENGAN LOGIC ANDA
            // dispatch(new \App\Jobs\SyncBpsContentJob());

            // (UNCOMMENT BARIS INI JIKA HANYA INGIN MENGETES EMAIL GAGAL)
            throw new \Exception("Ini adalah tes email gagal yang disengaja.");
            // -----------------------------------------------------------


            // 4. JIKA SUKSES, kembalikan pesan sukses
            Log::info('Sinkronisasi manual BERHASIL dipicu.');
            return back()->with('status', 'Sinkronisasi manual dipicu. Periksa log untuk progres.');
        } catch (\Exception $e) {

            // -----------------------------------------------------------
            // 5. JIKA GAGAL (catch), KIRIM NOTIFIKASI EMAIL
            // -----------------------------------------------------------
            $errorMessage = $e->getMessage();
            Log::error('SINKRONISASI MANUAL GAGAL: ' . $errorMessage);

            // Cek setting
            if (setting('email_notifications', false)) {
                $adminEmail = setting('admin_email');

                if ($adminEmail) {
                    try {
                        Mail::to($adminEmail)->send(new SyncFailedNotification($errorMessage));
                        Log::info('Email notifikasi kegagalan terkirim ke ' . $adminEmail);
                    } catch (\Exception $mailEx) {
                        Log::error('Gagal mengirim email notifikasi: ' . $mailEx->getMessage());
                    }
                }
            }

            // 6. Kembalikan pesan error ke halaman
            return back()->withErrors(['sync_error' => 'Sinkronisasi gagal: ' . $errorMessage]);
            // -----------------------------------------------------------

        } finally {

            // 7. BLOK INI SELALU DIJALANKAN (wajib)
            // Melepas kunci agar bisa dijalankan lagi nanti
            optional($lock)->release();
        }
    }
}