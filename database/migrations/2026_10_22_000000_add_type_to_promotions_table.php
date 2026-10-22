<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ajoute la colonne 'type' à la table promotions pour distinguer promotion et événement
     */
    public function up(): void
    {
        // Vérifie si la colonne type existe déjà pour éviter les erreurs
        if (!Schema::hasColumn('promotions', 'type')) {
            Schema::table('promotions', function (Blueprint $table) {
                $table->enum("type", ["promotion", "event"])->default("promotion")->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('promotions', 'type')) {
            Schema::table('promotions', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }
};
