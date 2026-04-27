<x-layouts.app :title="'Contact | ISIPA Shopping'">
    <section class="mb-6">
        <div>
            <p class="text-sm font-semibold text-[#EAF270]">Contact</p>
            <h1 class="mt-1 text-2xl font-bold leading-tight md:text-4xl">Parlons de votre besoin informatique</h1>
            <p class="mt-2 max-w-3xl text-sm text-zinc-300 md:text-base">Notre equipe est disponible pour les demandes commerciales, devis et informations sur les produits.</p>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-4 lg:grid-cols-[1.3fr_1fr]">
        <article class="rounded-lg border border-[#3A2424] bg-[#210f0f] p-4">
            <h2 class="text-xl font-semibold">Envoyer un message</h2>
            <form action="{{ route('contact.store') }}" method="POST" class="mt-3 flex flex-col gap-3">
                @csrf
                <label class="flex flex-col gap-1.5">
                    <span class="text-sm text-zinc-300">Nom complet</span>
                    <input type="text" name="nom" value="{{ old('nom') }}" required class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none ring-0 focus:border-[#EAF270]">
                </label>
                <label class="flex flex-col gap-1.5">
                    <span class="text-sm text-zinc-300">Adresse email</span>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none ring-0 focus:border-[#EAF270]">
                </label>
                <label class="flex flex-col gap-1.5">
                    <span class="text-sm text-zinc-300">Message</span>
                    <textarea name="message" rows="5" required class="w-full rounded-md border border-[#3A2424] bg-[#1a0808] px-3 py-2 text-sm text-white outline-none ring-0 focus:border-[#EAF270]">{{ old('message') }}</textarea>
                </label>
                <button type="submit" class="w-fit rounded-md bg-[#EAF270] px-4 py-2 text-sm font-semibold text-[#1B1B1B] transition hover:bg-[#d6de5f]">Envoyer</button>
            </form>
        </article>

        <article class="rounded-lg border border-[#3A2424] bg-[#210f0f] p-4">
            <h2 class="text-xl font-semibold">Coordonnees ISIPA</h2>
            <div class="mt-3 space-y-2 text-sm text-zinc-300">
                <p><strong class="text-white">Telephone:</strong> +243 999 271 690</p>
                <p><strong class="text-white">Email:</strong> contact@isipa.cd</p>
                <p><strong class="text-white">Inscription:</strong> inscription@isipa.cd</p>
                <p><strong class="text-white">Adresse principale:</strong> Ndeg 7518, Avenue Shaumba, Kinshasa / Gombe</p>
            </div>
            <a href="https://isipa.cd/contact/" target="_blank" rel="noopener" class="mt-4 inline-flex rounded-md border border-[#3A2424] bg-[#1a0808] px-4 py-2 text-sm font-medium text-white transition hover:border-[#EAF270]">
                Voir la page officielle ISIPA
            </a>
        </article>
    </section>
</x-layouts.app>
