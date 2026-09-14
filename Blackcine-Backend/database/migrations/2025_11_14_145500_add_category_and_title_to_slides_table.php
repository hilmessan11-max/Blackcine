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
        Schema::table('slides', function (Blueprint $table) {
            $table->enum('category', ['film', 'series', 'general'])->default('general')->after('title');
            $table->foreignId('title_id')->nullable()->after('category')->constrained('titles')->onDelete('cascade');
            $table->index(['category', 'is_active', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slides', function (Blueprint $table) {
            $table->dropForeign(['title_id']);
            $table->dropIndex(['category', 'is_active', 'display_order']);
            $table->dropColumn(['category', 'title_id']);
        });
    }
};
