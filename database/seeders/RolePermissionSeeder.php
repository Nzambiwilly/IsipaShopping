<?php

namespace Database\Seeders;

use App\Models\Attributions;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Role::definitions() as $role => $definition) {
            Role::query()->updateOrCreate(
                ['nom' => $role],
                ['description' => $definition['description']]
            );
        }

        foreach (Permission::definitions() as $code => $description) {
            Permission::query()->updateOrCreate(
                ['role' => $code],
                ['description' => $description]
            );
        }

        $matrix = [
            Role::SUPER_ADMIN => [
                Permission::MANAGE_USERS,
                Permission::CREATE_USER,
                Permission::EDIT_USER,
                Permission::DELETE_USER,
                Permission::VIEW_REPORTS,
                Permission::EDIT_PRODUCTS,
            ],
            Role::ADMIN => [
                Permission::MANAGE_USERS,
                Permission::CREATE_USER,
                Permission::EDIT_USER,
                Permission::VIEW_REPORTS,
                Permission::EDIT_PRODUCTS,
            ],
            Role::EDITOR => [
                Permission::EDIT_PRODUCTS,
                Permission::VIEW_REPORTS,
            ],
            Role::USER => [],
        ];

        foreach ($matrix as $roleName => $permissions) {
            $role = Role::query()->where('nom', $roleName)->first();
            foreach ($permissions as $permissionCode) {
                $permission = Permission::query()->where('role', $permissionCode)->first();
                if (! $role || ! $permission) {
                    continue;
                }

                Attributions::query()->updateOrCreate([
                    'id_role' => $role->id,
                    'id_permission' => $permission->id,
                ]);
            }
        }
    }
}
