@props(['extraHelds', 'allItems'])
<div class="space-y-2">
    <div class="flex items-center justify-between">
        <label class="block text-xs font-medium text-zinc-500">Helds inativos</label>
        <button type="button" wire:click="addExtraHeld"
                class="text-xs text-violet-600 hover:text-violet-800 font-medium transition-colors">
            + Adicionar
        </button>
    </div>

    @forelse($extraHelds as $i => $held)
        <div class="grid grid-cols-3 gap-2 items-center"
             x-data="{
                 name: @js($held['name'] ?? ''),
                 allItems: @js($allItems),
                 get tiers() { return this.allItems[this.name] ?? []; }
             }"
             x-init="$watch('name', v => $wire.set('form.extraHelds.{{ $i }}.name', v))">
            <div class="col-span-2 relative">
                <div class="absolute inset-y-0 left-2 flex items-center pointer-events-none" x-show="name">
                    <img :src="`/images/helds/tiers/${name.toLowerCase()}-t${$wire.get('form.extraHelds.{{ $i }}.tier') || 1}.png`"
                         class="h-8 w-8 object-contain" onerror="this.style.display='none'">
                </div>
                <select x-model="name" :class="name ? 'pl-10' : ''"
                        class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500">
                    <option value="">Selecione...</option>
                    @foreach(array_keys($allItems) as $itemName)
                        <option value="{{ $itemName }}">{{ $itemName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-1">
                <select wire:model="form.extraHelds.{{ $i }}.tier"
                        x-bind:disabled="!name"
                        class="flex-1 border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500 disabled:opacity-40">
                    <option value="">T</option>
                    <template x-for="t in tiers" :key="t">
                        <option :value="t" x-text="`T${t}`"></option>
                    </template>
                </select>
                <button type="button" wire:click="removeExtraHeld({{ $i }})"
                        class="text-zinc-400 hover:text-red-500 transition-colors text-xl leading-none px-1">
                    &times;
                </button>
            </div>
        </div>
    @empty
        <p class="text-xs text-zinc-400">Nenhum held inativo.</p>
    @endforelse
</div>
