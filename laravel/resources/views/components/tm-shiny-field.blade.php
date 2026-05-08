@props(['species' => '', 'isShiny' => false, 'shinyId' => 'isShiny'])
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end"
     x-data="tmSelect(@js($species), @js((bool) $isShiny))"
     x-show="hasSpecies">
    <div>
        <label class="block text-xs font-medium text-zinc-500 mb-1">TM</label>
        <select wire:model="form.tm"
                x-effect="$el.value = $wire.form.tm || ''"
                class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500">
            <option value="">Nenhum</option>
            <template x-for="opt in availableOptions" :key="opt">
                <option :value="opt" x-text="opt"></option>
            </template>
        </select>
        @error('form.tm') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
    </div>
    <div class="flex items-center gap-2 pb-2">
        <input wire:model="form.isShiny" id="{{ $shinyId }}" type="checkbox"
               class="rounded border-zinc-300 text-violet-600 shadow-sm focus:ring-violet-500">
        <label for="{{ $shinyId }}" class="text-sm font-medium text-zinc-600">Shiny</label>
    </div>
</div>
