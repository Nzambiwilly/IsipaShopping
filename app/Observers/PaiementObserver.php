<?php

namespace App\Observers;

use App\Models\Paiement;
use App\Services\AdminDashboardRealtime;

class PaiementObserver
{
    public function __construct(private AdminDashboardRealtime $realtime)
    {
    }

    public function created(Paiement $paiement): void
    {
        $commandeId = $paiement->commande_id ? '#' . $paiement->commande_id : '';

        $this->realtime->publish(
            'payment_received',
            trim('Nouveau paiement recu ' . $commandeId . ' !'),
            [
                'commande_id' => $paiement->commande_id,
                'status' => $paiement->statut,
                'highlights' => ['orders', 'stock', 'categories'],
            ]
        );
    }

    public function updated(Paiement $paiement): void
    {
        if (! $paiement->wasChanged('statut')) {
            return;
        }

        $label = match ($paiement->statut) {
            'en_attente' => 'en attente',
            'en_cours' => 'en cours',
            'valide', 'validee' => 'validee',
            'annule', 'annulee' => 'annulee',
            default => $paiement->statut,
        };

        $this->realtime->publish(
            'order_status_changed',
            'Commande #' . $paiement->commande_id . ' ' . $label . '.',
            [
                'commande_id' => $paiement->commande_id,
                'status' => $paiement->statut,
                'highlights' => ['orders'],
            ],
            in_array($paiement->statut, ['annule', 'annulee'], true) ? 'error' : 'success'
        );
    }
}
