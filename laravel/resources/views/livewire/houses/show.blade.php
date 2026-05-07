<div>
    <x-page-header>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('houses.index') }}" wire:navigate class="text-zinc-400 hover:text-zinc-600 transition-colors">&larr;</a>
                <h1 class="text-xl font-semibold text-zinc-900">{{ $house->name }}</h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('houses.history', $house) }}" wire:navigate
                   class="text-sm text-zinc-400 hover:text-zinc-700 transition-colors">
                    {{ __('History') }}
                </a>
                @if($this->canManage())
                    <a href="{{ route('houses.members', $house) }}" wire:navigate
                       class="text-sm text-zinc-400 hover:text-zinc-700 transition-colors">
                        {{ __('Manage') }}
                    </a>
                    <button wire:click="openCreate"
                            class="bg-violet-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-violet-700 transition-colors">
                        {{ __('+ Pokémon') }}
                    </button>
                @endif
            </div>
        </div>
    </x-page-header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Busca + filtros --}}
        <div class="flex flex-wrap items-center gap-3">
            <input wire:model.live.debounce.200ms="search" type="search"
                   placeholder="{{ __('Search pokémon...') }}"
                   class="border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500 w-52">
            <div class="flex items-center gap-1 bg-zinc-100 rounded-lg p-1">
                @foreach(['all' => __('All'), 'free' => __('Free'), 'taken' => __('Taken')] as $val => $label)
                    <button wire:click="$set('filterStatus', '{{ $val }}')"
                            @class([
                                'px-3 py-1 rounded-md text-xs font-medium transition-colors',
                                'bg-white shadow-sm text-zinc-900' => $filterStatus === $val,
                                'text-zinc-500 hover:text-zinc-700' => $filterStatus !== $val,
                            ])>
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        @error('checkout')
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">
                {{ $message }}
            </div>
        @enderror

        {{-- Formulário --}}
        @if($showForm)
            <x-card>
                <h3 class="text-sm font-semibold text-zinc-700 mb-4">
                    {{ $editingId ? __('Edit pokémon') : __('New pokémon') }}
                </h3>
                <form wire:submit="save" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-zinc-500 mb-1">{{ __('Nickname') }}</label>
                            <input wire:model="form.name" type="text" placeholder="{{ __('e.g. Torch') }}"
                                   class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500">
                            @error('form.name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-500 mb-1">{{ __('Pokémon') }}</label>
                            <div x-data="pokemonAutocomplete(@js($form->species))"
                                 x-on:click.outside="open = false"
                                 class="relative">
                                <div class="relative">
                                    <input
                                        type="text"
                                        x-model="search"
                                        x-on:input.debounce.150ms="filter()"
                                        x-on:focus="if (search && search.length >= 2) filter()"
                                        x-on:blur="if (!open) $wire.set('form.species', search)"
                                        x-on:keydown.escape="open = false"
                                        x-on:keydown.arrow-down.prevent="highlightNext()"
                                        x-on:keydown.arrow-up.prevent="highlightPrev()"
                                        x-on:keydown.enter.prevent="selectHighlighted()"
                                        placeholder="{{ __('e.g. Charizard') }}"
                                        autocomplete="off"
                                        class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500 pr-8"
                                    >
                                    <div x-show="loading" class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none">
                                        <svg class="animate-spin h-4 w-4 text-zinc-400" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div
                                    x-show="open"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 -translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    class="absolute z-50 w-full mt-1 bg-white border border-zinc-200 rounded-xl shadow-lg max-h-56 overflow-y-auto"
                                >
                                    <template x-for="(p, i) in results" :key="p.v">
                                        <button
                                            type="button"
                                            x-on:click="select(p)"
                                            x-on:mouseenter="highlighted = i"
                                            x-bind:class="highlighted === i ? 'bg-violet-50 text-violet-900' : 'text-zinc-700 hover:bg-zinc-50'"
                                            class="w-full text-left px-3 py-1.5 text-sm flex items-center gap-2.5 transition-colors"
                                        >
                                            <img
                                                :src="`https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${p.id}.png`"
                                                class="w-8 h-8 object-contain flex-shrink-0"
                                                x-on:error="$el.style.display='none'"
                                            >
                                            <span x-text="p.l"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                            @error('form.species') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Notes + Owner --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-zinc-500 mb-1">{{ __('Notes (optional)') }}</label>
                            <input wire:model="form.notes" type="text" placeholder="{{ __('e.g. Hunts only') }}"
                                   class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500">
                            @error('form.notes') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-500 mb-1">{{ __('Owner (optional)') }}</label>
                            <select wire:model="form.ownerId"
                                    class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500">
                                <option value="">{{ __('No owner') }}</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->user_id }}">{{ $member->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- TM + Shiny --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                        <div>
                            <label class="block text-xs font-medium text-zinc-500 mb-1">{{ __('TM (optional)') }}</label>
                            <input wire:model="form.tm" type="text"
                                   class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500">
                            @error('form.tm') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex items-center gap-2 pb-2">
                            <input wire:model="form.isShiny" id="isShiny" type="checkbox"
                                   class="rounded border-zinc-300 text-violet-600 shadow-sm focus:ring-violet-500">
                            <label for="isShiny" class="text-sm font-medium text-zinc-600">{{ __('Shiny') }}</label>
                        </div>
                    </div>

                    {{-- Held X --}}
                    <div x-data="heldItemSelect(@js($form->heldXName), @js($heldX), 'form.heldXName', 'form.heldXTier')">
                        <label class="block text-xs font-medium text-zinc-500 mb-1">{{ __('Held X (optional)') }}</label>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="col-span-2">
                                <select x-model="item"
                                        class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500">
                                    <option value="">{{ __('None') }}</option>
                                    @foreach(array_keys($heldX) as $itemName)
                                        <option value="{{ $itemName }}">{{ $itemName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select wire:model="form.heldXTier"
                                        x-bind:disabled="!item"
                                        class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500 disabled:opacity-40">
                                    <option value="">T</option>
                                    <template x-for="t in availableTiers" :key="t">
                                        <option :value="t" x-text="`T${t}`"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Held Y --}}
                    <div x-data="heldItemSelect(@js($form->heldYName), @js($heldY), 'form.heldYName', 'form.heldYTier')">
                        <label class="block text-xs font-medium text-zinc-500 mb-1">{{ __('Held Y (optional)') }}</label>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="col-span-2">
                                <select x-model="item"
                                        class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500">
                                    <option value="">{{ __('None') }}</option>
                                    @foreach(array_keys($heldY) as $itemName)
                                        <option value="{{ $itemName }}">{{ $itemName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select wire:model="form.heldYTier"
                                        x-bind:disabled="!item"
                                        class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500 disabled:opacity-40">
                                    <option value="">T</option>
                                    <template x-for="t in availableTiers" :key="t">
                                        <option :value="t" x-text="`T${t}`"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit"
                                wire:loading.attr="disabled" wire:target="save"
                                class="bg-violet-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-violet-700 transition-colors disabled:opacity-50">
                            <span wire:loading.remove wire:target="save">{{ $editingId ? __('Save') : __('Register') }}</span>
                            <span wire:loading wire:target="save">{{ $editingId ? __('Saving...') : __('Registering...') }}</span>
                        </button>
                        <button type="button" wire:click="cancelForm"
                                class="text-zinc-500 px-4 py-2 rounded-lg text-sm hover:bg-zinc-100 transition-colors">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </form>
            </x-card>
        @endif

        {{-- Grid de cards --}}
        @if($pokemons->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                @foreach($pokemons as $pokemon)
                    <div @class([
                        'border rounded-xl overflow-hidden flex flex-col transition-all',
                        'bg-white border-emerald-200'       => $pokemon->isAvailable(),
                        'bg-zinc-50/80 border-violet-300'   => !$pokemon->isAvailable() && $pokemon->current_holder_id === auth()->id(),
                        'bg-zinc-50/80 border-amber-300'    => !$pokemon->isAvailable() && $pokemon->current_holder_id !== auth()->id(),
                    ])>

                        {{-- Sprite --}}
                        <div @class([
                            'flex items-center justify-center p-4 h-32',
                            'bg-zinc-50'    => $pokemon->isAvailable(),
                            'bg-zinc-100'   => !$pokemon->isAvailable(),
                        ]) x-data="{ failed: false }">
                            @if($pokemon->sprite_url)
                                <img
                                    x-show="!failed"
                                    src="{{ $pokemon->sprite_url }}"
                                    alt="{{ $pokemon->species }}"
                                    @class([
                                        'h-24 w-24 object-contain drop-shadow-sm transition-opacity',
                                        'opacity-40' => !$pokemon->isAvailable(),
                                    ])
                                    x-on:error="failed = true"
                                >
                                <span x-show="failed" class="text-4xl text-zinc-300">?</span>
                            @else
                                <span class="text-4xl text-zinc-300">?</span>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="p-3 flex flex-col gap-2 flex-1">
                            <div>
                                <p class="font-semibold text-zinc-900 text-sm leading-tight">{{ $pokemon->species }}</p>
                                @if($pokemon->name)
                                    <p class="text-xs text-zinc-400">{{ $pokemon->name }}</p>
                                @endif
                                @if($pokemon->owner)
                                    <p class="text-xs text-violet-500 mt-0.5">{{ $pokemon->owner->name }}</p>
                                @endif
                            </div>

                            @if($pokemon->notes)
                                <p class="text-xs text-zinc-400 truncate">{{ $pokemon->notes }}</p>
                            @endif

                            {{-- Badges: shiny / tm / helds --}}
                            @if($pokemon->is_shiny || $pokemon->tm || $pokemon->held_x_name || $pokemon->held_y_name)
                                <div class="flex flex-wrap gap-1">
                                    @if($pokemon->is_shiny)
                                        <span class="inline-flex items-center gap-0.5 text-xs font-medium bg-amber-50 text-amber-600 border border-amber-200 rounded px-1.5 py-0.5">
                                            ✦ Shiny
                                        </span>
                                    @endif
                                    @if($pokemon->tm)
                                        <span class="inline-flex items-center text-xs font-medium bg-sky-50 text-sky-600 border border-sky-200 rounded px-1.5 py-0.5 max-w-full truncate">
                                            {{ $pokemon->tm }}
                                        </span>
                                    @endif
                                    @if($pokemon->held_x_name)
                                        <span class="inline-flex items-center text-xs font-medium bg-violet-50 text-violet-600 border border-violet-200 rounded px-1.5 py-0.5">
                                            {{ $pokemon->held_x_name }}@if($pokemon->held_x_tier) T{{ $pokemon->held_x_tier }}@endif
                                        </span>
                                    @endif
                                    @if($pokemon->held_y_name)
                                        <span class="inline-flex items-center text-xs font-medium bg-emerald-50 text-emerald-600 border border-emerald-200 rounded px-1.5 py-0.5">
                                            {{ $pokemon->held_y_name }}@if($pokemon->held_y_tier) T{{ $pokemon->held_y_tier }}@endif
                                        </span>
                                    @endif
                                </div>
                            @endif

                            {{-- Status --}}
                            @if($pokemon->isAvailable())
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    {{ __('Free') }}
                                </span>
                            @else
                                <div>
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-amber-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        {{ $pokemon->currentHolder->name }}
                                    </span>
                                    <p class="text-xs text-zinc-400 mt-0.5">{{ $pokemon->held_since->diffForHumans() }}</p>
                                </div>
                            @endif

                            {{-- Ações --}}
                            <div class="flex items-center justify-between mt-auto pt-1 border-t border-zinc-100">
                                @if($pokemon->isAvailable())
                                    <button wire:click="checkout({{ $pokemon->id }})"
                                            wire:loading.attr="disabled" wire:target="checkout({{ $pokemon->id }})"
                                            class="text-xs font-medium text-violet-600 hover:text-violet-800 transition-colors disabled:opacity-50">
                                        <span wire:loading.remove wire:target="checkout({{ $pokemon->id }})">{{ __('Take') }}</span>
                                        <span wire:loading wire:target="checkout({{ $pokemon->id }})">{{ __('Taking...') }}</span>
                                    </button>
                                @elseif($pokemon->current_holder_id === auth()->id())
                                    <button wire:click="return({{ $pokemon->id }})"
                                            wire:loading.attr="disabled" wire:target="return({{ $pokemon->id }})"
                                            class="text-xs font-medium text-zinc-500 hover:text-zinc-800 transition-colors disabled:opacity-50">
                                        <span wire:loading.remove wire:target="return({{ $pokemon->id }})">{{ __('Return') }}</span>
                                        <span wire:loading wire:target="return({{ $pokemon->id }})">{{ __('Returning...') }}</span>
                                    </button>
                                @else
                                    <span></span>
                                @endif

                                @if($this->canManage())
                                    <div class="flex items-center gap-2">
                                        <button wire:click="openEdit({{ $pokemon->id }})"
                                                class="text-xs text-zinc-300 hover:text-zinc-600 transition-colors">
                                            {{ __('Edit') }}
                                        </button>
                                        <button wire:click="delete({{ $pokemon->id }})"
                                                wire:confirm="{{ __('Remove') }} {{ $pokemon->name }}?"
                                                wire:loading.attr="disabled" wire:target="delete({{ $pokemon->id }})"
                                                class="text-xs text-red-300 hover:text-red-500 transition-colors disabled:opacity-50">
                                            <span wire:loading.remove wire:target="delete({{ $pokemon->id }})">{{ __('Remove') }}</span>
                                            <span wire:loading wire:target="delete({{ $pokemon->id }})">{{ __('Removing...') }}</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <x-card class="text-center py-12">
                @if($search || $filterStatus !== 'all')
                    <p class="text-zinc-400 text-sm">{{ __('No pokémons found for this filter.') }}</p>
                    <button wire:click="$set('search', ''); $set('filterStatus', 'all')"
                            class="mt-3 text-sm text-violet-600 hover:text-violet-700 font-medium">
                        {{ __('Clear filters') }}
                    </button>
                @else
                    <p class="text-zinc-400 text-sm">{{ __('No pokémons registered in this house.') }}</p>
                    @if($this->canManage())
                        <button wire:click="openCreate" class="mt-3 text-sm text-violet-600 hover:text-violet-700 font-medium">
                            {{ __('Register the first one') }}
                        </button>
                    @endif
                @endif
            </x-card>
        @endif

    </div>
</div>
