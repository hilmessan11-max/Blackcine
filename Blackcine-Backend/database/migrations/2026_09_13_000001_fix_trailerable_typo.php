<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite ne supporte pas renameColumn directement pour colonnes avec index,
        // on utilise une approche compatible : recréation si nécessaire
        if (Schema::hasColumn('trailers', 'trailarable_id')) {
            // Pour MySQL/Postgres : rename direct
            try {
                Schema::table('trailers', function (Blueprint $table) {
                    $table->renameColumn('trailarable_id', 'trailerable_id');
                });
            } catch (\Exception $e) {
                // Fallback SQLite : ajouter nouvelle colonne, copier, supprimer ancienne
                Schema::table('trailers', function (Blueprint $table) {
                    $table->unsignedBigInteger('trailerable_id')->nullable()->after('id');
                    $table->string('trailerable_type')->nullable()->after('trailerable_id');
                });
                DB::statement('UPDATE trailers SET trailerable_id = trailarable_id, trailerable_type = trailarable_type');
                // On garde les anciennes colonnes pour compatibilité, elles seront supprimées plus tard
            }
        }

        if (Schema::hasColumn('trailers', 'trailarable_type')) {
            try {
                Schema::table('trailers', function (Blueprint $table) {
                    $table->renameColumn('trailarable_type', 'trailerable_type');
                });
            } catch (\Exception $e) {
                // Déjà géré ci-dessus
            }
        }

        // Ajouter index sur nouvelles colonnes si pas déjà
        try {
            Schema::table('trailers', function (Blueprint $table) {
                $table->index(['trailerable_id', 'trailerable_type']);
            });
        } catch (\Exception $e) {
            // Index existe déjà
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('trailers', 'trailerable_id')) {
            try {
                Schema::table('trailers', function (Blueprint $table) {
                    $table->renameColumn('trailerable_id', 'trailarable_id');
                });
            } catch (\Exception $e) {}
        }
        if (Schema::hasColumn('trailers', 'trailerable_type')) {
            try {
                Schema::table('trailers', function (Blueprint $table) {
                    $table->renameColumn('trailerable_type', 'trailarable_type');
                });
            } catch (\Exception $e) {}
        }
    }
};
