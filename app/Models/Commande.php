<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $fillable = [
        'id_client',
        'date_commande',
        'total',
        'statut',
    ];

    public function client()
    {
        return $this->belongsTo(user::class, 'user_id');
    }

    public function produits()
    {
        return $this->belongsToMany(Produits::class, 'commande_produit', 'id_commande', 'id_produit')
                    ->withPivot('quantite', 'prix_unitaire');
    }
}
