<x-layouts.app :title="'Catalogue | ISIPA Shopping'">
    <section class="mb-6 flex flex-col items-start justify-between gap-4 lg:flex-row lg:items-end">
        <div>
            <h1 class="text-2xl font-bold leading-tight md:text-4xl">Catalogue des produits informatiques</h1>
            <p class="mt-2 max-w-3xl text-sm text-zinc-300 md:text-base">Decouvrez les produits disponibles, comparez rapidement les prix et trouvez le materiel adapte a vos besoins.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('contact.index') }}" class="rounded-md bg-[#EAF270] px-4 py-2 text-sm font-semibold text-[#1B1B1B] transition hover:bg-[#d6de5f]">Contacter ISIPA</a>
            @guest
                <a href="{{ route('register') }}" class="rounded-md border border-[#3A2424] bg-[#210f0f] px-4 py-2 text-sm font-medium text-white transition hover:border-[#EAF270]">Creer un compte</a>
            @endguest
        </div>
    </section>

    <section class="rounded-lg border border-[#3A2424] bg-[#210f0f] p-4">
        <form method="GET" action="{{ route('catalogue') }}" class="grid grid-cols-1 items-end gap-3 md:grid-cols-[1.5fr_0.8fr_auto]">
            <label class="flex flex-col gap-1.5">
                <span class="text-sm text-zinc-300">Recherche</span>
                <input type="text" name="q" placeholder="Nom ou description..." value="{{ $search }}" class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none ring-0 focus:border-[#EAF270]">
            </label>

            <label class="flex flex-col gap-1.5">
                <span class="text-sm text-zinc-300">Trier par</span>
                <select name="sort" class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none ring-0 focus:border-[#EAF270]">
                    <option value="recent" @selected($sort === 'recent')>Plus recents</option>
                    <option value="price_asc" @selected($sort === 'price_asc')>Prix croissant</option>
                    <option value="price_desc" @selected($sort === 'price_desc')>Prix decroissant</option>
                    <option value="name_asc" @selected($sort === 'name_asc')>Nom A-Z</option>
                </select>
            </label>

            <button type="submit" class="rounded-md bg-[#EAF270] px-4 py-2 text-sm font-semibold text-[#1B1B1B] transition hover:bg-[#d6de5f]">Filtrer</button>
        </form>
    </section>

    @if ($produits->count())
        <section class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($produits as $produit)
                <article class="overflow-hidden rounded-lg border border-[#3A2424] bg-[#210f0f] transition hover:-translate-y-0.5 hover:border-[#d6de5f]">
                    <img
                        src="{{ $produit->image ?: 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1200&q=80' }}"
                        alt="{{ $produit->nom }}"
                        loading="lazy"
                        class="aspect-[4/3] w-full object-cover"
                    >
                    <div class="flex flex-col gap-2 p-3">
                        <div class="flex items-center justify-between gap-2">
                            <h2 class="text-base font-semibold">{{ $produit->nom }}</h2>
                            <span class="rounded-full px-2 py-1 text-xs font-medium {{ $produit->stock > 0 ? 'bg-emerald-300 text-emerald-900' : 'bg-red-200 text-red-900' }}">
                                {{ $produit->stock > 0 ? 'En stock' : 'Rupture' }}
                            </span>
                        </div>
                        <p class="text-xs text-zinc-400">
                            Categorie: {{ $produit->categorie?->nom ?? 'Non classe' }}
                        </p>
                        <p class="min-h-[42px] text-sm text-zinc-300">{{ \Illuminate\Support\Str::limit($produit->description ?: 'Produit informatique.', 110) }}</p>
                        <div class="flex items-center justify-between gap-2">
                            <strong class="text-[#EAF270]">{{ number_format((float) $produit->prix_unitaire, 2, ',', ' ') }} USD</strong>
                            <span class="text-sm text-zinc-300">{{ $produit->stock }} unite(s)</span>
                        </div>
                        <div>
                            @auth
                                <form method="POST" action="{{ route('panier.add', $produit) }}" class="flex items-center gap-2">
                                    @csrf
                                    <input type="number" name="quantite" min="1" max="{{ max(1, $produit->stock) }}" value="1" class="w-16 rounded-md border border-[#3A2424] bg-[#1a0808] px-2 py-1.5 text-sm text-white">
                                    <button type="submit" class="rounded-md bg-[#EAF270] px-3 py-1.5 text-xs font-semibold text-[#1B1B1B] transition hover:bg-[#d6de5f]" @disabled($produit->stock < 1)>
                                        Ajouter au panier
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-1.5 text-xs font-medium text-white transition hover:border-[#EAF270]">
                                    Connectez-vous pour commander
                                </a>
                            @endauth
                        </div>
                    </div>
                </article>
            @endforeach
        </section>

        <div class="mt-4">
            {{ $produits->links() }}
        </div>
    @else
        <section class="mt-5 rounded-lg border border-dashed border-[#3A2424] bg-[#210f0f] p-6 text-center">
            <h2 class="text-lg font-semibold">Aucun produit ne correspond a votre recherche.</h2>
            <p class="mt-1 text-sm text-zinc-300">Essayez un autre mot-cle ou retirez les filtres.</p>
        </section>
    @endif
</x-layouts.app>
