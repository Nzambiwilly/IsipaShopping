<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\UserController;
use App\Models\Attributions;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $compiledPath = storage_path('framework/testing-views/admin-user-management');
        if (! is_dir($compiledPath)) {
            mkdir($compiledPath, 0777, true);
        }
        config()->set('view.compiled', $compiledPath);

        foreach (Role::definitions() as $role => $definition) {
            Role::query()->create([
                'nom' => $role,
                'description' => $definition['description'],
            ]);
        }

        foreach (Permission::definitions() as $code => $description) {
            Permission::query()->create([
                'role' => $code,
                'description' => $description,
            ]);
        }
    }

    public function test_admin_with_create_permission_can_access_user_creation_data(): void
    {
        $adminRole = Role::query()->where('nom', Role::ADMIN)->firstOrFail();
        $permissions = Permission::query()
            ->whereIn('role', [Permission::MANAGE_USERS, Permission::CREATE_USER])
            ->get();

        foreach ($permissions as $permission) {
            Attributions::query()->create([
                'id_role' => $adminRole->id,
                'id_permission' => $permission->id,
            ]);
        }

        $admin = User::factory()->create([
            'role' => Role::ADMIN,
            'permission' => 'scoped',
        ]);

        $this->actingAs($admin);
        $response = app(UserController::class)->create();

        $this->assertSame('admin.users.create', $response->name());
        $this->assertArrayHasKey('permissions', $response->getData());
    }

    public function test_editor_without_create_permission_cannot_open_user_creation_route(): void
    {
        $editor = User::factory()->create([
            'role' => Role::EDITOR,
            'permission' => 'scoped',
        ]);

        $response = $this->actingAs($editor)->postJson(route('admin.users.store'), [
            'nom_complet' => 'Utilisateur Refuse',
            'email' => 'refuse@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => Role::USER,
        ]);

        $response->assertForbidden();
    }

    public function test_admin_can_create_user_and_assign_role(): void
    {
        $adminRole = Role::query()->where('nom', Role::ADMIN)->firstOrFail();
        $permissions = Permission::query()
            ->whereIn('role', [Permission::MANAGE_USERS, Permission::CREATE_USER])
            ->get();

        foreach ($permissions as $permission) {
            Attributions::query()->create([
                'id_role' => $adminRole->id,
                'id_permission' => $permission->id,
            ]);
        }

        $admin = User::factory()->create([
            'role' => Role::ADMIN,
            'permission' => 'scoped',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'nom_complet' => 'Nouvel Editeur',
            'email' => 'editeur@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => Role::EDITOR,
            'permissions' => [Permission::EDIT_PRODUCTS, Permission::VIEW_REPORTS],
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'editeur@example.com',
            'role' => Role::EDITOR,
        ]);
        $createdUser = User::query()->where('email', 'editeur@example.com')->firstOrFail();
        $this->assertEqualsCanonicalizing(
            [Permission::EDIT_PRODUCTS, Permission::VIEW_REPORTS],
            $createdUser->directPermissionCodes()
        );
    }

    public function test_admin_can_update_user_role_and_direct_permissions(): void
    {
        $adminRole = Role::query()->where('nom', Role::ADMIN)->firstOrFail();
        $permissions = Permission::query()
            ->whereIn('role', [Permission::MANAGE_USERS, Permission::EDIT_USER])
            ->get();

        foreach ($permissions as $permission) {
            Attributions::query()->create([
                'id_role' => $adminRole->id,
                'id_permission' => $permission->id,
            ]);
        }

        $admin = User::factory()->create([
            'role' => Role::ADMIN,
            'permission' => 'scoped',
        ]);

        $managedUser = User::factory()->create([
            'role' => Role::USER,
            'permission' => 'user',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $managedUser), [
            'nom_complet' => 'Client Promu',
            'email' => $managedUser->email,
            'password' => '',
            'password_confirmation' => '',
            'role' => Role::EDITOR,
            'permissions' => [Permission::EDIT_PRODUCTS],
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $managedUser->refresh();

        $this->assertSame(Role::EDITOR, $managedUser->role);
        $this->assertEquals([Permission::EDIT_PRODUCTS], $managedUser->directPermissionCodes());
    }

    public function test_admin_cannot_update_super_admin_account(): void
    {
        $adminRole = Role::query()->where('nom', Role::ADMIN)->firstOrFail();
        $permissions = Permission::query()
            ->whereIn('role', [Permission::MANAGE_USERS, Permission::EDIT_USER])
            ->get();

        foreach ($permissions as $permission) {
            Attributions::query()->create([
                'id_role' => $adminRole->id,
                'id_permission' => $permission->id,
            ]);
        }

        $admin = User::factory()->create([
            'role' => Role::ADMIN,
            'permission' => 'scoped',
        ]);

        $superAdmin = User::factory()->create([
            'role' => Role::SUPER_ADMIN,
            'permission' => 'all',
        ]);

        $response = $this->actingAs($admin)->putJson(route('admin.users.update', $superAdmin), [
            'nom_complet' => 'Super Admin Modifie',
            'email' => $superAdmin->email,
            'password' => '',
            'password_confirmation' => '',
            'role' => Role::SUPER_ADMIN,
            'permissions' => [Permission::MANAGE_USERS],
        ]);

        $response->assertForbidden();
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $adminRole = Role::query()->where('nom', Role::ADMIN)->firstOrFail();
        $permissions = Permission::query()
            ->whereIn('role', [Permission::MANAGE_USERS, Permission::DELETE_USER])
            ->get();

        foreach ($permissions as $permission) {
            Attributions::query()->create([
                'id_role' => $adminRole->id,
                'id_permission' => $permission->id,
            ]);
        }

        $admin = User::factory()->create([
            'role' => Role::ADMIN,
            'permission' => 'scoped',
        ]);

        $response = $this->actingAs($admin)->deleteJson(route('admin.users.destroy', $admin));

        $response->assertForbidden();
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }
}
