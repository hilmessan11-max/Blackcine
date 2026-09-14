<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('push_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->string('image_url')->nullable();
            $table->string('target_url')->nullable();
            
            $table->enum('target_audience', ['all', 'subscribers', 'free_users', 'specific_users'])->default('all');
            $table->json('target_criteria')->nullable(); // Pour ciblage avancé (ex: pays, langue)
            
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            
            $table->enum('status', ['draft', 'scheduled', 'processing', 'sent', 'failed'])->default('draft');
            
            $table->integer('success_count')->default(0);
            $table->integer('failure_count')->default(0);
            
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('push_notifications');
    }
};
