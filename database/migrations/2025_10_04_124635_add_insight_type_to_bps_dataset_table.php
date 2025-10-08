<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bps_dataset', function (Blueprint $table) {
            // Menambahkan kolom insight_type setelah dataset_name
            // Kolom ini bisa berisi string seperti 'default', 'lower_is_better', dll.
            // Dibuat nullable agar data lama tidak error.
            $table->string('insight_type')->nullable()->default('default')->after('dataset_name');
        });
    }

    public function down(): void
    {
        Schema::table('bps_dataset', function (Blueprint $table) {
            $table->dropColumn('insight_type');
        });
    }
};
