<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address; // Kita tambahkan ini

class SyncFailedNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Variabel publik ini akan otomatis tersedia di file view Blade
     */
    public $errorMessage;

    /**
     * Buat instance pesan baru.
     *
     * @param string $errorMessage Pesan error yang terjadi
     */
    public function __construct($errorMessage)
    {
        // Saat kita memanggil mailer ini, kita akan memberinya pesan error
        $this->errorMessage = $errorMessage;
    }

    /**
     * Dapatkan 'amplop' (pengirim & subjek) pesan.
     */
    public function envelope(): Envelope
    {
        // Ambil "Nama Pengirim" dari setting Anda
        $fromName = setting('mail_from_name', 'Sistem Dashboard BPS');

        // Ambil alamat email 'from' dari .env
        $fromEmail = config('mail.from.address', 'no-reply@example.com');

        return new Envelope(
            from: new Address($fromEmail, $fromName),
            subject: '[PERINGATAN] Sinkronisasi Data Gagal',
        );
    }

    /**
     * Dapatkan 'isi' (template view) pesan.
     */
    public function content(): Content
    {
        return new Content(
            // Ini adalah nama file Blade yang akan kita BUAT SELANJUTNYA
            markdown: 'emails.sync.failed',
            with: [
                'errorMessage' => $this->errorMessage,
            ],
        );
    }

    /**
     * Dapatkan lampiran (attachments) untuk pesan.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
