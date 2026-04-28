<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $table = 'paiements';

    protected $fillable = [
        'commande_id',
        'montant',
        'methode_paeiment',
        'methode_paiement',
        'statut',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class, 'commande_id');
    }
}
