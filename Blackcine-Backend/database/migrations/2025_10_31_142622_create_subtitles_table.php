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
        Schema::create('subtitles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subtitlable_id');
            $table->string('subtitlable_type');
            $table->string('language', 5); // ISO 639-1
            $table->string('format')->default('vtt');
            $table->unsignedBigInteger('asset_id')->nullable()->index();
            $table->text('url')->nullable();
            $table->boolean('is_closed_caption')->default(false);
            $table->timestamps();
            $table->index(['subtitlable_id', 'subtitlable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subtitles');
    }
};
