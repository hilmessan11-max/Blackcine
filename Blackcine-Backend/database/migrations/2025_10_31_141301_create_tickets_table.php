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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('showtime_id');
            $table->string('holder_name')->nullable();
            $table->string('seat_label')->nullable();
            $table->unsignedInteger('price_cents')->default(0);
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->string('qr_code')->unique();
            $table->string('status')->default('valid'); // valid, used, refunded, cancelled
            $table->timestamps();
            $table->index(['order_id', 'showtime_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
