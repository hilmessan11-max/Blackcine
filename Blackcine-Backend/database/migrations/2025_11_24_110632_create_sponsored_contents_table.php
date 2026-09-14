<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sponsored_contents', function (Blueprint $table) {
            $table->id();
            $table->string('sponsor_name');
            $table->string('campaign_name')->nullable();
            
            // Lien polymorphique vers le contenu (Article, Video, Film, etc.)
            $table->nullableMorphs('content');
            
            $table->text('contract_details')->nullable();
            $table->string('legal_mention')->default('Sponsorisé par');
            $table->string('tracking_pixel_url')->nullable();
            $table->decimal('agreed_amount', 10, 2)->nullable(); // Montant convenu
            
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            
            $table->enum('status', ['active', 'pending', 'ended'])->default('pending');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sponsored_contents');
    }
};
