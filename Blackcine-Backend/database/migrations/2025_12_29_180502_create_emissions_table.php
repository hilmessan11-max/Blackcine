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
        Schema::create('emissions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->integer('duration')->nullable(); // en minutes
            $table->string('presenter')->nullable();
            $table->string('country')->nullable();
            $table->string('language')->nullable();
            $table->integer('year')->nullable();
            $table->string('status')->default('published'); // published, draft, archived
            $table->integer('views')->default(0);
            $table->string('thumbnail')->nullable();
            $table->string('video_url')->nullable();
            $table->boolean('is_live')->default(false);
            $table->datetime('broadcast_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emissions');
    }
};
