<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Tidak terpakai, bisa hapus jika mau
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address; // 1. Pastikan ini di-import

class SyncFailedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $errorMessage;

    /**
     * Create a new message instance.
     */
    public function __construct($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // 2. KEMBALIKAN BAGIAN 'FROM' INI
        // Ini mengambil "Nama Pengirim" dari setting Anda
        $fromName = setting('mail_from_name', 'Sistem Dashboard BPS');
        $fromEmail = config('mail.from.address', 'no-reply@example.com');

        return new Envelope(
            from: new Address($fromEmail, $fromName), // 3. Tambahkan baris ini
            subject: '🚨 Sinkronisasi Data BPS Gagal', // Subjek Anda sudah benar
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            // 4. GANTI 'view:' KEMBALI KE 'markdown:'
            markdown: 'emails.sync.failed',
            with: [
                'errorMessage' => $this->errorMessage,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
