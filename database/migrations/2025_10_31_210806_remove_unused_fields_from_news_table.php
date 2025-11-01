<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['detail_image', 'content_html', 'content_text']);
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->string('detail_image')->nullable();
            $table->text('content_html')->nullable();
            $table->text('content_text')->nullable();
        });
    }
};
