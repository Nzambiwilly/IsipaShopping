<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $fillable = [
        'nom',
        'image',
        'description',
    ];

    public function produits()
    {
        return $this->hasMany(Produits::class, 'id_categorie');
    }
}
