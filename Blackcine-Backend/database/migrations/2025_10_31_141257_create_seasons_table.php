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
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('title_id');
            $table->index('title_id');
            $table->unsignedInteger('season_number');
            $table->string('name')->nullable();
            $table->text('synopsis')->nullable();
            $table->unsignedInteger('release_year')->nullable();
            $table->unsignedBigInteger('poster_asset_id')->nullable()->index();
            $table->timestamps();
            $table->unique(['title_id', 'season_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seasons');
    }
};
