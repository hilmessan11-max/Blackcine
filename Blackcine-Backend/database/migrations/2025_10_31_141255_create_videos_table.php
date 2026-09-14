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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('source_type')->default('url'); // url, upload, youtube, vimeo, s3
            $table->text('source_url')->nullable();
            $table->foreignId('asset_id')->nullable()->index(); // uploaded file reference
            $table->foreignId('thumbnail_asset_id')->nullable()->index();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->index(['status', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
