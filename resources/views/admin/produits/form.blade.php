<x-layouts.app :title="$mode === 'create' ? 'Ajouter un produit | ISIPA Shopping' : 'Modifier un produit | ISIPA Shopping'">
    <section class="mb-6">
        <h1 class="text-2xl font-bold md:text-3xl">
            {{ $mode === 'create' ? 'Ajouter un produit' : 'Modifier un produit' }}
        </h1>
    </section>

    <section class="rounded-lg border border-[#3A2424] bg-[#210f0f] p-5">
        <form
            method="POST"
            action="{{ $mode === 'create' ? route('admin.produits.store') : route('admin.produits.update', $produit) }}"
            enctype="multipart/form-data"
            class="grid grid-cols-1 gap-4 md:grid-cols-2"
        >
            @csrf
            @if ($mode === 'edit')
                @method('PUT')
            @endif

            <label class="flex flex-col gap-1.5 md:col-span-2">
                <span class="text-sm text-zinc-300">Nom</span>
                <input type="text" name="nom" value="{{ old('nom', $produit->nom) }}" required class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none focus:border-[#EAF270]">
            </label>

            <label class="flex flex-col gap-1.5 md:col-span-2">
                <span class="text-sm text-zinc-300">Description</span>
                <textarea name="description" rows="4" class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none focus:border-[#EAF270]">{{ old('description', $produit->description) }}</textarea>
            </label>

            <label class="flex flex-col gap-1.5">
                <span class="text-sm text-zinc-300">Prix (USD)</span>
                <input type="number" name="prix_unitaire" min="0" step="0.01" value="{{ old('prix_unitaire', $produit->prix_unitaire) }}" required class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none focus:border-[#EAF270]">
            </label>

            <label class="flex flex-col gap-1.5">
                <span class="text-sm text-zinc-300">Stock</span>
                <input type="number" name="stock" min="0" value="{{ old('stock', $produit->stock ?? 0) }}" required class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none focus:border-[#EAF270]">
            </label>

            <label class="flex flex-col gap-1.5">
                <span class="text-sm text-zinc-300">Categorie</span>
                <select name="categorie_id" required class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none focus:border-[#EAF270]">
                    <option value="">Selectionner</option>
                    @foreach ($categories as $categorie)
                        <option value="{{ $categorie->id }}" @selected((int) old('categorie_id', $produit->categorie_id) === $categorie->id)>
                            {{ $categorie->nom }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label class="flex flex-col gap-1.5">
                <span class="text-sm text-zinc-300">Date de fabrication</span>
                <input type="date" name="date_fabrication" value="{{ old('date_fabrication', optional($produit->date_fabrication)->format('Y-m-d') ?? now()->toDateString()) }}" required class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none focus:border-[#EAF270]">
            </label>

            <label class="flex flex-col gap-1.5">
                <span class="text-sm text-zinc-300">Statut</span>
                <select name="statut" required class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none focus:border-[#EAF270]">
                    @foreach (['disponible', 'rupture', 'archive'] as $statut)
                        <option value="{{ $statut }}" @selected(old('statut', $produit->statut ?? 'disponible') === $statut)>
                            {{ ucfirst($statut) }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label class="flex flex-col gap-1.5 md:col-span-2">
                <span class="text-sm text-zinc-300">Image produit</span>
                <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white">
            </label>

            @if ($mode === 'edit' && $produit->image_url)
                <div class="md:col-span-2">
                    <p class="mb-2 text-sm text-zinc-300">Image actuelle</p>
                    <img src="{{ $produit->image_url }}" alt="{{ $produit->nom }}" class="h-24 w-24 rounded-md object-cover">
                </div>
            @endif

            <div class="md:col-span-2 flex items-center gap-2">
                <button type="submit" class="rounded-md bg-[#EAF270] px-4 py-2 text-sm font-semibold text-[#1B1B1B] transition hover:bg-[#d6de5f]">
                    {{ $mode === 'create' ? 'Enregistrer' : 'Sauvegarder' }}
                </button>
                <a href="{{ route('admin.produits.index') }}" class="rounded-md border border-[#3A2424] bg-[#1a0808] px-4 py-2 text-sm font-medium text-white transition hover:border-[#EAF270]">
                    Annuler
                </a>
            </div>
        </form>
    </section>
</x-layouts.app>

