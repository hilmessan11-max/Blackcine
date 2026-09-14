<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliate_programs', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ex: Amazon, Fnac
            $table->string('slug')->unique();
            $table->string('affiliate_id')->nullable(); // Votre ID affilié
            $table->string('base_url')->nullable(); // URL de base du programme
            $table->decimal('commission_rate', 5, 2)->nullable(); // Pourcentage
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            
            // Stats simples
            $table->unsignedBigInteger('clicks_count')->default(0);
            $table->decimal('total_earnings', 10, 2)->default(0);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_programs');
    }
};
