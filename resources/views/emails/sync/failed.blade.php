@component('mail::message')
# 🚨 Peringatan: Sinkronisasi Data Gagal

Halo Admin,

Sistem dashboard BPS baru saja mengalami kegagalan saat mencoba sinkronisasi data otomatis.

**Detail Error:**
> {{ $errorMessage }}

Mohon segera periksa log sistem atau coba jalankan sinkronisasi manual di dashboard.

{{-- PERBAIKAN ADA DI BARIS INI --}}
@component('mail::button', ['url' => url('/admin/settings'), 'color' => 'error'])
Buka Halaman Pengaturan
@endcomponent

Terima kasih,<br>
{{ setting('mail_from_name', 'Sistem Dashboard BPS') }}
@endcomponent