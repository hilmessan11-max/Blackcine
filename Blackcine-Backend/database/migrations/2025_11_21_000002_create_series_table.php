<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeriesTable extends Migration
{
    public function up()
    {
        Schema::create('series', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->text('overview')->nullable();
            $table->string('country')->nullable();
            $table->string('language')->nullable();
            $table->json('genres')->nullable();
            $table->enum('category', ['nouveaute','a_l_affiche','a_venir','en_cours','par_pays','par_genre','cultes_africaines','toutes'])->default('toutes');
            $table->integer('seasons')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('series');
    }
}