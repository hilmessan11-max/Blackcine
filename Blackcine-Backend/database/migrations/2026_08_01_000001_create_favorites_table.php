<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20); // film, series, article, emission
            $table->unsignedBigInteger('item_id');
            $table->string('name')->nullable();
            $table->string('poster')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'type', 'item_id']);
            $table->index(['type', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
