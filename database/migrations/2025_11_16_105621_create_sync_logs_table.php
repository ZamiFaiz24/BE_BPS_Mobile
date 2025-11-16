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
        Schema::create('sync_logs', function (Blueprint $table) {
            $table->id();

            // Status: 'berjalan', 'sukses', 'gagal'
            $table->string('status')->default('berjalan');

            // Ringkasan: "Berhasil: 2 ditambah, 5 diperbarui."
            $table->text('summary_message')->nullable();

            // Siapa yang menjalankan? (bisa null jika otomatis by system)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_logs');
    }
};
