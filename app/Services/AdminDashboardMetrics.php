<?php

namespace App\Services;

use App\Models\CommandeProduit;
use App\Models\Paiement;
use App\Models\Produits;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDashboardMetrics
{
    public function snapshot(): array
    {
        $totalUsers = User::query()->count();

        $productsSoldByCategory = CommandeProduit::query()
            ->selectRaw('COALESCE(categories.nom, ?) as categorie, SUM(commande_produits.quantite) as total_vendus', ['Sans categorie'])
            ->leftJoin('produits', 'produits.id', '=', 'commande_produits.produit_id')
            ->leftJoin('categories', 'categories.id', '=', 'produits.categorie_id')
            ->groupBy('categories.nom')
            ->orderByDesc('total_vendus')
            ->get()
            ->map(fn ($row) => [
                'categorie' => $row->categorie,
                'total_vendus' => (int) $row->total_vendus,
            ])
            ->values()
            ->all();

        $stockSummary = [
            'disponible' => Produits::query()->where('stock', '>', 10)->count(),
            'limite' => Produits::query()->whereBetween('stock', [1, 10])->count(),
            'rupture' => Produits::query()->where('stock', '<=', 0)->count(),
            'total_unites' => (int) Produits::query()->sum('stock'),
        ];

        $rawOrderStatuses = Paiement::query()
            ->select('statut', DB::raw('COUNT(*) as total'))
            ->groupBy('statut')
            ->pluck('total', 'statut');

        $orderStatusCards = [
            [
                'key' => 'en_attente',
                'label' => 'Commandes en attente',
                'value' => (int) ($rawOrderStatuses['en_attente'] ?? 0),
                'tone' => 'sand',
            ],
            [
                'key' => 'en_cours',
                'label' => 'Commandes en cours',
                'value' => (int) ($rawOrderStatuses['en_cours'] ?? 0),
                'tone' => 'blue',
            ],
            [
                'key' => 'validees',
                'label' => 'Commandes validees',
                'value' => (int) (($rawOrderStatuses['valide'] ?? 0) + ($rawOrderStatuses['validee'] ?? 0)),
                'tone' => 'green',
            ],
            [
                'key' => 'annulees',
                'label' => 'Commandes annulees',
                'value' => (int) (($rawOrderStatuses['annule'] ?? 0) + ($rawOrderStatuses['annulee'] ?? 0)),
                'tone' => 'red',
            ],
        ];

        return [
            'totalUsers' => $totalUsers,
            'productsSoldByCategory' => $productsSoldByCategory,
            'stockSummary' => $stockSummary,
            'orderStatusCards' => $orderStatusCards,
        ];
    }
}
