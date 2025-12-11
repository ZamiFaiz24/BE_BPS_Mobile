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
        Schema::create('dataset_overrides', function (Blueprint $table) {
            $table->id();
            $table->string('dataset_id')->unique()->comment('ID unik dari config atau quick add');
            $table->enum('source_type', ['config', 'quick_add'])->default('config')->comment('Sumber dataset: dari config atau quick add');
            $table->boolean('enabled')->default(true)->comment('Status aktif/nonaktif dataset');
            $table->text('api_url')->nullable()->comment('Original API URL jika dari quick add');
            $table->json('config')->nullable()->comment('Konfigurasi dataset jika override');
            $table->unsignedBigInteger('created_by')->nullable()->comment('User yang membuat override');
            $table->text('notes')->nullable()->comment('Catatan tambahan tentang override');
            $table->timestamps();

            $table->index('enabled');
            $table->index('source_type');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dataset_overrides');
    }
};
