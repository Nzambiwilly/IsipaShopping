<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Produits;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
    public function index(Request $request)
    {
        $query = Produits::query()->with('categorie');

        $search = trim((string) $request->input('q', ''));
        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('nom', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $sort = $request->input('sort', 'recent');
        if ($sort === 'price_asc') {
            $query->orderBy('prix_unitaire');
        } elseif ($sort === 'price_desc') {
            $query->orderByDesc('prix_unitaire');
        } elseif ($sort === 'name_asc') {
            $query->orderBy('nom');
        } else {
            $query->latest();
        }

        $produits = $query->paginate(9)->withQueryString();

        return view('visiteur.catalogue', [
            'produits' => $produits,
            'search' => $search,
            'sort' => $sort,
        ]);
    }
}
