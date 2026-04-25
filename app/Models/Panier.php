<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panier extends Model
{
    protected $fillable = [
        'id_user',
        'id_produit',
        'quantite',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function produit()
    {
        return $this->belongsTo(Produits::class, 'id_produit');
    }
}
