<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommandeProduit extends Model
{
    protected $table = 'commande_produit';

    protected $fillable = [
        'id_commande',
        'id_produit',
        'quantite',
        'prix_unitaire',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class, 'id_commande');
    }

    public function produit()
    {
        return $this->belongsTo(Produits::class, 'id_produit');
    }
}
