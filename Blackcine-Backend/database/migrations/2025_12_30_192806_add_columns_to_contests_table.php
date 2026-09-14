<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('contests', function (Blueprint $table) {
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['prix', 'bourse', 'festival', 'incubateur'])->default('prix');
            $table->string('prize')->nullable();
            $table->date('deadline');
            $table->text('requirements')->nullable();
            $table->string('location')->nullable();
            $table->enum('status', ['open', 'closed', 'judging', 'completed'])->default('open');
            $table->integer('participants_count')->default(0);
            $table->string('organizer')->nullable();
        });
    }

    public function down()
    {
        Schema::table('contests', function (Blueprint $table) {
            $table->dropColumn([
                'title', 'description', 'type', 'prize', 'deadline', 
                'requirements', 'location', 'status', 'participants_count', 'organizer'
            ]);
        });
    }
};