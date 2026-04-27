<x-layouts.app :title="'Inscription | ISIPA Shopping'">
    <section class="flex justify-center">
        <article class="w-full max-w-xl rounded-lg border border-[#3A2424] bg-[#210f0f] p-5">
            <p class="text-sm font-semibold text-[#EAF270]">Nouveau compte</p>
            <h1 class="mt-1 text-2xl font-bold">Inscription client</h1>

            <form action="{{ route('register.store') }}" method="POST" class="mt-4 flex flex-col gap-3">
                @csrf
                <label class="flex flex-col gap-1.5">
                    <span class="text-sm text-zinc-300">Nom complet</span>
                    <input type="text" name="nom_complet" value="{{ old('nom_complet') }}" required class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none ring-0 focus:border-[#EAF270]">
                </label>

                <label class="flex flex-col gap-1.5">
                    <span class="text-sm text-zinc-300">Email</span>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none ring-0 focus:border-[#EAF270]">
                </label>

                <label class="flex flex-col gap-1.5">
                    <span class="text-sm text-zinc-300">Mot de passe</span>
                    <input type="password" name="password" required class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none ring-0 focus:border-[#EAF270]">
                </label>

                <label class="flex flex-col gap-1.5">
                    <span class="text-sm text-zinc-300">Confirmer le mot de passe</span>
                    <input type="password" name="password_confirmation" required class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none ring-0 focus:border-[#EAF270]">
                </label>

                <button type="submit" class="w-fit rounded-md bg-[#EAF270] px-4 py-2 text-sm font-semibold text-[#1B1B1B] transition hover:bg-[#d6de5f]">Creer mon compte</button>
            </form>

            <p class="mt-4 text-sm text-zinc-300">
                Vous avez deja un compte ?
                <a href="{{ route('login') }}" class="font-semibold text-[#EAF270] hover:text-[#d6de5f]">Connexion</a>
            </p>
        </article>
    </section>
</x-layouts.app>
