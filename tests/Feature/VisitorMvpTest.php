<?php

namespace Tests\Feature;

use App\Models\Produits;
use App\Models\User;
use App\Models\Commande;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitorMvpTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitor_can_view_catalogue(): void
    {
        Produits::query()->create([
            'nom' => 'Clavier ISIPA',
            'description' => 'Clavier mecanique',
            'prix_unitaire' => 80,
            'stock' => 3,
            'date_fabrication' => now()->toDateString(),
            'statut' => 'disponible',
            'date_ajout' => now()->toDateString(),
        ]);

        $response = $this->get(route('catalogue'));

        $response->assertOk();
        $response->assertSee('Clavier ISIPA');
    }

    public function test_visitor_can_send_contact_message(): void
    {
        $response = $this->post(route('contact.store'), [
            'nom' => 'Jean Test',
            'email' => 'jean@example.com',
            'message' => 'Bonjour, je veux en savoir plus sur vos produits.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_visitor_can_register_and_login(): void
    {
        $register = $this->post(route('register.store'), [
            'nom_complet' => 'Marie Client',
            'email' => 'marie@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $register->assertRedirect(route('catalogue'));
        $this->assertAuthenticated();

        auth()->logout();

        User::query()->create([
            'nom_complet' => 'Paul Client',
            'email' => 'paul@example.com',
            'password' => 'password123',
            'role' => 'client',
            'permission' => 'user',
        ]);

        $login = $this->post(route('login.attempt'), [
            'email' => 'paul@example.com',
            'password' => 'password123',
        ]);

        $login->assertRedirect(route('catalogue'));
        $this->assertAuthenticated();
    }

    public function test_client_can_pay_only_selected_cart_items_and_keep_the_rest(): void
    {
        $user = User::factory()->create();

        $ordinateur = Produits::query()->create([
            'nom' => 'Ordinateur Portable',
            'description' => 'Portable pro',
            'prix_unitaire' => 800,
            'stock' => 5,
            'date_fabrication' => now()->toDateString(),
            'statut' => 'disponible',
            'date_ajout' => now()->toDateString(),
        ]);

        $souris = Produits::query()->create([
            'nom' => 'Souris USB',
            'description' => 'Souris simple',
            'prix_unitaire' => 20,
            'stock' => 10,
            'date_fabrication' => now()->toDateString(),
            'statut' => 'disponible',
            'date_ajout' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)
            ->withSession([
                'cart_' . $user->id => [
                    $ordinateur->id => 1,
                    $souris->id => 2,
                ],
            ])
            ->get(route('checkout.show', [
                'selected_items' => [$ordinateur->id],
            ]));

        $response->assertOk();
        $response->assertSee('Ordinateur Portable');
        $response->assertDontSee('Souris USB');

        $storeResponse = $this->actingAs($user)
            ->withSession([
                'cart_' . $user->id => [
                    $ordinateur->id => 1,
                    $souris->id => 2,
                ],
            ])
            ->post(route('checkout.store'), [
                'selected_items' => [$ordinateur->id],
                'adresse_livraison' => '12 avenue de la Livraison, Kinshasa',
                'date_livraison' => now()->addDays(2)->toDateString(),
                'methode_paiement' => 'mobile_money',
            ]);

        $storeResponse->assertRedirect(route('catalogue'));
        $storeResponse->assertSessionHas('success');

        $this->assertDatabaseCount('commandes', 1);
        $commande = Commande::query()->first();
        $this->assertNotNull($commande);
        $this->assertDatabaseHas('commande_produits', [
            'commande_id' => $commande->id,
            'produit_id' => $ordinateur->id,
            'quantite' => 1,
        ]);
        $this->assertDatabaseMissing('commande_produits', [
            'commande_id' => $commande->id,
            'produit_id' => $souris->id,
        ]);
        $storeResponse->assertSessionHas('cart_' . $user->id, [
            $souris->id => 2,
        ]);
    }
}
