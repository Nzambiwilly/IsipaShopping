@php
    $isEdit = $mode === 'edit';
    $action = $isEdit ? route('admin.users.update', $user) : route('admin.users.store');
@endphp

<section class="grid grid-cols-1 gap-4 lg:grid-cols-[1.15fr_0.85fr]">
    <article class="rounded-lg border border-[#3A2424] bg-[#210f0f] p-4">
        <h2 class="text-xl font-semibold">{{ $isEdit ? 'Modifier le compte' : 'Nouveau compte' }}</h2>
        <form action="{{ $action }}" method="POST" class="mt-4 flex flex-col gap-3">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <label class="flex flex-col gap-1.5">
                <span class="text-sm text-zinc-300">Nom complet</span>
                <input type="text" name="nom_complet" value="{{ old('nom_complet', $user->nom_complet) }}" required class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none ring-0 focus:border-[#EAF270]">
            </label>

            <label class="flex flex-col gap-1.5">
                <span class="text-sm text-zinc-300">Adresse email</span>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none ring-0 focus:border-[#EAF270]">
            </label>

            <label class="flex flex-col gap-1.5">
                <span class="text-sm text-zinc-300">Role</span>
                <select name="role" required class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none ring-0 focus:border-[#EAF270]">
                    @foreach ($roles as $role)
                        <option value="{{ $role->nom }}" @selected(old('role', $user->role ?: 'user') === $role->nom)>{{ $roleLabels[$role->nom] ?? ucfirst($role->nom) }}</option>
                    @endforeach
                </select>
            </label>

            <label class="flex flex-col gap-1.5">
                <span class="text-sm text-zinc-300">{{ $isEdit ? 'Nouveau mot de passe' : 'Mot de passe' }}</span>
                <input type="password" name="password" {{ $isEdit ? '' : 'required' }} class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none ring-0 focus:border-[#EAF270]">
            </label>

            <label class="flex flex-col gap-1.5">
                <span class="text-sm text-zinc-300">Confirmer le mot de passe</span>
                <input type="password" name="password_confirmation" {{ $isEdit ? '' : 'required' }} class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none ring-0 focus:border-[#EAF270]">
            </label>

            <div class="mt-2">
                <p class="text-sm font-medium text-white">Permissions granulaires</p>
                <div class="mt-3 grid grid-cols-1 gap-2 sm:grid-cols-2">
                    @foreach ($permissions as $permissionCode => $description)
                        <label class="flex items-start gap-3 rounded-md border border-[#3A2424] bg-[#1a0808] p-3 text-sm text-zinc-200">
                            <input type="checkbox" name="permissions[]" value="{{ $permissionCode }}" @checked(in_array($permissionCode, $selectedPermissions, true)) class="mt-0.5 h-4 w-4 rounded border-[#3A2424] bg-[#210f0f] text-[#EAF270] focus:ring-[#EAF270]">
                            <span>
                                <span class="block font-medium text-white">{{ $permissionCode }}</span>
                                <span class="block text-zinc-300">{{ $description }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-wrap gap-2 pt-2">
                <button type="submit" class="inline-flex w-fit items-center gap-2 rounded-md bg-[#EAF270] px-4 py-2 text-sm font-semibold text-[#1B1B1B] transition hover:bg-[#d6de5f]">
                    <x-ui.icon name="{{ $isEdit ? 'pencil' : 'plus' }}" class="h-4 w-4" />
                    <span>{{ $isEdit ? 'Enregistrer les modifications' : 'Creer l utilisateur' }}</span>
                </button>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 rounded-md border border-[#3A2424] bg-[#1a0808] px-4 py-2 text-sm font-medium text-white transition hover:border-[#EAF270]">
                    <x-ui.icon name="arrow-left" class="h-4 w-4" />
                    <span>Retour a la liste</span>
                </a>
            </div>
        </form>
    </article>

    <article class="rounded-lg border border-[#3A2424] bg-[#210f0f] p-4">
        <h2 class="text-xl font-semibold">Reference des droits</h2>
        <div class="mt-3 space-y-3">
            @foreach ($roles as $role)
                <div class="rounded-md border border-[#3A2424] bg-[#1a0808] p-3">
                    <p class="font-medium text-white">{{ $roleLabels[$role->nom] ?? ucfirst($role->nom) }}</p>
                    <p class="mt-1 text-sm text-zinc-300">{{ $role->description }}</p>
                </div>
            @endforeach
        </div>
    </article>
</section>
