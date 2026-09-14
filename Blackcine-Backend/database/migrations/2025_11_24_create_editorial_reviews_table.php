<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('editorial_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('title_id')->constrained('titles')->onDelete('cascade');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->string('headline');
            $table->text('content');
            $table->integer('rating')->nullable()->comment('Note critique sur 5 ou 10');
            $table->string('critic_name')->nullable();
            $table->string('publication')->nullable();
            $table->date('publication_date')->nullable();
            $table->string('external_url')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_featured')->default(false)->comment('Mise en avant');
            $table->integer('display_order')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            // Index
            $table->index('title_id');
            $table->index('status');
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('editorial_reviews');
    }
};
