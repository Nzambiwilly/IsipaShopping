<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Produits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProduitController extends Controller
{
    public function index()
    {
        return view('admin.produits.index', [
            'produits' => Produits::query()->with('categorie')->latest()->paginate(12),
        ]);
    }

    public function create()
    {
        return view('admin.produits.form', [
            'produit' => new Produits(),
            'categories' => Categorie::query()->orderBy('nom')->get(),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatePayload($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('produits', 'public');
        }
        $path_to_store_in_database = 'storage/' . $validated['image'];
        $validated['image'] = $path_to_store_in_database;

        $validated['date_ajout'] = now()->toDateString();

        Produits::query()->create($validated);

        return redirect()->route('admin.produits.index')
            ->with('success', 'Produit ajoute avec succes.');
    }

    public function edit(Produits $produit)
    {
        return view('admin.produits.form', [
            'produit' => $produit,
            'categories' => Categorie::query()->orderBy('nom')->get(),
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Produits $produit)
    {
        $validated = $this->validatePayload($request);

        if ($request->hasFile('image')) {
            if ($produit->image && ! str_starts_with($produit->image, 'http')) {
                Storage::disk('public')->delete($produit->image);
            }
            $validated['image'] = $request->file('image')->store('produits', 'public');
            $validated['image'] = 'storage/' . $validated['image'];
        }

        $produit->update($validated);

        return redirect()->route('admin.produits.index')
            ->with('success', 'Produit mis a jour avec succes.');
    }

    public function destroy(Produits $produit)
    {
        if ($produit->image && Storage::disk('public')->exists($produit->image)) {
            Storage::disk('public')->delete($produit->image);
        }

        $produit->delete();

        return redirect()->route('admin.produits.index')
            ->with('success', 'Produit supprime avec succes.');
    }

    private function validatePayload(Request $request): array
    {
        return $request->validate([
            'nom' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:3000'],
            'prix_unitaire' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'categorie_id' => ['required', 'exists:categories,id'],
            'date_fabrication' => ['required', 'date'],
            'statut' => ['required', 'in:disponible,rupture,archive'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);
    }
}
