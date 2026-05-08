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
                    @if($listing->tm || $listing->held_x_name || $listing->held_y_name || $listing->extra_helds)
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
                            @foreach($listing->extra_helds ?? [] as $extra)
                                @if(!empty($extra['name']))
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium bg-zinc-50 text-zinc-500 border border-zinc-200 rounded px-2 py-1">
                                        <img src="/images/helds/tiers/{{ strtolower($extra['name']) }}-t{{ $extra['tier'] ?? 1 }}.png" class="h-5 w-5 object-contain" onerror="this.style.display='none'">
                                        {{ $extra['name'] }}@if(!empty($extra['tier'])) T{{ $extra['tier'] }}@endif
                                        <span class="text-zinc-400">(inativo)</span>
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    {{-- Preço --}}
                    <div class="bg-zinc-50 rounded-xl px-4 py-3">
                        <p class="text-xs text-zinc-400 mb-0.5">Preço</p>
                        <p class="text-2xl font-bold text-zinc-900">{{ $listing->price }}</p>
                        <p class="text-xs text-zinc-400 mt-0.5">Servidor {{ $listing->server->value }}</p>
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
                                <a href="{{ route('market.edit', $listing) }}" wire:navigate
                                   class="text-sm px-4 py-2 rounded-lg text-zinc-500 hover:bg-zinc-100 transition-colors">
                                    Editar
                                </a>
                                @if($listing->isActive())
                                    <button x-on:click="$dispatch('open-confirm', {
                                                message: 'Marcar este anúncio como vendido?',
                                                type: 'info',
                                                action: () => $wire.markSold()
                                            })"
                                            wire:loading.attr="disabled" wire:target="markSold"
                                            class="text-sm px-4 py-2 rounded-lg bg-violet-50 text-violet-700 hover:bg-violet-100 transition-colors disabled:opacity-50 font-medium">
                                        <span wire:loading.remove wire:target="markSold">Marcar como vendido</span>
                                        <span wire:loading wire:target="markSold">Marcando...</span>
                                    </button>
                                @endif
                                <button x-on:click="$dispatch('open-confirm', {
                                            message: 'Remover este anúncio?',
                                            type: 'danger',
                                            action: () => $wire.delete()
                                        })"
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

        {{-- Ofertas --}}
        <div class="mt-6 space-y-4">

            @if($isOwner)
                {{-- Dono: lista de ofertas recebidas --}}
                <h2 class="text-sm font-semibold text-zinc-700">
                    Ofertas recebidas
                    @if($offers->isNotEmpty())
                        <span class="ml-1.5 text-xs font-medium bg-violet-100 text-violet-600 rounded-full px-2 py-0.5">{{ $offers->count() }}</span>
                    @endif
                </h2>

                @forelse($offers as $offer)
                    @php
                        $statusClass = match($offer->status) {
                            \App\Enums\MarketOfferStatus::Pending  => 'bg-amber-50 text-amber-600 border-amber-200',
                            \App\Enums\MarketOfferStatus::Accepted => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            \App\Enums\MarketOfferStatus::Rejected => 'bg-zinc-100 text-zinc-400 border-zinc-200',
                        };
                        $statusLabel = match($offer->status) {
                            \App\Enums\MarketOfferStatus::Pending  => 'Pendente',
                            \App\Enums\MarketOfferStatus::Accepted => 'Aceita',
                            \App\Enums\MarketOfferStatus::Rejected => 'Recusada',
                        };
                    @endphp
                    <x-card class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-medium text-zinc-900">{{ $offer->user->name }}</span>
                                <span class="text-xs border rounded-full px-2 py-0.5 {{ $statusClass }}">{{ $statusLabel }}</span>
                            </div>
                            <p class="text-sm text-zinc-600 leading-relaxed">{{ $offer->message }}</p>
                            <p class="text-xs text-zinc-400 mt-1">{{ $offer->created_at->diffForHumans() }}</p>
                        </div>
                        @if($offer->status === \App\Enums\MarketOfferStatus::Pending && $listing->isActive())
                            <div class="flex gap-2 shrink-0">
                                <button x-on:click="$dispatch('open-confirm', {
                                            message: 'Aceitar oferta de {{ $offer->user->name }}? O anúncio será marcado como vendido.',
                                            type: 'info',
                                            action: () => $wire.acceptOffer({{ $offer->id }})
                                        })"
                                        wire:loading.attr="disabled" wire:target="acceptOffer({{ $offer->id }})"
                                        class="text-xs px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-medium transition-colors disabled:opacity-50">
                                    Aceitar
                                </button>
                                <button x-on:click="$dispatch('open-confirm', {
                                            message: 'Recusar oferta de {{ $offer->user->name }}?',
                                            type: 'danger',
                                            action: () => $wire.rejectOffer({{ $offer->id }})
                                        })"
                                        wire:loading.attr="disabled" wire:target="rejectOffer({{ $offer->id }})"
                                        class="text-xs px-3 py-1.5 rounded-lg text-zinc-400 hover:bg-zinc-100 hover:text-zinc-600 transition-colors disabled:opacity-50">
                                    Recusar
                                </button>
                            </div>
                        @endif
                    </x-card>
                @empty
                    <x-card class="text-center py-8">
                        <p class="text-sm text-zinc-400">Nenhuma oferta recebida ainda.</p>
                    </x-card>
                @endforelse

            @elseauth
                {{-- Visitante autenticado: formulário ou status da oferta --}}
                @if($listing->isActive())
                    @if($myOffer)
                        @php
                            $myStatusClass = match($myOffer->status) {
                                \App\Enums\MarketOfferStatus::Pending  => 'bg-amber-50 border-amber-200 text-amber-700',
                                \App\Enums\MarketOfferStatus::Accepted => 'bg-emerald-50 border-emerald-200 text-emerald-700',
                                \App\Enums\MarketOfferStatus::Rejected => 'bg-zinc-50 border-zinc-200 text-zinc-500',
                            };
                            $myStatusMsg = match($myOffer->status) {
                                \App\Enums\MarketOfferStatus::Pending  => 'Sua oferta está pendente de resposta.',
                                \App\Enums\MarketOfferStatus::Accepted => 'Sua oferta foi aceita! Entre em contato com o vendedor.',
                                \App\Enums\MarketOfferStatus::Rejected => 'Sua oferta foi recusada.',
                            };
                        @endphp
                        <x-card class="border {{ $myStatusClass }}">
                            <p class="text-sm font-medium">{{ $myStatusMsg }}</p>
                            <p class="text-xs text-zinc-400 mt-1">Sua oferta: <span class="text-zinc-600">{{ $myOffer->message }}</span></p>
                        </x-card>
                    @else
                        <x-card>
                            <h2 class="text-sm font-semibold text-zinc-700 mb-3">Fazer uma oferta</h2>
                            <form wire:submit="submitOffer" class="space-y-3">
                                <div>
                                    <textarea wire:model="offerMessage" rows="2"
                                              placeholder="Descreva sua oferta (ex: 600kk, ou troco por Gengar T3 + 200kk)"
                                              class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500"></textarea>
                                    @error('offerMessage') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <button type="submit"
                                        wire:loading.attr="disabled" wire:target="submitOffer"
                                        class="bg-violet-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-violet-700 transition-colors disabled:opacity-50">
                                    <span wire:loading.remove wire:target="submitOffer">Enviar oferta</span>
                                    <span wire:loading wire:target="submitOffer">Enviando...</span>
                                </button>
                            </form>
                        </x-card>
                    @endif
                @endif
            @else
                {{-- Visitante não autenticado --}}
                @if($listing->isActive())
                    <x-card class="text-center py-6">
                        <p class="text-sm text-zinc-500 mb-3">Entre para fazer uma oferta neste anúncio.</p>
                        <a href="{{ route('login') }}" wire:navigate
                           class="inline-block bg-violet-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-violet-700 transition-colors">
                            Entrar
                        </a>
                    </x-card>
                @endif
            @endauth

        </div>

    </div>
</div>
