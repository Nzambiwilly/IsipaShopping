<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
        ]);

        User::query()->updateOrCreate(
            ['email' => 'admin@isipa.cd'],
            [
                'nom_complet' => 'Administrateur ISIPA',
                'password' => 'password123',
                'role' => Role::SUPER_ADMIN,
                'permission' => 'all',
            ]
        );

        $this->call([
            CategorieSeeder::class,
            ProduitSeeder::class,
        ]);
    }
}
