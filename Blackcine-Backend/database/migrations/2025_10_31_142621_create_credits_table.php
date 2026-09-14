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
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('title_id');
            $table->unsignedBigInteger('person_id');
            $table->string('department')->nullable(); // Acting, Directing, Writing
            $table->string('job')->nullable(); // Director, Writer
            $table->string('character_name')->nullable();
            $table->unsignedInteger('credit_order')->default(0);
            $table->timestamps();
            $table->index(['title_id', 'department', 'job']);
            $table->index('person_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};
