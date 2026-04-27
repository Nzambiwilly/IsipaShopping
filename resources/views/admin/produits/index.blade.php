<x-layouts.app :title="'Admin Produits | ISIPA Shopping'">
    <section class="mb-6 flex flex-col items-start justify-between gap-3 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold md:text-3xl">Gestion des produits</h1>
            <p class="mt-1 text-sm text-zinc-300">Ajoutez et modifiez les produits du catalogue.</p>
        </div>
        <a href="{{ route('admin.produits.create') }}" class="rounded-md bg-[#EAF270] px-4 py-2 text-sm font-semibold text-[#1B1B1B] transition hover:bg-[#d6de5f]">
            Ajouter un produit
        </a>
    </section>

    <section class="overflow-hidden rounded-lg border border-[#3A2424]">
        <table class="min-w-full divide-y divide-[#3A2424] bg-[#210f0f] text-sm">
            <thead class="bg-[#1a0808] text-zinc-300">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">Produit</th>
                    <th class="px-4 py-3 text-left font-medium">Categorie</th>
                    <th class="px-4 py-3 text-left font-medium">Prix</th>
                    <th class="px-4 py-3 text-left font-medium">Stock</th>
                    <th class="px-4 py-3 text-left font-medium">Statut</th>
                    <th class="px-4 py-3 text-left font-medium">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#3A2424]">
                @forelse ($produits as $produit)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $produit->image_url ?? 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=600&q=80' }}" alt="{{ $produit->nom }}" class="h-10 w-10 rounded-md object-cover">
                                <span class="font-medium">{{ $produit->nom }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">{{ $produit->categorie?->nom ?? 'Non classe' }}</td>
                        <td class="px-4 py-3">{{ number_format((float) $produit->prix_unitaire, 2, ',', ' ') }} USD</td>
                        <td class="px-4 py-3">{{ $produit->stock }}</td>
                        <td class="px-4 py-3">{{ ucfirst($produit->statut) }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.produits.edit', $produit) }}" class="rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-1.5 text-xs font-medium text-white transition hover:border-[#EAF270]">
                                    Modifier
                                </a>
                                <form method="POST" action="{{ route('admin.produits.destroy', $produit) }}" onsubmit="return confirm('Supprimer ce produit ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-md border border-red-500/40 bg-red-600/20 px-3 py-1.5 text-xs font-medium text-red-200 transition hover:bg-red-600/30">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-zinc-300">Aucun produit disponible.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <div class="mt-4">
        {{ $produits->links() }}
    </div>
</x-layouts.app>
