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
        Schema::table('press_releases', function (Blueprint $table) {
            // URL PDF utama (untuk akses cepat)
            $table->string('pdf_url', 512)->nullable()->after('link');

            // Array semua tautan unduhan (JSON)
            $table->json('downloads')->nullable()->after('pdf_url');
        });
    }

    public function down(): void
    {
        Schema::table('press_releases', function (Blueprint $table) {
            $table->dropColumn(['pdf_url', 'downloads']);
        });
    }
};
