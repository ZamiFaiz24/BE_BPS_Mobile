<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            // 1. Ubah tipe data release_date dari VARCHAR ke DATE
            $table->date('release_date')->nullable()->change();

            // 2. Tambahkan kolom 'subject' (yang dikirim dari Python)
            // Asumsi: 'category' di tabel Anda tidak digunakan atau digantikan oleh 'subject'.
            // Jika Anda ingin mengubah nama kolom 'category' menjadi 'subject', gunakan 
            // $table->renameColumn('category', 'subject');
            // Jika Anda ingin menyimpan keduanya:
            // $table->string('subject', 255)->nullable()->after('release_date');

            // 3. Tambahkan kolom 'downloads' (array lengkap)
            $table->json('downloads')->nullable()->after('pdf_url');
        });
    }

    public function down(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            // Revert kolom subject dan downloads
            $table->dropColumn(['downloads']);
            // Revert tipe data release_date kembali ke string (jika diperlukan)
            $table->string('release_date', 255)->nullable()->change();
        });
    }
};
