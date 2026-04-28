<?php

namespace App\Observers;

use App\Models\Produits;
use App\Services\AdminDashboardRealtime;

class ProduitsObserver
{
    public function __construct(private AdminDashboardRealtime $realtime)
    {
    }

    public function created(Produits $produit): void
    {
        $this->realtime->publish(
            'product_created',
            'Produit ajoute avec succes.',
            [
                'product_id' => $produit->id,
                'product_name' => $produit->nom,
                'highlights' => ['stock'],
            ]
        );
    }

    public function updated(Produits $produit): void
    {
        if ($produit->wasChanged(['stock', 'statut'])) {
            $this->realtime->publish(
                'stock_updated',
                'Stock mis a jour pour ' . $produit->nom . '.',
                [
                    'product_id' => $produit->id,
                    'product_name' => $produit->nom,
                    'highlights' => ['stock', 'categories'],
                ]
            );

            return;
        }

        $this->realtime->publish(
            'product_updated',
            'Produit mis a jour avec succes.',
            [
                'product_id' => $produit->id,
                'product_name' => $produit->nom,
                'highlights' => ['stock'],
            ]
        );
    }

    public function deleted(Produits $produit): void
    {
        $this->realtime->publish(
            'product_deleted',
            'Produit supprime avec succes.',
            [
                'product_id' => $produit->id,
                'product_name' => $produit->nom,
                'highlights' => ['stock'],
            ],
            'error'
        );
    }
}
