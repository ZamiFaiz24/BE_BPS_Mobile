<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Tidak terpakai
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address; // Pastikan ini di-import

class SyncSuccessNotification extends Mailable
{
    use Queueable, SerializesModels;

    // Kita tidak perlu $errorMessage di sini
    // Tapi Anda bisa tambahkan data lain, misal: public $summary;

    /**
     * Buat instance pesan baru.
     */
    public function __construct()
    {
        // Jika Anda ingin mengirim ringkasan, tambahkan di sini
        // $this->summary = $summary;
    }

    /**
     * Dapatkan 'amplop' (pengirim & subjek) pesan.
     */
    public function envelope(): Envelope
    {
        // Ambil "Nama Pengirim" dari setting Anda
        $fromName = setting('mail_from_name', 'Sistem Dashboard BPS');
        $fromEmail = config('mail.from.address', 'no-reply@example.com');

        return new Envelope(
            from: new Address($fromEmail, $fromName),
            subject: '✅ [INFO] Sinkronisasi Data BPS Berhasil', // Subjek baru
        );
    }

    /**
     * Dapatkan 'isi' (template view) pesan.
     */
    public function content(): Content
    {
        return new Content(
            // Ini adalah nama file Blade yang akan kita BUAT SELANJUTNYA
            markdown: 'emails.sync.success', // View baru
            with: [
                // 'summary' => $this->summary, // Kirim data jika perlu
            ],
        );
    }

    /**
     * Dapatkan lampiran (attachments) untuk pesan.
     */
    public function attachments(): array
    {
        return [];
    }
}
