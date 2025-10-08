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
        Schema::create('bps_dataset', function (Blueprint $table) {
            $table->id(); // Ini otomatis BIGINT UNSIGNED
            $table->string('dataset_code')->unique();
            $table->string('dataset_name');
            $table->string('subject')->nullable();
            $table->string('source');
            $table->timestamp('last_update')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bps_dataset');
    }
};
