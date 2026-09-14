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
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('season_id')->constrained('seasons')->cascadeOnDelete();
            $table->unsignedInteger('episode_number');
            $table->string('name');
            $table->text('synopsis')->nullable();
            $table->date('air_date')->nullable();
            $table->unsignedInteger('runtime_seconds')->nullable();
            $table->boolean('is_free')->default(true);
            $table->unsignedBigInteger('thumbnail_asset_id')->nullable()->index();
            $table->timestamps();
            $table->unique(['season_id', 'episode_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};
