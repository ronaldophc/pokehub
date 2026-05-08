@props(['species' => '', 'spriteField' => null, 'speciesField' => 'form.species', 'placeholder' => 'ex: Charizard'])
<div x-data="pokemonAutocomplete(@js($species), @js($spriteField))"
     x-on:click.outside="open = false"
     class="relative">
    <div class="relative">
        <input
            type="text"
            x-model="search"
            x-on:input.debounce.150ms="filter()"
            x-on:focus="if (search && search.length >= 2) filter()"
            x-on:blur="if (!open) $wire.set('{{ $speciesField }}', search)"
            x-on:keydown.escape="open = false"
            x-on:keydown.arrow-down.prevent="highlightNext()"
            x-on:keydown.arrow-up.prevent="highlightPrev()"
            x-on:keydown.enter.prevent="selectHighlighted()"
            placeholder="{{ $placeholder }}"
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
                <img :src="p.sprite || `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${p.id}.png`"
                     class="w-8 h-8 object-contain flex-shrink-0"
                     x-on:error="$el.style.display='none'">
                <span x-text="p.l"></span>
            </button>
        </template>
    </div>
</div>
