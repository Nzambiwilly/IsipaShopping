<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduitPanier extends Model
{
    protected $table = 'produit_panier';

    protected $fillable = [
        'id_panier',
        'id_produit',
        'stock',
    ];

    public function panier()
    {
        return $this->belongsTo(Panier::class, 'id_panier');
}

    public function produit()
    {
        return $this->belongsTo(Produits::class, 'id_produit');
    }
}
