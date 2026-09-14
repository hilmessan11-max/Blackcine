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
        Schema::create('festival_title', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('festival_id');
            $table->unsignedBigInteger('title_id');
            $table->string('section')->nullable(); // selection officielle, hors compétition, etc.
            $table->unsignedInteger('year')->nullable();
            $table->timestamps();
            $table->unique(['festival_id', 'title_id']);
            $table->index(['festival_id', 'year']);
            $table->index('title_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('festival_title');
    }
};
