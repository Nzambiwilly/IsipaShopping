<x-layouts.app :title="'Connexion | ISIPA Shopping'">
    <section class="flex justify-center">
        <article class="w-full max-w-xl rounded-lg border border-[#3A2424] bg-[#210f0f] p-5">
            <a href="{{ route('catalogue') }}" class="mb-4 inline-flex items-center gap-2 rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm font-medium text-white transition hover:border-[#EAF270]">
                <x-ui.icon name="arrow-left" class="h-4 w-4" />
                <span>Retour au catalogue</span>
            </a>
            <p class="text-sm font-semibold text-[#EAF270]">Authentification</p>
            <h1 class="mt-1 text-2xl font-bold">Connexion client</h1>

            <form action="{{ route('login.attempt') }}" method="POST" class="mt-4 flex flex-col gap-3">
                @csrf
                <label class="flex flex-col gap-1.5">
                    <span class="text-sm text-zinc-300">Email</span>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none ring-0 focus:border-[#EAF270]">
                </label>

                <label class="flex flex-col gap-1.5">
                    <span class="text-sm text-zinc-300">Mot de passe</span>
                    <input type="password" name="password" required class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none ring-0 focus:border-[#EAF270]">
                </label>

                <label class="inline-flex items-center gap-2 text-sm text-zinc-300">
                    <input type="checkbox" name="remember" value="1" class="size-4 rounded border-[#3A2424] bg-[#1a0808] text-[#EAF270]">
                    <span>Rester connecte</span>
                </label>

                <button type="submit" class="w-fit rounded-md bg-[#EAF270] px-4 py-2 text-sm font-semibold text-[#1B1B1B] transition hover:bg-[#d6de5f]">Se connecter</button>
            </form>

            <p class="mt-4 text-sm text-zinc-300">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="font-semibold text-[#EAF270] hover:text-[#d6de5f]">Inscription</a>
            </p>
        </article>
    </section>
</x-layouts.app>
