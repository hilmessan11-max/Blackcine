<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            return;
        }

        try {
            DB::statement('ALTER TABLE titles ADD FULLTEXT titles_fulltext (name, synopsis)');
        } catch (\Throwable $e) {
            // Index déjà existant ou moteur non supporté
        }

        try {
            // Colonnes réelles: title, excerpt, body (pas de `content`)
            DB::statement('ALTER TABLE articles ADD FULLTEXT articles_fulltext (title, excerpt, body)');
        } catch (\Throwable $e) {
        }

        // Index simples pour fallback LIKE et filtres
        try {
            Schema::table('titles', function ($table) {
                $table->index(['type', 'status']);
                $table->index('origin_country');
            });
        } catch (\Throwable $e) {}

        try {
            Schema::table('articles', function ($table) {
                $table->index('status');
            });
        } catch (\Throwable $e) {}
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            return;
        }
        try { DB::statement('ALTER TABLE titles DROP INDEX titles_fulltext'); } catch (\Throwable $e) {}
        try { DB::statement('ALTER TABLE articles DROP INDEX articles_fulltext'); } catch (\Throwable $e) {}
    }
};
