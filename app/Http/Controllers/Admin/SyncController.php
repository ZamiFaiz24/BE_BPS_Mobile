<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Mail\SyncFailedNotification;
use App\Mail\SyncSuccessNotification; 
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Facades\Log;

class SyncController extends Controller
{
    public function manual(Request $request)
    {
        // Kunci sinkronisasi agar tidak berjalan ganda
        $lock = Cache::lock('sync:manual', 60);
        if (!$lock->get()) {
            return back()->withErrors(['status' => 'Sinkronisasi sedang berjalan. Coba lagi nanti.']);
        }

        try {
            Log::info('Manual sync triggered by user ID: ' . ($request->user()->id ?? 'unknown'));

            // --- LOGIC SINKRONISASI ANDA SEHARUSNYA DI SINI ---
            // dispatch(new \App\Jobs\SyncBpsContentJob());

            // (PENTING: Baris ini untuk tes GAGAL. Beri komentar untuk tes SUKSES)
            // throw new \Exception("Ini adalah tes email gagal yang disengaja.");
            // --- END LOGIC ---

            // Jika sukses, kirim notifikasi SUKSES
            Log::info('Sinkronisasi manual BERHASIL dipicu.');

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

            return back()->with('status', 'Sinkronisasi manual dipicu. Periksa log untuk progres.');
        } catch (\Exception $e) {

            // Jika GAGAL, kirim notifikasi ERROR
            $errorMessage = $e->getMessage();
            Log::error('SINKRONISASI MANUAL GAGAL: ' . $errorMessage);

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