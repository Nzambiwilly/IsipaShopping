<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'nom' => 'Ordinateurs',
                'image' => 'categories/ordinateurs.jpg',
                'description' => 'Laptops et postes de travail.',
            ],
            [
                'nom' => 'Peripheriques',
                'image' => 'categories/peripheriques.jpg',
                'description' => 'Claviers, souris, casques et accessoires.',
            ],
            [
                'nom' => 'Affichage',
                'image' => 'categories/affichage.jpg',
                'description' => 'Ecrans et solutions d affichage.',
            ],
        ];

        foreach ($categories as $categorie) {
            Categorie::query()->updateOrCreate(
                ['nom' => $categorie['nom']],
                $categorie
            );
        }
    }
}

