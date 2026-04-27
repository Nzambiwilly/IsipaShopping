<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\CommandeProduit;
use App\Models\Paiement;
use App\Models\Produits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        $items = $this->buildCartItems($request);
        $total = $items->sum('sous_total');

        return view('client.checkout', [
            'items' => $items,
            'total' => $total,
            'adresse' => old('adresse_livraison', $request->user()->adresse_de_livraison),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'adresse_livraison' => ['required', 'string', 'min:10', 'max:255'],
            'date_livraison' => ['nullable', 'date', 'after:today'],
            'methode_paiement' => ['required', 'in:mobile_money,carte,especes'],
        ]);

        $items = $this->buildCartItems($request);
        if ($items->isEmpty()) {
            return redirect()->route('panier.index')
                ->withErrors(['panier' => 'Votre panier est vide.']);
        }

        DB::transaction(function () use ($request, $validated, $items): void {
            $commande = Commande::query()->create([
                'user_id' => $request->user()->id,
                'adresse_livraison' => $validated['adresse_livraison'],
                'date_livraison' => $validated['date_livraison'] ?? now()->addDays(3)->toDateString(),
                'date_commande' => now()->toDateString(),
            ]);

            $total = 0;

            foreach ($items as $item) {
                $produit = $item['produit'];
                $quantite = $item['quantite'];
                $prix = (float) $produit->prix_unitaire;
                $sousTotal = $prix * $quantite;
                $total += $sousTotal;

                CommandeProduit::query()->create([
                    'commande_id' => $commande->id,
                    'produit_id' => $produit->id,
                    'quantite' => $quantite,
                    'prix_unitaire' => $prix,
                ]);

                $produit->decrement('stock', $quantite);
                if ($produit->stock <= 0) {
                    $produit->update(['statut' => 'rupture']);
                }
            }

            Paiement::query()->create([
                'commande_id' => $commande->id,
                'montant' => $total,
                'methode_paeiment' => $validated['methode_paiement'],
                'methode_paiement' => $validated['methode_paiement'],
                'statut' => 'valide',
            ]);

            $request->user()->update([
                'adresse_de_livraison' => $validated['adresse_livraison'],
            ]);
        });

        $request->session()->forget('cart_' . $request->user()->id);

        return redirect()->route('catalogue')
            ->with('success', 'Commande validee avec succes.');
    }

    private function buildCartItems(Request $request)
    {
        $cart = PanierController::getCartStatic($request);
        $produits = Produits::query()
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        return collect($cart)->map(function (int $quantite, int $produitId) use ($produits) {
            $produit = $produits->get($produitId);
            if (! $produit) {
                return null;
            }

            $quantiteFinale = max(1, min($quantite, $produit->stock));

            return [
                'produit' => $produit,
                'quantite' => $quantiteFinale,
                'sous_total' => ((float) $produit->prix_unitaire) * $quantiteFinale,
            ];
        })->filter()->values();
    }
}

