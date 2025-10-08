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
        Schema::create('bps_datavalue', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dataset_id')
                ->constrained('bps_dataset')
                ->onDelete('cascade');

            $table->foreignId('vervar_id')->nullable()
                ->constrained('bps_datadimension')
                ->onDelete('cascade');

            $table->foreignId('turvar_id')->nullable()
                ->constrained('bps_datadimension')
                ->onDelete('cascade');

            $table->foreignId('turtahun_id')->nullable()
                ->constrained('bps_datadimension')
                ->onDelete('cascade');

            $table->integer('year');
            $table->integer('month')->nullable();
            $table->double('value');
            $table->string('unit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bps_datavalue');
    }
};
