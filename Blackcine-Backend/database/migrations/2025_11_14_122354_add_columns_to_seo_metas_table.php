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
        Schema::table('seo_metas', function (Blueprint $table) {
            // Colonnes pour la relation polymorphique
            $table->string('seoable_type');
            $table->unsignedBigInteger('seoable_id');
            $table->index(['seoable_type', 'seoable_id']);
            
            // Métadonnées SEO
            $table->string('meta_title', 60)->nullable();
            $table->string('meta_description', 160)->nullable();
            $table->string('meta_keywords')->nullable();
            
            // Open Graph
            $table->string('og_title', 60)->nullable();
            $table->string('og_description', 160)->nullable();
            $table->foreignId('og_image_asset_id')->nullable()->constrained('assets')->onDelete('set null');
            
            // URL canonique
            $table->string('canonical_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seo_metas', function (Blueprint $table) {
            $table->dropForeign(['og_image_asset_id']);
            $table->dropIndex(['seoable_type', 'seoable_id']);
            $table->dropColumn([
                'seoable_type', 'seoable_id', 'meta_title', 'meta_description', 
                'meta_keywords', 'og_title', 'og_description', 'og_image_asset_id', 'canonical_url'
            ]);
        });
    }
};
