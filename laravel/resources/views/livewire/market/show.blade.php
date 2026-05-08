<div>
    <x-page-header>
        <div class="flex items-center gap-3">
            <a href="{{ route('market.index') }}" wire:navigate class="text-zinc-400 hover:text-zinc-600 transition-colors">&larr;</a>
            <h1 class="text-xl font-semibold text-zinc-900">Anúncio</h1>
        </div>
    </x-page-header>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <x-card>
            <div class="flex flex-col sm:flex-row gap-6">

                {{-- Sprite + screenshot --}}
                <div class="flex flex-col items-center gap-3 sm:w-44 shrink-0">
                    <div class="flex items-center justify-center w-40 h-40 bg-zinc-50 rounded-xl border border-zinc-100" x-data="{ failed: false }">
                        <img
                            x-show="!failed"
                            src="{{ $spriteUrl }}"
                            alt="{{ $listing->species }}"
                            class="h-32 w-32 object-contain drop-shadow-sm"
                            x-on:error="failed = true"
                        >
                        <span x-show="failed" class="text-4xl text-zinc-300">?</span>
                    </div>

                    @if($listing->screenshot_path)
                        <a href="{{ Storage::disk('public')->url($listing->screenshot_path) }}"
                           target="_blank" rel="noopener"
                           class="block w-40">
                            <img src="{{ Storage::disk('public')->url($listing->screenshot_path) }}"
                                 alt="Print do pokémon"
                                 class="w-full rounded-lg border border-zinc-200 hover:border-violet-300 transition-colors object-cover">
                        </a>
                        <p class="text-xs text-zinc-400">Print do look</p>
                    @endif
                </div>

                {{-- Dados --}}
                <div class="flex-1 space-y-4">

                    {{-- Header --}}
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-lg font-bold text-zinc-900">{{ $listing->species }}</h2>
                                @if($listing->is_shiny)
                                    <span class="text-xs font-medium bg-amber-50 text-amber-600 border border-amber-200 rounded px-1.5 py-0.5">✦ Shiny</span>
                                @endif
                            </div>
                            @if($listing->name)
                                <p class="text-sm text-zinc-400">"{{ $listing->name }}"</p>
                            @endif
                            <p class="text-sm text-zinc-500 mt-0.5">Lv. {{ $listing->level }}</p>
                        </div>

                        {{-- Status badge --}}
                        @php
                            $statusClass = match($listing->status) {
                                \App\Enums\MarketStatus::Active  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                \App\Enums\MarketStatus::Sold    => 'bg-violet-50 text-violet-700 border-violet-200',
                                \App\Enums\MarketStatus::Expired => 'bg-zinc-100 text-zinc-400 border-zinc-200',
                            };
                            $statusLabel = match($listing->status) {
                                \App\Enums\MarketStatus::Active  => 'Ativo',
                                \App\Enums\MarketStatus::Sold    => 'Vendido',
                                \App\Enums\MarketStatus::Expired => 'Expirado',
                            };
                        @endphp
                        <span class="text-xs font-medium border rounded-full px-2.5 py-1 shrink-0 {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    {{-- Equipamentos --}}
                    @if($listing->tm || $listing->held_x_name || $listing->held_y_name)
                        <div class="flex flex-wrap gap-1.5">
                            @if($listing->tm)
                                <span class="inline-flex items-center text-xs font-medium bg-sky-50 text-sky-600 border border-sky-200 rounded px-2 py-1">{{ $listing->tm }}</span>
                            @endif
                            @if($listing->held_x_name)
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium bg-violet-50 text-violet-600 border border-violet-200 rounded px-2 py-1">
                                    <img src="/images/helds/tiers/{{ strtolower($listing->held_x_name) }}-t{{ $listing->held_x_tier ?? 1 }}.png" class="h-5 w-5 object-contain" onerror="this.style.display='none'">
                                    {{ $listing->held_x_name }}@if($listing->held_x_tier) T{{ $listing->held_x_tier }}@endif
                                </span>
                            @endif
                            @if($listing->held_y_name)
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium bg-emerald-50 text-emerald-600 border border-emerald-200 rounded px-2 py-1">
                                    <img src="/images/helds/tiers/{{ strtolower($listing->held_y_name) }}-t{{ $listing->held_y_tier ?? 1 }}.png" class="h-5 w-5 object-contain" onerror="this.style.display='none'">
                                    {{ $listing->held_y_name }}@if($listing->held_y_tier) T{{ $listing->held_y_tier }}@endif
                                </span>
                            @endif
                        </div>
                    @endif

                    {{-- Preço --}}
                    <div class="bg-zinc-50 rounded-xl px-4 py-3">
                        <p class="text-xs text-zinc-400 mb-0.5">Preço</p>
                        <p class="text-2xl font-bold text-zinc-900">{{ number_format($listing->price, 0, ',', '.') }}</p>
                        <p class="text-xs text-zinc-400 mt-0.5">dinheiro in-game · Servidor {{ $listing->server->value }}</p>
                    </div>

                    {{-- Contato --}}
                    <div class="border border-zinc-100 rounded-xl px-4 py-3 space-y-2">
                        <p class="text-xs font-medium text-zinc-500 uppercase tracking-wide">Contato</p>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-zinc-400">Nick:</span>
                            <span class="text-sm font-medium text-zinc-800 select-all">{{ $listing->contact_nick }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-zinc-400">Servidor:</span>
                            <span class="text-sm font-medium text-zinc-800">{{ $listing->server->value }}</span>
                        </div>
                        @if($listing->contact_discord)
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-zinc-400">Discord:</span>
                                <span class="text-sm font-medium text-zinc-800 select-all">{{ $listing->contact_discord }}</span>
                            </div>
                        @endif
                    </div>

                    @if($listing->notes)
                        <div>
                            <p class="text-xs font-medium text-zinc-500 uppercase tracking-wide mb-1">Observações</p>
                            <p class="text-sm text-zinc-600">{{ $listing->notes }}</p>
                        </div>
                    @endif

                    {{-- Meta --}}
                    <div class="flex items-center justify-between text-xs text-zinc-400 pt-2 border-t border-zinc-100">
                        <span>Publicado {{ $listing->created_at->diffForHumans() }}</span>
                        @if($listing->isActive())
                            <span>Expira {{ $listing->expires_at->diffForHumans() }}</span>
                        @endif
                    </div>

                    {{-- Ações do dono --}}
                    @auth
                        @if(auth()->id() === $listing->user_id)
                            <div class="flex gap-2 pt-2 border-t border-zinc-100">
                                @if($listing->isActive())
                                    <button wire:click="markSold"
                                            wire:confirm="Marcar este anúncio como vendido?"
                                            wire:loading.attr="disabled" wire:target="markSold"
                                            class="text-sm px-4 py-2 rounded-lg bg-violet-50 text-violet-700 hover:bg-violet-100 transition-colors disabled:opacity-50 font-medium">
                                        <span wire:loading.remove wire:target="markSold">Marcar como vendido</span>
                                        <span wire:loading wire:target="markSold">Marcando...</span>
                                    </button>
                                @endif
                                <button wire:click="delete"
                                        wire:confirm="Remover este anúncio?"
                                        wire:loading.attr="disabled" wire:target="delete"
                                        class="text-sm px-4 py-2 rounded-lg text-red-400 hover:bg-red-50 hover:text-red-600 transition-colors disabled:opacity-50">
                                    <span wire:loading.remove wire:target="delete">Remover</span>
                                    <span wire:loading wire:target="delete">Removendo...</span>
                                </button>
                            </div>
                        @endif
                    @endauth

                </div>
            </div>
        </x-card>
    </div>
</div>
