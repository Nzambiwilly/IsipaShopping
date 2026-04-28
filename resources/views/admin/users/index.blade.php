<x-layouts.app :title="'Utilisateurs | ISIPA Shopping'">
    <section class="mb-6 flex flex-col items-start justify-between gap-3 sm:flex-row sm:items-center">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="mb-3 inline-flex items-center gap-2 rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm font-medium text-white transition hover:border-[#EAF270]">
                <x-ui.icon name="arrow-left" class="h-4 w-4" />
                <span>Retour a l admin</span>
            </a>
            <h1 class="text-2xl font-bold md:text-3xl">Gestion des utilisateurs</h1>
            <p class="mt-1 text-sm text-zinc-300">Administrez les comptes, les roles et les permissions granulaires depuis un seul espace.</p>
        </div>
        @if (auth()->user()->hasPermission('can_create_user'))
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 rounded-md bg-[#EAF270] px-4 py-2 text-sm font-semibold text-[#1B1B1B] transition hover:bg-[#d6de5f]">
                <x-ui.icon name="plus" class="h-4 w-4" />
                <span>Creer un utilisateur</span>
            </a>
        @endif
    </section>

    <section class="overflow-hidden rounded-lg border border-[#3A2424]">
        <table class="min-w-full divide-y divide-[#3A2424] bg-[#210f0f] text-sm">
            <thead class="bg-[#1a0808] text-zinc-300">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">Utilisateur</th>
                    <th class="px-4 py-3 text-left font-medium">Role</th>
                    <th class="px-4 py-3 text-left font-medium">Permissions directes</th>
                    <th class="px-4 py-3 text-left font-medium">Acces effectif</th>
                    <th class="px-4 py-3 text-left font-medium">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#3A2424]">
                @forelse ($users as $managedUser)
                    @php
                        $canManageTarget = ! $managedUser->is(auth()->user())
                            && (! $managedUser->hasRole('superadmin') || auth()->user()->hasRole('superadmin'));
                    @endphp
                    <tr>
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-medium text-white">{{ $managedUser->nom_complet }}</p>
                                <p class="text-xs text-zinc-300">{{ $managedUser->email }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3">{{ $roleLabels[$managedUser->role] ?? ucfirst($managedUser->role) }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                @forelse ($managedUser->directPermissionCodes() as $permissionCode)
                                    <span class="rounded-full border border-[#3A2424] bg-[#1a0808] px-2 py-1 text-xs text-zinc-200">{{ $permissionCode }}</span>
                                @empty
                                    <span class="text-xs text-zinc-400">Aucune permission directe</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                @foreach ($managedUser->allPermissionCodes() as $permissionCode)
                                    <span class="rounded-full bg-[#EAF270] px-2 py-1 text-xs font-medium text-[#1B1B1B]">{{ $permissionCode }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap items-center gap-2">
                                @if (auth()->user()->hasPermission('can_edit_user') && $canManageTarget)
                                    <a href="{{ route('admin.users.edit', $managedUser) }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-1.5 text-xs font-medium text-white transition hover:border-[#EAF270]">
                                        <x-ui.icon name="pencil" class="h-3.5 w-3.5" />
                                        <span>Modifier</span>
                                    </a>
                                @endif
                                @if (auth()->user()->hasPermission('can_delete_user') && $canManageTarget)
                                    <form method="POST" action="{{ route('admin.users.destroy', $managedUser) }}" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-md border border-red-500/40 bg-red-600/20 px-3 py-1.5 text-xs font-medium text-red-200 transition hover:bg-red-600/30">
                                            <x-ui.icon name="trash" class="h-3.5 w-3.5" />
                                            <span>Supprimer</span>
                                        </button>
                                    </form>
                                @endif
                                @unless ($canManageTarget)
                                    <span class="rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-1.5 text-xs text-zinc-400">
                                        Protege
                                    </span>
                                @endunless
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-zinc-300">Aucun utilisateur disponible.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</x-layouts.app>
