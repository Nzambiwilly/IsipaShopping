<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
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
        $selectedIds = $this->extractSelectedIds($request);
        if ($selectedIds === []) {
            return redirect()->route('panier.index')
                ->withErrors(['selection' => 'Selectionnez au moins un article a payer.']);
        }

        $items = $this->buildCartItems($request, $selectedIds);
        if ($items->isEmpty()) {
            return redirect()->route('panier.index')
                ->withErrors(['selection' => 'Les articles selectionnes ne sont plus disponibles.']);
        }

        $total = $items->sum('sous_total');

        return view('client.checkout', [
            'items' => $items,
            'total' => $total,
            'adresse' => old('adresse_livraison', $request->user()->adresse_de_livraison),
            'selectedItems' => $selectedIds,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'adresse_livraison' => ['required', 'string', 'min:10', 'max:255'],
            'date_livraison' => ['nullable', 'date', 'after:today'],
            'methode_paiement' => ['required', 'in:mobile_money,carte,especes'],
            'selected_items' => ['required', 'array', 'min:1'],
            'selected_items.*' => ['integer'],
        ]);

        $selectedIds = collect($validated['selected_items'])
            ->map(fn ($id) => (int) $id)
            ->filter(fn (int $id) => $id > 0)
            ->unique()
            ->values()
            ->all();

        $items = $this->buildCartItems($request, $selectedIds);
        if ($items->isEmpty()) {
            return redirect()->route('panier.index')
                ->withErrors(['selection' => 'Les articles selectionnes ne sont plus disponibles.']);
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

        $cart = CartController::getCartStatic($request);
        foreach ($selectedIds as $produitId) {
            unset($cart[$produitId]);
        }
        $request->session()->put('cart_' . $request->user()->id, $cart);

        return redirect()->route('catalogue')
            ->with('success', 'Commande validee avec succes.');
    }

    private function buildCartItems(Request $request, array $selectedIds = [])
    {
        $cart = CartController::getCartStatic($request);
        if ($selectedIds !== []) {
            $cart = array_intersect_key($cart, array_flip($selectedIds));
        }

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

    private function extractSelectedIds(Request $request): array
    {
        return collect((array) $request->query('selected_items', []))
            ->map(fn ($id) => (int) $id)
            ->filter(fn (int $id) => $id > 0)
            ->unique()
            ->values()
            ->all();
    }
}
