<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('films', function (Blueprint $table) {
            $table->unsignedBigInteger('poster_asset_id')->nullable()->index()->after('year');
            $table->unsignedInteger('duration_minutes')->nullable()->after('poster_asset_id');
            $table->decimal('rating', 3, 1)->nullable()->after('duration_minutes');
            $table->boolean('is_featured')->default(false)->after('rating');
        });

        Schema::table('series', function (Blueprint $table) {
            $table->string('creator')->nullable()->after('overview');
            $table->unsignedBigInteger('poster_asset_id')->nullable()->index()->after('seasons');
            $table->string('status')->nullable()->after('poster_asset_id');
            $table->date('first_air_date')->nullable()->after('status');
            $table->decimal('rating', 3, 1)->nullable()->after('first_air_date');
            $table->boolean('is_featured')->default(false)->after('rating');
        });
    }

    public function down(): void
    {
        Schema::table('films', function (Blueprint $table) {
            $table->dropColumn(['poster_asset_id', 'duration_minutes', 'rating', 'is_featured']);
        });

        Schema::table('series', function (Blueprint $table) {
            $table->dropColumn(['creator', 'poster_asset_id', 'status', 'first_air_date', 'rating', 'is_featured']);
        });
    }
};
