<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmsTable extends Migration
{
    public function up()
    {
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->text('overview')->nullable();
            $table->string('country')->nullable();
            $table->string('language')->nullable();
            $table->json('genres')->nullable();
            $table->enum('category', ['nouveaute','a_l_affiche','a_venir','par_pays','par_langue','box_office','court_web','classiques_africains','tous'])->default('tous');
            $table->integer('year')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('films');
    }
}