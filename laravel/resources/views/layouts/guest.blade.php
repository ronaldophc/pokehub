<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PokeHub') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-zinc-900 min-h-screen flex items-center justify-center px-4 py-12">

    {{-- Sprites decorativos de fundo --}}
    <div class="fixed inset-0 flex items-end justify-between px-8 pb-0 pointer-events-none select-none overflow-hidden">
        <img src="https://img.pokemondb.net/sprites/red-blue/normal/gengar.png"
             style="image-rendering: pixelated;"
             class="h-24 opacity-10 mb-0">
        <img src="https://img.pokemondb.net/sprites/red-blue/normal/mewtwo.png"
             style="image-rendering: pixelated;"
             class="h-24 opacity-10 mb-0">
    </div>

    <div class="w-full max-w-sm relative z-10">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="/" wire:navigate>
                <span class="text-3xl font-bold tracking-tight text-white">Poke<span class="text-violet-400">Hub</span></span>
            </a>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-xl px-8 py-8">
            {{ $slot }}
        </div>

    </div>

</body>
</html>
