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
        Schema::create('bps_datadimension', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dataset_id')
                ->constrained('bps_dataset')
                ->onDelete('cascade');
            $table->string('dimension');
            $table->string('dimension_kode');
            $table->text('dimension_value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bps_datadimension');
    }
};
