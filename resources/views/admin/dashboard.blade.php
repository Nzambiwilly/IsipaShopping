<x-layouts.app :title="'Dashboard admin | ISIPA Shopping'">
    @php
        $tones = [
            'sand' => 'border-[#5A4A28] bg-[#2B2112] text-[#F2D58B]',
            'blue' => 'border-[#244A66] bg-[#102230] text-[#88C8F2]',
            'green' => 'border-[#24543B] bg-[#11291D] text-[#89D7A9]',
            'red' => 'border-[#6B2B2B] bg-[#301515] text-[#F0A3A3]',
        ];
    @endphp

    <section class="mb-6 flex flex-col items-start justify-between gap-3 sm:flex-row sm:items-center" data-dashboard-root data-stream-url="{{ route('admin.dashboard.stream') }}">
        <div>
            <h1 class="text-2xl font-bold md:text-3xl">Tableau de bord e-commerce</h1>
            <p class="mt-1 text-sm text-zinc-300">Vue synthese des comptes, ventes par categorie, stock et etat des commandes.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.produits.index') }}" class="inline-flex items-center gap-2 rounded-md border border-[#3A2424] bg-[#1a0808] px-4 py-2 text-sm font-medium text-white transition hover:border-[#EAF270]">
                <x-ui.icon name="box" class="h-4 w-4" />
                <span>Catalogue</span>
            </a>
            @if (auth()->user()->hasPermission('manage_users'))
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 rounded-md border border-[#3A2424] bg-[#1a0808] px-4 py-2 text-sm font-medium text-white transition hover:border-[#EAF270]">
                    <x-ui.icon name="admin" class="h-4 w-4" />
                    <span>Utilisateurs</span>
                </a>
            @endif
        </div>
    </section>

    <section class="grid grid-cols-1 gap-4 xl:grid-cols-12">
        <article class="rounded-lg border border-[#3A2424] bg-[#210f0f] p-5 xl:col-span-3" data-highlight-card="users">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm uppercase tracking-[0.18em] text-zinc-400">Utilisateurs</p>
                    <p class="mt-3 text-4xl font-bold text-white" data-metric="totalUsers">{{ number_format($totalUsers, 0, ',', ' ') }}</p>
                    <p class="mt-2 text-sm text-zinc-300">Comptes enregistrés sur la plateforme.</p>
                </div>
                <span class="rounded-md border border-[#4D3F1F] bg-[#33270D] p-3 text-[#EAF270]">
                    <x-ui.icon name="users" class="h-5 w-5" />
                </span>
            </div>
        </article>

        <article class="rounded-lg border border-[#3A2424] bg-[#210f0f] p-5 xl:col-span-5" data-highlight-card="categories">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-white">Produits vendus par categorie</h2>
                    <p class="mt-1 text-sm text-zinc-300">Cumul des quantites vendues par famille de produits.</p>
                </div>
                <span class="rounded-md border border-[#3A2424] bg-[#1a0808] p-3 text-[#EAF270]">
                    <x-ui.icon name="chart" class="h-5 w-5" />
                </span>
            </div>

            <div class="mt-4 space-y-3" data-category-sales>
                @forelse ($productsSoldByCategory as $categoryStat)
                    <div class="rounded-md border border-[#3A2424] bg-[#1a0808] p-3">
                        <div class="flex items-center justify-between gap-3">
                            <p class="font-medium text-white">{{ $categoryStat['categorie'] }}</p>
                            <span class="rounded-md bg-[#2B1717] px-2.5 py-1 text-sm font-semibold text-[#EAF270]">
                                {{ number_format($categoryStat['total_vendus'], 0, ',', ' ') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="rounded-md border border-dashed border-[#3A2424] bg-[#1a0808] p-4 text-sm text-zinc-300">
                        Aucune vente enregistree pour le moment.
                    </div>
                @endforelse
            </div>
        </article>

        <article class="rounded-lg border border-[#3A2424] bg-[#210f0f] p-5 xl:col-span-4" data-highlight-card="stock">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-white">Etat du stock</h2>
                    <p class="mt-1 text-sm text-zinc-300">Lecture rapide du niveau de disponibilite du catalogue.</p>
                </div>
                <span class="rounded-md border border-[#3A2424] bg-[#1a0808] p-3 text-[#EAF270]">
                    <x-ui.icon name="archive" class="h-5 w-5" />
                </span>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-3">
                <div class="rounded-md border border-[#24543B] bg-[#11291D] p-4">
                    <p class="text-sm text-[#89D7A9]">Produits bien fournis</p>
                    <p class="mt-2 text-2xl font-bold text-white" data-stock-metric="disponible">{{ $stockSummary['disponible'] }}</p>
                </div>
                <div class="rounded-md border border-[#5A4A28] bg-[#2B2112] p-4">
                    <p class="text-sm text-[#F2D58B]">Stock limite</p>
                    <p class="mt-2 text-2xl font-bold text-white" data-stock-metric="limite">{{ $stockSummary['limite'] }}</p>
                </div>
                <div class="rounded-md border border-[#6B2B2B] bg-[#301515] p-4">
                    <p class="text-sm text-[#F0A3A3]">Ruptures</p>
                    <p class="mt-2 text-2xl font-bold text-white" data-stock-metric="rupture">{{ $stockSummary['rupture'] }}</p>
                </div>
                <div class="rounded-md border border-[#244A66] bg-[#102230] p-4">
                    <p class="text-sm text-[#88C8F2]">Unites en stock</p>
                    <p class="mt-2 text-2xl font-bold text-white" data-stock-metric="total_unites">{{ number_format($stockSummary['total_unites'], 0, ',', ' ') }}</p>
                </div>
            </div>
        </article>
    </section>

    <section class="mt-6">
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-white">Suivi des commandes</h2>
            <p class="mt-1 text-sm text-zinc-300">Chaque etat est isole dans sa propre carte pour une lecture immediate.</p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($orderStatusCards as $card)
                <article class="rounded-lg border p-5 {{ $tones[$card['tone']] }}" data-highlight-card="orders" data-order-card="{{ $card['key'] }}">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium">{{ $card['label'] }}</p>
                            <p class="mt-3 text-4xl font-bold text-white" data-order-metric="{{ $card['key'] }}">{{ number_format($card['value'], 0, ',', ' ') }}</p>
                        </div>
                        <span class="rounded-md border border-current/30 bg-black/10 p-3">
                            <x-ui.icon name="cart" class="h-5 w-5" />
                        </span>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const root = document.querySelector('[data-dashboard-root]');
            if (!root || typeof EventSource === 'undefined') {
                return;
            }

            const formatNumber = (value) => new Intl.NumberFormat('fr-FR').format(Number(value || 0));
            const streamUrl = root.dataset.streamUrl;
            let lastEventId = '0';
            const escapeHtml = (value) => String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#39;');

            const animateCards = (keys = []) => {
                keys.forEach((key) => {
                    document.querySelectorAll(`[data-highlight-card="${key}"]`).forEach((card) => {
                        card.classList.remove('dashboard-card-live');
                        window.requestAnimationFrame(() => {
                            card.classList.add('dashboard-card-live');
                            window.setTimeout(() => card.classList.remove('dashboard-card-live'), 650);
                        });
                    });
                });
            };

            const renderCategories = (rows) => {
                const container = document.querySelector('[data-category-sales]');
                if (!container) {
                    return;
                }

                if (!rows.length) {
                    container.innerHTML = '<div class="rounded-md border border-dashed border-[#3A2424] bg-[#1a0808] p-4 text-sm text-zinc-300">Aucune vente enregistree pour le moment.</div>';
                    return;
                }

                container.innerHTML = rows.map((row) => `
                    <div class="rounded-md border border-[#3A2424] bg-[#1a0808] p-3">
                        <div class="flex items-center justify-between gap-3">
                            <p class="font-medium text-white">${escapeHtml(row.categorie)}</p>
                            <span class="rounded-md bg-[#2B1717] px-2.5 py-1 text-sm font-semibold text-[#EAF270]">
                                ${formatNumber(row.total_vendus)}
                            </span>
                        </div>
                    </div>
                `).join('');
            };

            const applySnapshot = (snapshot) => {
                if (!snapshot) {
                    return;
                }

                const totalUsers = document.querySelector('[data-metric="totalUsers"]');
                if (totalUsers) {
                    totalUsers.textContent = formatNumber(snapshot.totalUsers);
                }

                Object.entries(snapshot.stockSummary || {}).forEach(([key, value]) => {
                    const node = document.querySelector(`[data-stock-metric="${key}"]`);
                    if (node) {
                        node.textContent = formatNumber(value);
                    }
                });

                (snapshot.orderStatusCards || []).forEach((card) => {
                    const node = document.querySelector(`[data-order-metric="${card.key}"]`);
                    if (node) {
                        node.textContent = formatNumber(card.value);
                    }
                });

                renderCategories(snapshot.productsSoldByCategory || []);
            };

            const connect = () => {
                const source = new EventSource(`${streamUrl}?last_event_id=${encodeURIComponent(lastEventId)}`);

                source.addEventListener('dashboard-update', (event) => {
                    lastEventId = event.lastEventId || lastEventId;

                    try {
                        const payload = JSON.parse(event.data);
                        applySnapshot(payload.snapshot);
                        animateCards(payload.context?.highlights || ['orders']);

                        window.dispatchEvent(new CustomEvent('app:toast', {
                            detail: {
                                level: payload.level || 'info',
                                title: 'Mise a jour en direct',
                                message: payload.message,
                                duration: 2800,
                            },
                        }));
                    } catch (error) {
                        window.dispatchEvent(new CustomEvent('app:toast', {
                            detail: {
                                level: 'error',
                                title: 'Synchronisation',
                                message: 'Une mise a jour en direct n a pas pu etre appliquee.',
                            },
                        }));
                    }
                });

                source.onerror = () => {
                    source.close();
                    window.setTimeout(connect, 2500);
                };
            };

            connect();
        });
    </script>
</x-layouts.app>
