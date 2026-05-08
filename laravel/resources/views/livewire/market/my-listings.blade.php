<div>
    <x-page-header>
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('market.index') }}" wire:navigate class="text-zinc-400 hover:text-zinc-600 transition-colors">&larr;</a>
                <h1 class="text-xl font-semibold text-zinc-900">Meus anúncios</h1>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs text-zinc-400">{{ $activeCount }}/{{ $maxActive }} ativos</span>
                @if($activeCount < $maxActive)
                    <a href="{{ route('market.create') }}" wire:navigate
                       class="bg-violet-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-violet-700 transition-colors">
                        + Anunciar
                    </a>
                @endif
            </div>
        </div>
    </x-page-header>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Tabs --}}
        <div class="flex items-center gap-1 bg-zinc-100 rounded-lg p-1 w-fit">
            @foreach(['active' => 'Ativos', 'sold' => 'Vendidos', 'expired' => 'Expirados'] as $val => $label)
                <button wire:click="$set('tab', '{{ $val }}')"
                        @class([
                            'px-4 py-1.5 rounded-md text-sm font-medium transition-colors',
                            'bg-white shadow-sm text-zinc-900' => $tab === $val,
                            'text-zinc-500 hover:text-zinc-700' => $tab !== $val,
                        ])>
                    {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- Listagem --}}
        @forelse($listings as $listing)
            <x-card class="flex items-center gap-4">
                {{-- Sprite --}}
                <div class="flex items-center justify-center w-16 h-16 bg-zinc-50 rounded-lg shrink-0" x-data="{ failed: false }">
                    <img
                        x-show="!failed"
                        src="{{ \App\Models\Pokemon::spriteUrl($listing->species) }}"
                        alt="{{ $listing->species }}"
                        class="h-14 w-14 object-contain drop-shadow-sm"
                        x-on:error="failed = true"
                    >
                    <span x-show="failed" class="text-2xl text-zinc-300">?</span>
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-semibold text-zinc-900 text-sm">{{ $listing->species }}</span>
                        @if($listing->is_shiny)
                            <span class="text-xs bg-amber-50 text-amber-600 border border-amber-200 rounded px-1 py-0.5">✦ Shiny</span>
                        @endif
                        <span class="text-xs text-zinc-400">{{ $listing->server->value }}</span>
                    </div>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-sm font-bold text-zinc-900">{{ number_format($listing->price, 0, ',', '.') }}</span>
                        @if($listing->isActive())
                            <span class="text-xs text-zinc-400">Expira {{ $listing->expires_at->diffForHumans() }}</span>
                        @elseif($tab === 'expired')
                            <span class="text-xs text-zinc-400">Expirou {{ $listing->expires_at->diffForHumans() }}</span>
                        @endif
                    </div>
                </div>

                {{-- Ações --}}
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('market.show', $listing) }}" wire:navigate
                       class="text-xs text-zinc-400 hover:text-zinc-700 transition-colors px-2 py-1 rounded hover:bg-zinc-100">
                        Ver
                    </a>
                    @if($listing->isActive())
                        <button wire:click="markSold({{ $listing->id }})"
                                wire:confirm="Marcar como vendido?"
                                wire:loading.attr="disabled" wire:target="markSold({{ $listing->id }})"
                                class="text-xs text-violet-600 hover:text-violet-800 transition-colors px-2 py-1 rounded hover:bg-violet-50 disabled:opacity-50">
                            <span wire:loading.remove wire:target="markSold({{ $listing->id }})">Vendido</span>
                            <span wire:loading wire:target="markSold({{ $listing->id }})">...</span>
                        </button>
                    @endif
                    <button wire:click="delete({{ $listing->id }})"
                            wire:confirm="Remover este anúncio?"
                            wire:loading.attr="disabled" wire:target="delete({{ $listing->id }})"
                            class="text-xs text-red-300 hover:text-red-500 transition-colors px-2 py-1 rounded hover:bg-red-50 disabled:opacity-50">
                        <span wire:loading.remove wire:target="delete({{ $listing->id }})">Remover</span>
                        <span wire:loading wire:target="delete({{ $listing->id }})">...</span>
                    </button>
                </div>
            </x-card>
        @empty
            <x-card class="text-center py-12">
                <p class="text-zinc-400 text-sm">
                    @if($tab === 'active')
                        Nenhum anúncio ativo.
                    @elseif($tab === 'sold')
                        Nenhum anúncio vendido.
                    @else
                        Nenhum anúncio expirado.
                    @endif
                </p>
                @if($tab === 'active' && $activeCount < $maxActive)
                    <a href="{{ route('market.create') }}" wire:navigate
                       class="mt-3 inline-block text-sm text-violet-600 hover:text-violet-700 font-medium">
                        Criar primeiro anúncio →
                    </a>
                @endif
            </x-card>
        @endforelse

    </div>
</div>
