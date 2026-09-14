<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('festivals', function (Blueprint $table) {
            $table->string('name');
            $table->string('slug')->unique();
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->string('city')->nullable();
            $table->string('country', 2)->nullable();
            $table->text('description')->nullable();
            $table->string('website_url')->nullable();
            $table->string('status')->default('draft');
        });
    }

    public function down(): void
    {
        Schema::table('festivals', function (Blueprint $table) {
            $table->dropColumn([
                'name', 'slug', 'starts_at', 'ends_at', 'city', 'country',
                'description', 'website_url', 'status',
            ]);
        });
    }
};
