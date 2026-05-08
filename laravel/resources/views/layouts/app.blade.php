<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link rel="icon" type="image/svg+xml" href="/favicon.svg">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-zinc-50">
            <livewire:layout.navigation />

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <footer class="mt-8 pb-6 px-4" x-data="{ pixOpen: false, copied: false }">
                <div class="flex items-center justify-center gap-4">
                    <button x-on:click="pixOpen = !pixOpen"
                            class="inline-flex items-center gap-1.5 text-xs text-zinc-300 hover:text-emerald-500 transition-colors">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 512 512">
                            <path d="M242.4 292.5C247.8 287.1 257.1 287.1 262.5 292.5L339.5 369.5C353.7 383.7 372.6 391.5 392.6 391.5H407.7L310.6 488.6C280.3 518.1 231.1 518.1 200.8 488.6L103.3 391.2H118.4C138.4 391.2 157.3 383.4 171.5 369.2L242.4 292.5zM262.5 218.9C256.1 224.4 247.9 224.5 242.4 218.9L165.4 141.9C151.1 127.7 132.3 119.9 112.2 119.9H97.1L194.6 22.5C224.9-7.963 273.1-7.963 304.4 22.5L401.9 119.9H386.7C366.7 119.9 347.8 127.7 333.6 141.9L262.5 218.9zM112.2 142.7C126.4 142.7 139.1 148.3 149.7 158.1L226.7 235.1C233.9 242.3 245.1 242.3 252.3 235.1L329.3 158.1C339.9 147.5 353.7 142.7 367.9 142.7H400.8L480.8 222.7C510.3 252.1 510.3 300.3 480.8 330.6L400.8 410.7H367.9C353.7 410.7 339.1 405 328.5 394.4L251.5 317.4C244.3 310.2 233.1 310.2 225.9 317.4L148.9 394.4C138.3 405 124.5 410.7 110.3 410.7H77.5L-2.5 330.7C-32 301.3-32 253.1-2.5 222.8L77.5 142.7H112.2z"/>
                        </svg>
                        Apoiar via PIX
                    </button>
                    <a href="https://www.linkedin.com/in/ronaldophc/" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-1.5 text-xs text-zinc-300 hover:text-violet-500 transition-colors">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                        Desenvolvido por Ronaldo
                    </a>
                </div>

                {{-- Mini painel PIX --}}
                <div x-show="pixOpen"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mt-3 flex justify-center">
                    <div class="inline-flex items-center gap-3 bg-white border border-zinc-200 rounded-xl px-4 py-2.5 shadow-sm">
                        <span class="text-xs text-zinc-400">PIX</span>
                        <span class="font-mono text-xs font-medium text-zinc-800 select-all">ronaldohortmann02@gmail.com</span>
                        <button x-on:click="navigator.clipboard.writeText('ronaldohortmann02@gmail.com'); copied = true; setTimeout(() => copied = false, 2000)"
                                class="text-xs font-medium transition-colors"
                                x-bind:class="copied ? 'text-emerald-600' : 'text-violet-600 hover:text-violet-800'">
                            <span x-show="!copied">Copiar</span>
                            <span x-show="copied">Copiado ✓</span>
                        </button>
                    </div>
                </div>
            </footer>
        </div>

        <x-notifications />
        <x-confirm-modal />

        @if(session('toast'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    window.dispatchEvent(new CustomEvent('notify', {
                        detail: {
                            message: @js(session('toast.message')),
                            type: @js(session('toast.type', 'success'))
                        }
                    }))
                })
            </script>
        @endif
    </body>
</html>
