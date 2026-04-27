<x-layouts.app :title="'Checkout | ISIPA Shopping'">
    <section class="mb-6">
        <h1 class="text-2xl font-bold md:text-3xl">Validation de commande</h1>
        <p class="mt-1 text-sm text-zinc-300">Confirmez votre panier, saisissez l'adresse de livraison et validez le paiement.</p>
    </section>

    @if ($items->count() === 0)
        <section class="rounded-lg border border-dashed border-[#3A2424] bg-[#210f0f] p-8 text-center">
            <h2 class="text-lg font-semibold">Votre panier est vide.</h2>
            <a href="{{ route('catalogue') }}" class="mt-4 inline-flex rounded-md bg-[#EAF270] px-4 py-2 text-sm font-semibold text-[#1B1B1B] transition hover:bg-[#d6de5f]">
                Retour au catalogue
            </a>
        </section>
    @else
        <section class="grid grid-cols-1 gap-4 lg:grid-cols-[1.3fr_1fr]">
            <article class="rounded-lg border border-[#3A2424] bg-[#210f0f] p-4">
                <h2 class="text-xl font-semibold">Panier confirme</h2>
                <div class="mt-3 space-y-3">
                    @foreach ($items as $item)
                        <div class="flex items-center justify-between gap-3 rounded-md border border-[#3A2424] bg-[#1a0808] p-3 text-sm">
                            <div>
                                <p class="font-medium">{{ $item['produit']->nom }}</p>
                                <p class="text-zinc-300">Quantite: {{ $item['quantite'] }}</p>
                            </div>
                            <strong class="text-[#EAF270]">{{ number_format($item['sous_total'], 2, ',', ' ') }} USD</strong>
                        </div>
                    @endforeach
                </div>
                <p class="mt-4 text-lg font-semibold">Total: <span class="text-[#EAF270]">{{ number_format($total, 2, ',', ' ') }} USD</span></p>
            </article>

            <article class="rounded-lg border border-[#3A2424] bg-[#210f0f] p-4">
                <h2 class="text-xl font-semibold">Paiement et livraison</h2>
                <form action="{{ route('checkout.store') }}" method="POST" class="mt-3 flex flex-col gap-3">
                    @csrf
                    <label class="flex flex-col gap-1.5">
                        <span class="text-sm text-zinc-300">Adresse de livraison</span>
                        <textarea name="adresse_livraison" rows="4" required class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none focus:border-[#EAF270]">{{ $adresse }}</textarea>
                    </label>
                    <label class="flex flex-col gap-1.5">
                        <span class="text-sm text-zinc-300">Date de livraison souhaitée (optionnel)</span>
                        <input type="date" name="date_livraison" value="{{ old('date_livraison') }}" class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none focus:border-[#EAF270]">
                    </label>
                    <label class="flex flex-col gap-1.5">
                        <span class="text-sm text-zinc-300">Methode de paiement</span>
                        <select name="methode_paiement" required class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none focus:border-[#EAF270]">
                            <option value="mobile_money" @selected(old('methode_paiement') === 'mobile_money')>Mobile Money</option>
                            <option value="carte" @selected(old('methode_paiement') === 'carte')>Carte bancaire</option>
                            <option value="especes" @selected(old('methode_paiement') === 'especes')>Paiement en especes</option>
                        </select>
                    </label>
                    <button type="submit" class="w-fit rounded-md bg-[#EAF270] px-4 py-2 text-sm font-semibold text-[#1B1B1B] transition hover:bg-[#d6de5f]">
                        Valider le paiement
                    </button>
                </form>
            </article>
        </section>
    @endif
</x-layouts.app>

