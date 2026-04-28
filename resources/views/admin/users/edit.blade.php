<x-layouts.app :title="'Modification utilisateur | ISIPA Shopping'">
    <section class="mb-6 flex flex-col items-start justify-between gap-3 sm:flex-row sm:items-center">
        <div>
            <a href="{{ route('admin.users.index') }}" class="mb-3 inline-flex items-center gap-2 rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm font-medium text-white transition hover:border-[#EAF270]">
                <x-ui.icon name="arrow-left" class="h-4 w-4" />
                <span>Retour aux utilisateurs</span>
            </a>
            <h1 class="text-2xl font-bold md:text-3xl">Modifier {{ $user->nom_complet }}</h1>
            <p class="mt-1 text-sm text-zinc-300">Ajustez le role, les permissions granulaires et, si besoin, le mot de passe.</p>
        </div>
    </section>

    @include('admin.users.form')
</x-layouts.app>
