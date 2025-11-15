@component('mail::message')
# ✅ Info: Sinkronisasi Data Berhasil

Halo Admin,

Ini adalah konfirmasi bahwa sinkronisasi data BPS telah **berhasil** dijalankan.

Semua data di aplikasi mobile Anda telah diperbarui.

@component('mail::button', ['url' => url('/admin/dashboard'), 'color' => 'success'])
Buka Dashboard
@endcomponent

Terima kasih,<br>
{{ setting('mail_from_name', 'Sistem Dashboard BPS') }}
@endcomponent