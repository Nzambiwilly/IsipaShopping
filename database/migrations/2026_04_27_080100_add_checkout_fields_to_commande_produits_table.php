<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commande_produits', function (Blueprint $table) {
            if (! Schema::hasColumn('commande_produits', 'produit_id')) {
                $table->foreignId('produit_id')
                    ->nullable()
                    ->after('commande_id')
                    ->constrained('produits')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('commande_produits', 'quantite')) {
                $table->unsignedInteger('quantite')->default(1)->after('produit_id');
            }

            if (! Schema::hasColumn('commande_produits', 'prix_unitaire')) {
                $table->decimal('prix_unitaire', 10, 2)->default(0)->after('quantite');
            }
        });
    }

    public function down(): void
    {
        Schema::table('commande_produits', function (Blueprint $table) {
            if (Schema::hasColumn('commande_produits', 'produit_id')) {
                $table->dropConstrainedForeignId('produit_id');
            }

            if (Schema::hasColumn('commande_produits', 'quantite')) {
                $table->dropColumn('quantite');
            }

            if (Schema::hasColumn('commande_produits', 'prix_unitaire')) {
                $table->dropColumn('prix_unitaire');
            }
        });
    }
};

