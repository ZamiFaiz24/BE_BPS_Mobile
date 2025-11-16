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
        Schema::create('sync_log_details', function (Blueprint $table) {
            $table->id();

            // Ini adalah "kunci" yang menyambungkan ke "Struk"-nya
            $table->foreignId('sync_log_id')->constrained('sync_logs')->onDelete('cascade');

            // Tindakan: 'ditambah', 'diperbarui', 'dilewati', 'gagal'
            $table->string('action');

            // Judul dataset yang diproses
            $table->string('dataset_title');

            // Pesan spesifik (misal: "Sukses" atau "Error: ID tidak ditemukan")
            $table->text('message')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_log_details');
    }
};
