<div>
    <x-page-header>
        <div class="flex items-center justify-between gap-4">
            <h1 class="text-xl font-semibold text-zinc-900">Market</h1>
            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ route('market.my-listings') }}" wire:navigate
                       class="text-sm text-zinc-500 px-4 py-2 rounded-lg hover:bg-zinc-100 transition-colors">
                        Meus anúncios
                    </a>
                    <a href="{{ route('market.create') }}" wire:navigate
                       class="bg-violet-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-violet-700 transition-colors">
                        + Anunciar
                    </a>
                @else
                    <a href="{{ route('login') }}" wire:navigate
                       class="text-sm text-zinc-500 px-4 py-2 rounded-lg hover:bg-zinc-100 transition-colors">
                        Entrar para anunciar
                    </a>
                @endauth
            </div>
        </div>
    </x-page-header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        {{-- Filtros --}}
        <div class="flex flex-wrap items-end gap-3">
            <div>
                <input wire:model.live.debounce.200ms="search" type="search"
                       placeholder="Buscar pokémon..."
                       class="border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500 w-44">
            </div>
            <div>
                <select wire:model.live="server"
                        class="border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500">
                    <option value="">Todos os servidores</option>
                    @foreach($servers as $s)
                        <option value="{{ $s->value }}">{{ $s->value }}</option>
                    @endforeach
                </select>
            </div>
<div>
                <select wire:model.live="tm"
                        class="border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500">
                    <option value="">Todas as TMs</option>
                    @foreach(['TM Tank', 'TM DPS', 'TM Burst', 'TM Off-Tank'] as $tmOpt)
                        <option value="{{ $tmOpt }}">{{ $tmOpt }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <input wire:model.live="shinyOnly" id="shinyOnly" type="checkbox"
                       class="rounded border-zinc-300 text-amber-500 shadow-sm focus:ring-amber-400">
                <label for="shinyOnly" class="text-sm text-zinc-600">Shiny</label>
            </div>
            @if($search || $server || $shinyOnly || $tm)
                <button wire:click="clearFilters"
                        class="text-xs text-zinc-400 hover:text-zinc-700 underline transition-colors">
                    Limpar filtros
                </button>
            @endif
        </div>

        {{-- Grid --}}
        @if($listings->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                @foreach($listings as $listing)
                    <a href="{{ route('market.show', $listing) }}" wire:navigate
                       class="bg-white border border-zinc-200 rounded-xl overflow-hidden flex flex-col hover:border-violet-300 hover:shadow-sm transition-all">

                        {{-- Sprite --}}
                        <div class="flex items-center justify-center p-3 h-32 bg-zinc-50 relative" x-data="{ failed: false }">
                            <img
                                x-show="!failed"
                                src="{{ $listing->sprite_url ?: \App\Models\Pokemon::spriteUrl($listing->species) }}"
                                alt="{{ $listing->species }}"
                                class="h-24 w-24 object-contain drop-shadow-sm"
                                x-on:error="failed = true"
                            >
                            <span x-show="failed" class="text-3xl text-zinc-300">?</span>

                            @if($listing->is_shiny)
                                <span class="absolute top-2 right-2 text-xs font-medium bg-amber-50 text-amber-600 border border-amber-200 rounded px-1 py-0.5">
                                    ✦
                                </span>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="p-3 flex flex-col gap-1.5 flex-1">
                            <div>
                                <p class="font-semibold text-zinc-900 text-sm leading-tight">{{ $listing->species }}</p>
                            </div>

                            @if($listing->tm || $listing->held_x_name || $listing->held_y_name)
                                <div class="flex flex-wrap gap-1">
                                    @if($listing->tm)
                                        <span class="text-xs bg-sky-50 text-sky-600 border border-sky-200 rounded px-1 py-0.5 truncate max-w-full">{{ $listing->tm }}</span>
                                    @endif
                                    @if($listing->held_x_name)
                                        <span class="text-xs bg-violet-50 text-violet-600 border border-violet-200 rounded px-1 py-0.5">
                                            {{ $listing->held_x_name }}@if($listing->held_x_tier) T{{ $listing->held_x_tier }}@endif
                                        </span>
                                    @endif
                                    @if($listing->held_y_name)
                                        <span class="text-xs bg-emerald-50 text-emerald-600 border border-emerald-200 rounded px-1 py-0.5">
                                            {{ $listing->held_y_name }}@if($listing->held_y_tier) T{{ $listing->held_y_tier }}@endif
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <div class="mt-auto pt-1.5 border-t border-zinc-100 space-y-1">
                                <div>
                                    <p class="text-xs text-zinc-400">Preço</p>
                                    <p class="text-sm font-bold text-zinc-900">{{ $listing->price }}</p>
                                </div>
                                <div class="flex items-center justify-between gap-1">
                                    <span class="text-xs text-zinc-400 truncate">{{ $listing->contact_nick }}</span>
                                    <span class="text-xs text-zinc-300 shrink-0">{{ $listing->server->value }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $listings->links() }}
            </div>
        @else
            <x-card class="text-center py-16">
                <p class="text-zinc-400 text-sm">Nenhum anúncio encontrado.</p>
                @if($search || $server || $shinyOnly || $tm)
                    <button wire:click="clearFilters"
                            class="mt-3 text-sm text-violet-600 hover:text-violet-700 font-medium">
                        Limpar filtros
                    </button>
                @endif
            </x-card>
        @endif

    </div>
</div>
