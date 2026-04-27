<?php

namespace Database\Seeders;

use App\Models\Produits;
use Illuminate\Database\Seeder;

class ProduitSeeder extends Seeder
{
    public function run(): void
    {
        $produits = [
            [
                'nom' => 'Laptop Pro 14',
                'description' => 'Ordinateur portable professionnel 14 pouces avec clavier retroeclaire.',
                'prix_unitaire' => 899.99,
                'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?auto=format&fit=crop&w=1200&q=80',
                'stock' => 26,
                'date_fabrication' => now()->subMonths(2)->toDateString(),
                'statut' => 'disponible',
                'date_ajout' => now()->toDateString(),
            ],
            [
                'nom' => 'Mechanical Keyboard K1',
                'description' => 'Clavier mecanique compact pour developpeurs et gamers.',
                'prix_unitaire' => 129.00,
                'image' => 'https://images.unsplash.com/photo-1541140532154-b024d705b90a?auto=format&fit=crop&w=1200&q=80',
                'stock' => 40,
                'date_fabrication' => now()->subMonths(1)->toDateString(),
                'statut' => 'disponible',
                'date_ajout' => now()->toDateString(),
            ],
            [
                'nom' => 'Wireless Mouse M2',
                'description' => 'Souris ergonomique sans fil pour bureautique et production.',
                'prix_unitaire' => 39.90,
                'image' => 'https://images.unsplash.com/photo-1527814050087-3793815479db?auto=format&fit=crop&w=1200&q=80',
                'stock' => 75,
                'date_fabrication' => now()->subWeeks(3)->toDateString(),
                'statut' => 'disponible',
                'date_ajout' => now()->toDateString(),
            ],
            [
                'nom' => '27" Monitor Vision',
                'description' => 'Ecran Full HD 27 pouces, couleur precise et faible latence.',
                'prix_unitaire' => 249.00,
                'image' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&w=1200&q=80',
                'stock' => 18,
                'date_fabrication' => now()->subMonths(4)->toDateString(),
                'statut' => 'disponible',
                'date_ajout' => now()->toDateString(),
            ],
            [
                'nom' => 'Office Headset H5',
                'description' => 'Casque audio avec microphone antibruit pour appels et reunions.',
                'prix_unitaire' => 89.50,
                'image' => 'https://images.unsplash.com/photo-1585298723682-7115561c51b7?auto=format&fit=crop&w=1200&q=80',
                'stock' => 33,
                'date_fabrication' => now()->subMonths(2)->toDateString(),
                'statut' => 'disponible',
                'date_ajout' => now()->toDateString(),
            ],
            [
                'nom' => 'Docking Station D4',
                'description' => 'Station d accueil USB-C multiports pour postes de travail modernes.',
                'prix_unitaire' => 159.00,
                'image' => 'https://images.unsplash.com/photo-1625842268584-8f3296236761?auto=format&fit=crop&w=1200&q=80',
                'stock' => 21,
                'date_fabrication' => now()->subMonths(1)->toDateString(),
                'statut' => 'disponible',
                'date_ajout' => now()->toDateString(),
            ],
        ];

        foreach ($produits as $produit) {
            Produits::query()->updateOrCreate(
                ['nom' => $produit['nom']],
                $produit
            );
        }
    }
}
