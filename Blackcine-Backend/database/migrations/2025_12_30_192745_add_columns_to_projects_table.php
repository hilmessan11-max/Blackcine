<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('title');
            $table->text('pitch');
            $table->enum('category', ['film', 'serie', 'documentaire', 'animation', 'court-metrage'])->default('film');
            $table->string('director');
            $table->string('location');
            $table->enum('status', ['development', 'preproduction', 'production', 'postproduction', 'completed'])->default('development');
            $table->text('needs')->nullable();
            $table->string('thumbnail')->nullable();
            $table->integer('views_count')->default(0);
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'title', 'pitch', 'category', 'director', 'location', 
                'status', 'needs', 'thumbnail', 'views_count'
            ]);
        });
    }
};