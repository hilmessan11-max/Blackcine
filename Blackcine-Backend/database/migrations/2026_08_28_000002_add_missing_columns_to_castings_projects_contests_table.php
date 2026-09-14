<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('castings', function (Blueprint $table) {
            if (!Schema::hasColumn('castings', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            if (!Schema::hasColumn('castings', 'slug')) {
                $table->string('slug')->nullable();
            }
            if (!Schema::hasColumn('castings', 'role')) {
                $table->string('role')->nullable();
            }
            if (!Schema::hasColumn('castings', 'country')) {
                $table->string('country')->nullable();
            }
            if (!Schema::hasColumn('castings', 'city')) {
                $table->string('city')->nullable();
            }
        });

        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            if (!Schema::hasColumn('projects', 'slug')) {
                $table->string('slug')->nullable();
            }
            if (!Schema::hasColumn('projects', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('projects', 'project_type')) {
                $table->string('project_type')->nullable();
            }
            if (!Schema::hasColumn('projects', 'country')) {
                $table->string('country')->nullable();
            }
            if (!Schema::hasColumn('projects', 'city')) {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('projects', 'end_date')) {
                $table->timestamp('end_date')->nullable();
            }
        });

        Schema::table('contests', function (Blueprint $table) {
            if (!Schema::hasColumn('contests', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            if (!Schema::hasColumn('contests', 'slug')) {
                $table->string('slug')->nullable();
            }
            if (!Schema::hasColumn('contests', 'contest_type')) {
                $table->string('contest_type')->nullable();
            }
            if (!Schema::hasColumn('contests', 'registration_deadline')) {
                $table->timestamp('registration_deadline')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('castings', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'slug', 'role', 'country', 'city']);
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'slug', 'description', 'project_type', 'country', 'city', 'end_date']);
        });
        Schema::table('contests', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'slug', 'contest_type', 'registration_deadline']);
        });
    }
};
