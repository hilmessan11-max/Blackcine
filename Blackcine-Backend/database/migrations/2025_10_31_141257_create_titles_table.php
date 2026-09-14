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
        Schema::create('titles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['movie', 'series', 'classic'])->default('movie');
            $table->text('synopsis')->nullable();
            $table->date('release_date')->nullable();
            $table->string('origin_country', 2)->nullable();
            $table->string('original_language', 5)->nullable(); // ISO 639-1
            $table->string('certification')->nullable(); // e.g., PG-13
            $table->unsignedInteger('runtime_minutes')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->unsignedInteger('price_cents')->nullable();
            $table->foreignId('currency_id')->nullable()->index();
            $table->unsignedBigInteger('poster_asset_id')->nullable()->index();
            $table->unsignedBigInteger('backdrop_asset_id')->nullable()->index();
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamps();
            $table->index(['type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titles');
    }
};
