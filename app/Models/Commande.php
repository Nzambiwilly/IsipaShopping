<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $table = 'commandes';

    protected $fillable = [
        'user_id',
        'adresse_livraison',
        'date_livraison',
        'date_commande',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lignes()
    {
        return $this->hasMany(CommandeProduit::class, 'commande_id');
    }

    public function paiement()
    {
        return $this->hasOne(Paiement::class, 'commande_id');
    }
}
