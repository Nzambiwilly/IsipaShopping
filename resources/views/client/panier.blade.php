<x-layouts.app :title="'Panier | ISIPA Shopping'">
    <section class="mb-6 flex flex-col items-start justify-between gap-3 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold md:text-3xl">Mon panier</h1>
            <p class="mt-1 text-sm text-zinc-300">Confirmez les quantites avant de valider votre commande.</p>
        </div>
        @if ($items->count() > 0)
            <a href="{{ route('checkout.show') }}" class="rounded-md bg-[#EAF270] px-4 py-2 text-sm font-semibold text-[#1B1B1B] transition hover:bg-[#d6de5f]">
                Passer au checkout
            </a>
        @endif
    </section>

    @if ($items->count() > 0)
        <section class="space-y-3">
            @foreach ($items as $item)
                <article class="rounded-lg border border-[#3A2424] bg-[#210f0f] p-4">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ $item['produit']->image_url ?? 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=600&q=80' }}" alt="{{ $item['produit']->nom }}" class="h-14 w-14 rounded-md object-cover">
                            <div>
                                <h2 class="font-semibold">{{ $item['produit']->nom }}</h2>
                                <p class="text-sm text-zinc-300">{{ number_format((float) $item['produit']->prix_unitaire, 2, ',', ' ') }} USD / unite</p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <form method="POST" action="{{ route('panier.update', $item['produit']) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantite" min="1" max="{{ max(1, $item['produit']->stock) }}" value="{{ $item['quantite'] }}" class="w-16 rounded-md border border-[#3A2424] bg-[#1a0808] px-2 py-1.5 text-sm text-white">
                                <button type="submit" class="rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-1.5 text-xs font-medium text-white transition hover:border-[#EAF270]">
                                    Mettre a jour
                                </button>
                            </form>
                            <form method="POST" action="{{ route('panier.remove', $item['produit']) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-md border border-red-500/40 bg-red-600/20 px-3 py-1.5 text-xs font-medium text-red-200 transition hover:bg-red-600/30">
                                    Retirer
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-3 text-sm text-zinc-200">
                        Sous-total: <strong class="text-[#EAF270]">{{ number_format($item['sous_total'], 2, ',', ' ') }} USD</strong>
                    </div>
                </article>
            @endforeach
        </section>

        <section class="mt-5 rounded-lg border border-[#3A2424] bg-[#210f0f] p-4">
            <p class="text-lg font-semibold">Total: <span class="text-[#EAF270]">{{ number_format($total, 2, ',', ' ') }} USD</span></p>
        </section>
    @else
        <section class="rounded-lg border border-dashed border-[#3A2424] bg-[#210f0f] p-8 text-center">
            <h2 class="text-lg font-semibold">Votre panier est vide.</h2>
            <p class="mt-1 text-sm text-zinc-300">Ajoutez des produits depuis le catalogue pour commencer.</p>
            <a href="{{ route('catalogue') }}" class="mt-4 inline-flex rounded-md bg-[#EAF270] px-4 py-2 text-sm font-semibold text-[#1B1B1B] transition hover:bg-[#d6de5f]">
                Voir le catalogue
            </a>
        </section>
    @endif
</x-layouts.app>

