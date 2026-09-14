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
        Schema::create('showtimes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('title_id');
            $table->dateTime('starts_at');
            $table->unsignedInteger('runtime_minutes')->nullable();
            $table->unsignedInteger('base_price_cents')->default(0);
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->string('status')->default('scheduled'); // scheduled, cancelled, ended
            $table->timestamps();
            $table->index(['room_id', 'starts_at']);
            $table->index('title_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('showtimes');
    }
};
