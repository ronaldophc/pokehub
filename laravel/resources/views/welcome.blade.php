<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PokeHub — Gerencie os pokémons da sua equipe</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-zinc-900">

    {{-- Nav --}}
    <nav class="fixed top-0 inset-x-0 z-50 border-b border-zinc-100 bg-white/80 backdrop-blur-sm">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-14 flex items-center justify-between">
            <span class="text-lg font-bold tracking-tight text-zinc-900">Poke<span class="text-violet-500">Hub</span></span>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('houses.index') }}"
                       class="bg-violet-600 text-white px-4 py-1.5 rounded-lg text-sm font-medium hover:bg-violet-700 transition-colors">
                        Minhas Houses
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="text-sm text-zinc-500 hover:text-zinc-900 transition-colors">
                        Entrar
                    </a>
                    <a href="{{ route('register') }}"
                       class="bg-violet-600 text-white px-4 py-1.5 rounded-lg text-sm font-medium hover:bg-violet-700 transition-colors">
                        Criar conta
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="pt-36 pb-16 px-4 text-center">
        <div class="max-w-3xl mx-auto">
            <div class="inline-flex items-center gap-1.5 bg-violet-50 text-violet-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-6 tracking-wide uppercase">
                Para jogadores de PokeXGames
            </div>

            <h1 class="text-4xl sm:text-5xl font-bold leading-tight tracking-tight">
                O acervo de pokémons<br class="hidden sm:block">
                da sua equipe, organizado
            </h1>

            <p class="mt-5 text-lg text-zinc-500 max-w-xl mx-auto leading-relaxed">
                Crie uma house, cadastre seus pokémons e deixe os membros pegarem e devolverem em tempo real.
            </p>

            <div class="mt-8 flex items-center justify-center gap-5">
                @auth
                    <a href="{{ route('houses.index') }}"
                       class="bg-violet-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-violet-700 transition-colors shadow-sm">
                        Ir para minhas houses
                    </a>
                @else
                    <a href="{{ route('register') }}"
                       class="bg-violet-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-violet-700 transition-colors shadow-sm">
                        Começar agora
                    </a>
                    <a href="{{ route('login') }}"
                       class="text-sm font-medium text-zinc-500 hover:text-zinc-800 transition-colors">
                        Já tenho conta &rarr;
                    </a>
                @endauth
            </div>
        </div>

        {{-- Sprites decorativos (Gen 1) --}}
        <div class="mt-14 flex justify-center items-end gap-6 sm:gap-10">
            @foreach(['charizard', 'blastoise', 'venusaur', 'dragonite', 'mewtwo', 'gengar'] as $name)
                <img
                    src="https://img.pokemondb.net/sprites/red-blue/normal/{{ $name }}.png"
                    alt="{{ $name }}"
                    style="image-rendering: pixelated;"
                    class="h-16 sm:h-24 w-auto object-contain opacity-80"
                    onerror="this.style.display='none'"
                >
            @endforeach
        </div>
    </section>

    {{-- Features --}}
    <section class="bg-zinc-50 border-t border-zinc-100 py-20 px-4">
        <div class="max-w-5xl mx-auto grid sm:grid-cols-3 gap-10">

            <div class="space-y-3">
                <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-zinc-900">Houses</h3>
                <p class="text-sm text-zinc-500 leading-relaxed">
                    Crie uma house para sua equipe ou entre em uma com código de convite. Gerencie membros e permissões com facilidade.
                </p>
            </div>

            <div class="space-y-3">
                <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-zinc-900">Empréstimo de pokémons</h3>
                <p class="text-sm text-zinc-500 leading-relaxed">
                    Membros pegam e devolvem pokémons do acervo da house. Sempre visível quem está usando qual pokémon e há quanto tempo.
                </p>
            </div>

            <div class="space-y-3">
                <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-zinc-900">Atualizações em tempo real</h3>
                <p class="text-sm text-zinc-500 leading-relaxed">
                    Quando alguém pega ou devolve um pokémon, todos os membros veem na hora — sem precisar recarregar a página.
                </p>
            </div>

        </div>
    </section>

    {{-- Como funciona --}}
    <section class="py-20 px-4">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-2xl font-bold text-center mb-12">Como funciona</h2>

            <div class="space-y-10">
                <div class="flex gap-5">
                    <div class="w-8 h-8 rounded-full bg-violet-600 text-white text-sm font-bold flex items-center justify-center shrink-0 mt-0.5">1</div>
                    <div>
                        <p class="font-semibold text-zinc-900">Crie sua house</p>
                        <p class="text-sm text-zinc-500 mt-1">Registre uma house para sua equipe e convide os membros pelo código de convite.</p>
                    </div>
                </div>

                <div class="flex gap-5">
                    <div class="w-8 h-8 rounded-full bg-violet-600 text-white text-sm font-bold flex items-center justify-center shrink-0 mt-0.5">2</div>
                    <div>
                        <p class="font-semibold text-zinc-900">Cadastre os pokémons</p>
                        <p class="text-sm text-zinc-500 mt-1">Adicione os pokémons disponíveis para empréstimo. A sprite é buscada automaticamente.</p>
                    </div>
                </div>

                <div class="flex gap-5">
                    <div class="w-8 h-8 rounded-full bg-violet-600 text-white text-sm font-bold flex items-center justify-center shrink-0 mt-0.5">3</div>
                    <div>
                        <p class="font-semibold text-zinc-900">Membros pegam e devolvem</p>
                        <p class="text-sm text-zinc-500 mt-1">Cada membro vê quais pokémons estão livres e pode pegar o que precisar. Ao terminar, basta devolver.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA final --}}
    @guest
    <section class="bg-zinc-900 py-20 px-4 text-center">
        <div class="max-w-lg mx-auto space-y-5">
            <h2 class="text-2xl font-bold text-white">Pronto para organizar sua equipe?</h2>
            <p class="text-zinc-400 text-sm">Gratuito. Sem complicação.</p>
            <a href="{{ route('register') }}"
               class="inline-block bg-violet-500 text-white px-8 py-3 rounded-xl font-semibold hover:bg-violet-400 transition-colors">
                Criar conta grátis
            </a>
        </div>
    </section>
    @endguest

    {{-- Doação --}}
    <section class="bg-zinc-50 border-t border-zinc-100 py-12 px-4">
        <div class="max-w-xl mx-auto text-center">
            <p class="text-lg font-semibold text-zinc-800">Gostou do PokeHub?</p>
            <p class="text-sm text-zinc-500 mt-1 mb-5">O site é gratuito e mantido do próprio bolso. Se quiser ajudar com os custos do servidor, qualquer valor é muito bem-vindo.</p>
            <div class="inline-flex items-center gap-3 bg-white border border-zinc-200 rounded-xl px-5 py-3 shadow-sm">
                <span class="text-sm text-zinc-500">PIX</span>
                <span class="font-mono text-sm font-medium text-zinc-900 select-all">ronaldohortmann02@gmail.com</span>
                <button onclick="
                    navigator.clipboard.writeText('ronaldohortmann02@gmail.com');
                    var btn = this;
                    btn.querySelector('.lbl-copy').style.display = 'none';
                    btn.querySelector('.lbl-copied').style.display = 'inline';
                    setTimeout(function() {
                        btn.querySelector('.lbl-copy').style.display = 'inline';
                        btn.querySelector('.lbl-copied').style.display = 'none';
                    }, 2000);
                " class="text-xs font-medium transition-colors whitespace-nowrap text-violet-600 hover:text-violet-800">
                    <span class="lbl-copy">Copiar</span>
                    <span class="lbl-copied text-emerald-600" style="display:none">Copiado ✓</span>
                </button>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-zinc-100 py-8 px-4">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <span class="text-sm font-bold tracking-tight text-zinc-400">Poke<span class="text-violet-400">Hub</span></span>
            <a href="https://www.linkedin.com/in/ronaldophc/" target="_blank" rel="noopener noreferrer"
               class="flex items-center gap-1.5 text-xs text-zinc-400 hover:text-violet-500 transition-colors">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                </svg>
                Desenvolvido por Ronaldo
            </a>
        </div>
    </footer>

</body>
</html>
