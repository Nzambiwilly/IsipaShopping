<?php

namespace Tests\Feature;

use App\Models\Produits;
use App\Models\User;
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
}

