<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            if (! Schema::hasColumn('paiements', 'commande_id')) {
                $table->foreignId('commande_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('commandes')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('paiements', 'methode_paiement')) {
                $table->string('methode_paiement')->nullable()->after('methode_paeiment');
            }

            if (! Schema::hasColumn('paiements', 'statut')) {
                $table->string('statut')->default('en_attente')->after('methode_paiement');
            }
        });
    }

    public function down(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            if (Schema::hasColumn('paiements', 'commande_id')) {
                $table->dropConstrainedForeignId('commande_id');
            }

            if (Schema::hasColumn('paiements', 'methode_paiement')) {
                $table->dropColumn('methode_paiement');
            }

            if (Schema::hasColumn('paiements', 'statut')) {
                $table->dropColumn('statut');
            }
        });
    }
};

