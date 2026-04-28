<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->with('permissions')
            ->latest()
            ->paginate(12);

        return view('admin.users.index', [
            'users' => $users,
            'roleLabels' => Role::labels(),
        ]);
    }

    public function create()
    {
        return view('admin.users.create', [
            'user' => new User(),
            'roles' => $this->orderedRoles(),
            'roleLabels' => Role::labels(),
            'permissions' => Permission::definitions(),
            'selectedPermissions' => old('permissions', []),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateUser($request);

        if (
            $validated['role'] === Role::SUPER_ADMIN
            && ! $request->user()->hasRole(Role::SUPER_ADMIN)
        ) {
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['role' => 'Seul un SuperAdmin peut creer un autre SuperAdmin.']);
        }

        $user = User::query()->create([
            'nom_complet' => $validated['nom_complet'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
            'permission' => $validated['role'] === Role::USER ? 'user' : 'scoped',
        ]);
        $user->syncPermissionsByCodes($validated['permissions'] ?? []);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur cree avec succes : ' . $user->nom_complet . '.');
    }

    public function edit(User $user)
    {
        $this->authorizeTargetUser(request()->user(), $user, 'modifier');

        return view('admin.users.edit', [
            'user' => $user->load('permissions'),
            'roles' => $this->orderedRoles(),
            'roleLabels' => Role::labels(),
            'permissions' => Permission::definitions(),
            'selectedPermissions' => old('permissions', $user->directPermissionCodes()),
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeTargetUser($request->user(), $user, 'modifier');

        $validated = $this->validateUser($request, $user);

        if (
            $validated['role'] === Role::SUPER_ADMIN
            && ! $request->user()->hasRole(Role::SUPER_ADMIN)
        ) {
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['role' => 'Seul un SuperAdmin peut promouvoir un utilisateur en SuperAdmin.']);
        }

        $user->update([
            'nom_complet' => $validated['nom_complet'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'permission' => $validated['role'] === Role::USER ? 'user' : 'scoped',
            ...($validated['password'] ? ['password' => $validated['password']] : []),
        ]);

        $user->syncPermissionsByCodes($validated['permissions'] ?? []);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis a jour avec succes : ' . $user->nom_complet . '.');
    }

    public function destroy(Request $request, User $user)
    {
        $this->authorizeTargetUser($request->user(), $user, 'supprimer');

        $nom = $user->nom_complet;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprime avec succes : ' . $nom . '.');
    }

    private function validateUser(Request $request, ?User $user = null): array
    {
        $passwordRules = $user
            ? ['nullable', 'confirmed', 'min:8']
            : ['required', 'confirmed', 'min:8'];

        return $request->validate([
            'nom_complet' => ['required', 'string', 'min:3', 'max:160'],
            'email' => [
                'required',
                'email',
                'max:120',
                Rule::unique('users', 'email')->ignore($user),
            ],
            'password' => $passwordRules,
            'role' => ['required', 'string', Rule::in(array_keys(Role::definitions()))],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in(array_keys(Permission::definitions()))],
        ]);
    }

    private function orderedRoles()
    {
        return Role::query()
            ->whereIn('nom', array_keys(Role::definitions()))
            ->get()
            ->sortBy(fn (Role $role) => array_search($role->nom, array_keys(Role::definitions()), true))
            ->values();
    }

    private function authorizeTargetUser(User $actor, User $target, string $action): void
    {
        if ($target->is($actor)) {
            abort(403, 'Vous ne pouvez pas ' . $action . ' votre propre compte depuis ce module.');
        }

        if ($target->hasRole(Role::SUPER_ADMIN) && ! $actor->hasRole(Role::SUPER_ADMIN)) {
            abort(403, 'Seul un SuperAdmin peut ' . $action . ' un SuperAdmin.');
        }
    }
}
