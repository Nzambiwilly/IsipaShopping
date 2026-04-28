<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\DashboardController;
use App\Models\Categorie;
use App\Models\Commande;
use App\Models\CommandeProduit;
use App\Models\Paiement;
use App\Models\Produits;
use App\Models\Role;
use App\Models\User;
use App\Services\AdminDashboardRealtime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_exposes_expected_metrics(): void
    {
        $admin = User::factory()->create([
            'role' => Role::ADMIN,
            'permission' => 'scoped',
        ]);

        User::factory()->count(2)->create();

        $categorieA = Categorie::query()->create([
            'nom' => 'Electronique',
            'image' => 'categories/electronique.jpg',
        ]);

        $categorieB = Categorie::query()->create([
            'nom' => 'Maison',
            'image' => 'categories/maison.jpg',
        ]);

        $produitA = Produits::query()->create([
            'nom' => 'Laptop',
            'categorie_id' => $categorieA->id,
            'prix_unitaire' => 1200,
            'stock' => 18,
            'date_fabrication' => now()->subMonth()->toDateString(),
            'statut' => 'disponible',
            'date_ajout' => now()->toDateString(),
        ]);

        $produitB = Produits::query()->create([
            'nom' => 'Mixeur',
            'categorie_id' => $categorieB->id,
            'prix_unitaire' => 80,
            'stock' => 4,
            'date_fabrication' => now()->subWeeks(2)->toDateString(),
            'statut' => 'disponible',
            'date_ajout' => now()->toDateString(),
        ]);

        $produitC = Produits::query()->create([
            'nom' => 'Clavier',
            'categorie_id' => $categorieA->id,
            'prix_unitaire' => 50,
            'stock' => 0,
            'date_fabrication' => now()->subWeeks(3)->toDateString(),
            'statut' => 'rupture',
            'date_ajout' => now()->toDateString(),
        ]);

        $commandeA = Commande::query()->create([
            'user_id' => $admin->id,
            'adresse_livraison' => 'Kinshasa centre, avenue test 10',
            'date_livraison' => now()->addDays(2)->toDateString(),
            'date_commande' => now()->toDateString(),
        ]);

        $commandeB = Commande::query()->create([
            'user_id' => $admin->id,
            'adresse_livraison' => 'Kinshasa centre, avenue test 11',
            'date_livraison' => now()->addDays(4)->toDateString(),
            'date_commande' => now()->toDateString(),
        ]);

        CommandeProduit::query()->create([
            'commande_id' => $commandeA->id,
            'produit_id' => $produitA->id,
            'quantite' => 3,
            'prix_unitaire' => 1200,
        ]);

        CommandeProduit::query()->create([
            'commande_id' => $commandeA->id,
            'produit_id' => $produitB->id,
            'quantite' => 2,
            'prix_unitaire' => 80,
        ]);

        CommandeProduit::query()->create([
            'commande_id' => $commandeB->id,
            'produit_id' => $produitC->id,
            'quantite' => 1,
            'prix_unitaire' => 50,
        ]);

        Paiement::query()->create([
            'commande_id' => $commandeA->id,
            'montant' => 3760,
            'methode_paeiment' => 'mobile_money',
            'methode_paiement' => 'mobile_money',
            'statut' => 'valide',
        ]);

        Paiement::query()->create([
            'commande_id' => $commandeB->id,
            'montant' => 50,
            'methode_paeiment' => 'carte',
            'methode_paiement' => 'carte',
            'statut' => 'en_attente',
        ]);

        Paiement::query()->create([
            'commande_id' => null,
            'montant' => 100,
            'methode_paeiment' => 'carte',
            'methode_paiement' => 'carte',
            'statut' => 'annulee',
        ]);

        $this->actingAs($admin);

        $response = app()->call([app(DashboardController::class), 'index']);
        $data = $response->getData();

        $this->assertSame('admin.dashboard', $response->name());
        $this->assertSame(3, $data['totalUsers']);
        $this->assertSame([
            'disponible' => 1,
            'limite' => 1,
            'rupture' => 1,
            'total_unites' => 22,
        ], $data['stockSummary']);

        $statusValues = collect($data['orderStatusCards'])->pluck('value', 'label')->all();
        $this->assertSame(1, $statusValues['Commandes en attente']);
        $this->assertSame(0, $statusValues['Commandes en cours']);
        $this->assertSame(1, $statusValues['Commandes validees']);
        $this->assertSame(1, $statusValues['Commandes annulees']);

        $categorySales = collect($data['productsSoldByCategory'])
            ->pluck('total_vendus', 'categorie')
            ->map(fn ($value) => (int) $value)
            ->all();

        $this->assertSame(4, $categorySales['Electronique']);
        $this->assertSame(2, $categorySales['Maison']);
    }

    public function test_dashboard_stream_returns_published_event(): void
    {
        $admin = User::factory()->create([
            'role' => Role::ADMIN,
            'permission' => 'scoped',
        ]);

        app(AdminDashboardRealtime::class)->publish(
            'payment_received',
            'Nouveau paiement recu !',
            ['highlights' => ['orders', 'stock']]
        );

        $response = $this->actingAs($admin)->get(route('admin.dashboard.stream', ['once' => 1]));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/event-stream; charset=UTF-8');
        $this->assertStringContainsString('event: dashboard-update', $response->streamedContent());
        $this->assertStringContainsString('Nouveau paiement recu !', $response->streamedContent());
    }
}
