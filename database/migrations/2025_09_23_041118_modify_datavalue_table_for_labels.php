<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bps_datavalue', function (Blueprint $table) {
            // Hapus foreign key yang lama
            $table->dropForeign(['vervar_id']);
            $table->dropForeign(['turvar_id']);
            $table->dropForeign(['turtahun_id']);
            $table->dropColumn(['vervar_id', 'turvar_id', 'turtahun_id']);

            // Tambahkan kolom baru untuk menyimpan label
            $table->string('vervar_label')->nullable()->after('dataset_id');
            $table->string('turvar_label')->nullable()->after('vervar_label');
            $table->string('turtahun_label')->nullable()->after('turvar_label');
        });
    }

    public function down(): void
    {
        // (Kode untuk rollback, bisa diabaikan untuk saat ini)
    }
};
