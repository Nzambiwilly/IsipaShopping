<?php

namespace App\Http\Controllers;

use App\Models\Produits;
use Illuminate\Http\Request;

class PanierController extends Controller
{
    public function index(Request $request)
    {
        $cart = $this->getCart($request);
        $produits = Produits::query()
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $items = collect($cart)->map(function (int $quantite, int $produitId) use ($produits) {
            $produit = $produits->get($produitId);

            if (! $produit) {
                return null;
            }

            $sousTotal = ((float) $produit->prix_unitaire) * $quantite;

            return [
                'produit' => $produit,
                'quantite' => $quantite,
                'sous_total' => $sousTotal,
            ];
        })->filter()->values();

        $total = $items->sum('sous_total');

        return view('client.panier', [
            'items' => $items,
            'total' => $total,
        ]);
    }

    public function add(Request $request, Produits $produit)
    {
        $validated = $request->validate([
            'quantite' => ['nullable', 'integer', 'min:1'],
        ]);

        $quantite = (int) ($validated['quantite'] ?? 1);

        $cart = $this->getCart($request);
        $cart[$produit->id] = min($produit->stock, ($cart[$produit->id] ?? 0) + $quantite);

        $this->putCart($request, $cart);

        return redirect()->route('panier.index')->with('success', 'Produit ajoute au panier.');
    }

    public function update(Request $request, Produits $produit)
    {
        $validated = $request->validate([
            'quantite' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $this->getCart($request);
        $cart[$produit->id] = min($produit->stock, (int) $validated['quantite']);
        $this->putCart($request, $cart);

        return redirect()->route('panier.index')->with('success', 'Quantite mise a jour.');
    }

    public function remove(Request $request, Produits $produit)
    {
        $cart = $this->getCart($request);
        unset($cart[$produit->id]);
        $this->putCart($request, $cart);

        return redirect()->route('panier.index')->with('success', 'Produit retire du panier.');
    }

    public static function cartCount(Request $request): int
    {
        return array_sum(self::getCartStatic($request));
    }

    public static function getCartStatic(Request $request): array
    {
        $user = $request->user();
        if (! $user) {
            return [];
        }

        return (array) $request->session()->get('cart_' . $user->id, []);
    }

    private function getCart(Request $request): array
    {
        return self::getCartStatic($request);
    }

    private function putCart(Request $request, array $cart): void
    {
        $request->session()->put('cart_' . $request->user()->id, $cart);
    }
}
