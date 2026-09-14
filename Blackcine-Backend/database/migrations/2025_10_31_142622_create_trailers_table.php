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
        Schema::create('trailers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trailarable_id');
            $table->string('trailarable_type');
            $table->string('title')->nullable();
            $table->string('source_type')->default('url'); // url, upload, youtube, vimeo
            $table->text('source_url')->nullable();
            $table->unsignedBigInteger('asset_id')->nullable()->index();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->boolean('is_official')->default(true);
            $table->unsignedInteger('display_order')->default(0);
            $table->timestamps();
            $table->index(['trailarable_id', 'trailarable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trailers');
    }
};
