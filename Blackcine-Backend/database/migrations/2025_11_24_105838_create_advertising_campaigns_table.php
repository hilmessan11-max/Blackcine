<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertising_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('client_name')->nullable();
            $table->enum('type', ['banner', 'video', 'sponsored_post'])->default('banner');
            $table->string('placement')->nullable()->comment('home_top, sidebar, footer, etc.');
            $table->string('image_url')->nullable();
            $table->string('video_url')->nullable();
            $table->string('target_url')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'paused', 'ended', 'draft'])->default('draft');
            
            // Statistiques simples
            $table->unsignedBigInteger('impressions_count')->default(0);
            $table->unsignedBigInteger('clicks_count')->default(0);
            
            // Finance
            $table->integer('price_cents')->default(0);
            $table->string('currency')->default('EUR');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertising_campaigns');
    }
};
