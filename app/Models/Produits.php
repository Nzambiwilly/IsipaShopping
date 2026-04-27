<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produits extends Model
{
    protected $table = 'produits';

    protected $fillable = [
        'nom',
        'description',
        'prix_unitaire',
        'image',
        'stock',
        'date_fabrication',
        'statut',
        'date_ajout',
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'date_fabrication' => 'date',
        'date_ajout' => 'date',
    ];
}
