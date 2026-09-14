<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('castings', function (Blueprint $table) {
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['film', 'serie', 'pub', 'emission'])->default('film');
            $table->string('location');
            $table->date('shooting_start')->nullable();
            $table->date('shooting_end')->nullable();
            $table->string('compensation')->nullable();
            $table->integer('roles_count')->default(1);
            $table->text('requirements')->nullable();
            $table->date('deadline');
            $table->boolean('is_urgent')->default(false);
            $table->enum('status', ['open', 'closed', 'in_progress'])->default('open');
            $table->integer('applications_count')->default(0);
        });
    }

    public function down()
    {
        Schema::table('castings', function (Blueprint $table) {
            $table->dropColumn([
                'title', 'description', 'type', 'location', 'shooting_start', 
                'shooting_end', 'compensation', 'roles_count', 'requirements', 
                'deadline', 'is_urgent', 'status', 'applications_count'
            ]);
        });
    }
};