<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Produits extends Model
{
    protected $table = 'produits';

    protected $fillable = [
        'nom',
        'categorie_id',
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

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        return asset('storage/' . ltrim($this->image, '/'));
    }
}
