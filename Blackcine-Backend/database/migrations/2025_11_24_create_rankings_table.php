<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rankings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['general', 'genre', 'country', 'year', 'custom'])->default('general');
            $table->string('genre')->nullable();
            $table->string('country')->nullable();
            $table->integer('year')->nullable();
            $table->enum('period', ['all_time', 'year', 'month', 'week'])->default('all_time');
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
            
            $table->index('type');
            $table->index('is_active');
        });

        Schema::create('ranking_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ranking_id')->constrained()->onDelete('cascade');
            $table->foreignId('title_id')->constrained('titles')->onDelete('cascade');
            $table->integer('position');
            $table->integer('previous_position')->nullable();
            $table->decimal('score', 8, 2)->nullable()->comment('Score calculé');
            $table->integer('votes_count')->default(0);
            $table->decimal('average_rating', 3, 2)->nullable();
            $table->timestamps();
            
            // Contrainte unique : un titre ne peut être qu'une fois dans un classement
            $table->unique(['ranking_id', 'title_id']);
            $table->index(['ranking_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ranking_entries');
        Schema::dropIfExists('rankings');
    }
};
