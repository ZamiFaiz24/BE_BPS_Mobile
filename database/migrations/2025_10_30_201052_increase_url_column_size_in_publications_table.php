<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            // Ubah pdf_url menjadi 512 karakter (atau 1024 jika Anda sangat khawatir)
            $table->string('pdf_url', 512)->change();

            // Disarankan juga untuk cover_url dan link (link BPS juga panjang)
            $table->string('cover_url', 512)->change();
            $table->string('link', 512)->change();
        });
    }

    public function down(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            // Revert (Kembalikan ke ukuran awal jika migrasi dibatalkan)
            // Ganti angka 255 dengan ukuran asli Anda.
            $table->string('pdf_url', 255)->change();
            $table->string('cover_url', 255)->change();
            $table->string('link', 255)->change();
        });
    }
};
