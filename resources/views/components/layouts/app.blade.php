<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'ISIPA Shopping' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#0E0101] text-white">
@php
    $cartCount = auth()->check() ? array_sum((array) session('cart_' . auth()->id(), [])) : 0;
@endphp
<div class="min-h-screen">
    <header class="sticky top-0 z-20 border-b border-[#3A2424] bg-[#140707]/95 backdrop-blur">
        <div class="mx-auto flex w-[92%] max-w-6xl flex-wrap items-center justify-between gap-4 py-3">
            <a href="{{ route('catalogue') }}" class="inline-flex items-center gap-2 font-semibold text-white">
                <span class="rounded-md bg-[#EAF270] px-2 py-1 text-xs font-bold text-[#1f1f1f]">ISIPA</span>
                <span>Shopping</span>
            </a>

            <nav class="flex items-center gap-2 text-sm">
                <a
                    href="{{ route('catalogue') }}"
                    @class([
                        'rounded-md px-3 py-1.5 transition',
                        'bg-[#2a1616] text-white' => request()->routeIs('catalogue', 'catalogue.index'),
                        'text-zinc-300 hover:bg-[#2a1616] hover:text-white' => !request()->routeIs('catalogue', 'catalogue.index'),
                    ])
                >
                    Catalogue
                </a>
                <a
                    href="{{ route('contact.index') }}"
                    @class([
                        'rounded-md px-3 py-1.5 transition',
                        'bg-[#2a1616] text-white' => request()->routeIs('contact.*'),
                        'text-zinc-300 hover:bg-[#2a1616] hover:text-white' => !request()->routeIs('contact.*'),
                    ])
                >
                    Contact
                </a>
            </nav>

            <div class="flex items-center gap-2">
                @auth
                    @if (auth()->user()->hasRole('superadmin', 'admin', 'editor'))
                        <a href="{{ route('admin.produits.index') }}" class="inline-flex items-center gap-2 rounded-md border border-[#3A2424] bg-[#210f0f] px-3 py-2 text-sm font-medium text-white transition hover:border-[#EAF270]">
                            <x-ui.icon name="admin" class="h-4 w-4" />
                            <span>Admin</span>
                        </a>
                    @endif
                    <a href="{{ route('panier.index') }}" class="inline-flex items-center gap-2 rounded-md border border-[#3A2424] bg-[#210f0f] px-3 py-2 text-sm font-medium text-white transition hover:border-[#EAF270]">
                        <x-ui.icon name="cart" class="h-4 w-4" />
                        <span>Panier ({{ $cartCount }})</span>
                    </a>
                    <span class="rounded-md border border-[#3A2424] bg-[#210f0f] px-3 py-1.5 text-sm text-zinc-100">{{ auth()->user()->nom_complet }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-md border border-[#3A2424] bg-[#210f0f] px-3 py-2 text-sm font-medium text-white transition hover:border-[#EAF270]">
                            Se deconnecter
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="rounded-md border border-[#3A2424] bg-[#210f0f] px-3 py-2 text-sm font-medium text-white transition hover:border-[#EAF270]">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="rounded-md bg-[#EAF270] px-3 py-2 text-sm font-semibold text-[#1B1B1B] transition hover:bg-[#d6de5f]">
                        Inscription
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="mx-auto w-[92%] max-w-6xl py-6 md:py-8">
        @if (session('success'))
            <div class="mb-4 rounded-md border border-emerald-300 bg-emerald-100 px-4 py-2 text-sm font-medium text-emerald-900">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-md border border-red-300 bg-red-100 px-4 py-2 text-sm font-medium text-red-900">
                {{ $errors->first() }}
            </div>
        @endif

        {{ $slot }}
    </main>
</div>
</body>
</html>
