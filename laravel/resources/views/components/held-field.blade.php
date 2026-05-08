@props(['label', 'items', 'nameField', 'tierField', 'currentName' => null])
<div x-data="heldItemSelect(@js($currentName), @js($items), '{{ $nameField }}', '{{ $tierField }}')">
    <label class="block text-xs font-medium text-zinc-500 mb-1">{{ $label }}</label>
    <div class="grid grid-cols-3 gap-2">
        <div class="col-span-2 relative">
            <div class="absolute inset-y-0 left-2 flex items-center pointer-events-none" x-show="item">
                <img :src="`/images/helds/tiers/${item.toLowerCase()}-t${$wire.get('{{ $tierField }}') || 1}.png`"
                     class="h-8 w-8 object-contain" onerror="this.style.display='none'">
            </div>
            <select x-model="item" :class="item ? 'pl-10' : ''"
                    class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500">
                <option value="">Nenhum</option>
                @foreach(array_keys($items) as $itemName)
                    <option value="{{ $itemName }}">{{ $itemName }}</option>
                @endforeach
            </select>
        </div>
        <select wire:model="{{ $tierField }}"
                x-bind:disabled="!item"
                class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500 disabled:opacity-40">
            <option value="">T</option>
            <template x-for="t in availableTiers" :key="t">
                <option :value="t" x-text="`T${t}`"></option>
            </template>
        </select>
    </div>
</div>
