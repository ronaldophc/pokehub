<div>
    <x-page-header>
        <div class="flex items-center gap-3">
            <a href="{{ route('market.show', $listing) }}" wire:navigate class="text-zinc-400 hover:text-zinc-600 transition-colors">&larr;</a>
            <h1 class="text-xl font-semibold text-zinc-900">Editar anúncio</h1>
        </div>
    </x-page-header>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <x-card>
            <form wire:submit="save" class="space-y-5">

                {{-- Pokémon --}}
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Pokémon <span class="text-red-400">*</span></label>
                    <div x-data="pokemonAutocomplete(@js($form->species), 'form.spriteUrl')"
                         x-on:click.outside="open = false"
                         class="relative">
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
                            placeholder="ex: Charizard"
                            autocomplete="off"
                            class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500"
                        >
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
                                    <img :src="`https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${p.id}.png`"
                                         class="w-8 h-8 object-contain flex-shrink-0"
                                         x-on:error="$el.style.display='none'">
                                    <span x-text="p.l"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                    @error('form.species') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Servidor --}}
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Servidor <span class="text-red-400">*</span></label>
                    <select wire:model="form.server"
                            class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500">
                        <option value="">Selecione...</option>
                        @foreach($servers as $s)
                            <option value="{{ $s->value }}">{{ $s->value }}</option>
                        @endforeach
                    </select>
                    @error('form.server') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- TM + Shiny --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end"
                     x-data="tmSelect(@js($form->species), @js((bool) $form->isShiny))"
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
                        <input wire:model="form.isShiny" id="editIsShiny" type="checkbox"
                               class="rounded border-zinc-300 text-violet-600 shadow-sm focus:ring-violet-500">
                        <label for="editIsShiny" class="text-sm font-medium text-zinc-600">Shiny</label>
                    </div>
                </div>

                {{-- Held X --}}
                <div x-data="heldItemSelect(@js($form->heldXName), @js($heldX), 'form.heldXName', 'form.heldXTier')">
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Held X (opcional)</label>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="col-span-2 relative">
                            <div class="absolute inset-y-0 left-2 flex items-center pointer-events-none" x-show="item">
                                <img :src="`/images/helds/tiers/${item.toLowerCase()}-t${$wire.form.heldXTier || 1}.png`" class="h-8 w-8 object-contain" onerror="this.style.display='none'">
                            </div>
                            <select x-model="item" :class="item ? 'pl-10' : ''"
                                    class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500">
                                <option value="">Nenhum</option>
                                @foreach(array_keys($heldX) as $itemName)
                                    <option value="{{ $itemName }}">{{ $itemName }}</option>
                                @endforeach
                            </select>
                        </div>
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

                {{-- Held Y --}}
                <div x-data="heldItemSelect(@js($form->heldYName), @js($heldY), 'form.heldYName', 'form.heldYTier')">
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Held Y (opcional)</label>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="col-span-2 relative">
                            <div class="absolute inset-y-0 left-2 flex items-center pointer-events-none" x-show="item">
                                <img :src="`/images/helds/tiers/${item.toLowerCase()}-t${$wire.form.heldYTier || 1}.png`" class="h-8 w-8 object-contain" onerror="this.style.display='none'">
                            </div>
                            <select x-model="item" :class="item ? 'pl-10' : ''"
                                    class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500">
                                <option value="">Nenhum</option>
                                @foreach(array_keys($heldY) as $itemName)
                                    <option value="{{ $itemName }}">{{ $itemName }}</option>
                                @endforeach
                            </select>
                        </div>
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

                {{-- Preço --}}
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Preço <span class="text-red-400">*</span></label>
                    <input wire:model="form.price" type="text" placeholder="ex: 500kk ou 1.5kkk"
                           class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500">
                    @error('form.price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Contato --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-zinc-500 mb-1">Nick in-game <span class="text-red-400">*</span></label>
                        <input wire:model="form.contactNick" type="text" placeholder="Seu personagem"
                               class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500">
                        @error('form.contactNick') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-zinc-500 mb-1">Discord (opcional)</label>
                        <input wire:model="form.contactDiscord" type="text" placeholder="usuario#0000 ou @usuario"
                               class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500">
                        @error('form.contactDiscord') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Observações --}}
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Observações (opcional)</label>
                    <textarea wire:model="form.notes" rows="2" placeholder="Informações adicionais sobre o pokémon ou a negociação"
                              class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500"></textarea>
                    @error('form.notes') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Screenshot --}}
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Print do look (opcional)</label>
                    <div
                        x-on:livewire-upload-start="$el.classList.add('opacity-50')"
                        x-on:livewire-upload-finish="$el.classList.remove('opacity-50')"
                    >
                        @if($screenshot)
                            {{-- New upload preview --}}
                            <div class="mb-2 relative inline-block">
                                <img src="{{ $screenshot->temporaryUrl() }}" class="h-32 rounded-lg object-cover border border-zinc-200">
                                <button type="button" wire:click="$set('screenshot', null)"
                                        class="absolute -top-2 -right-2 bg-white border border-zinc-200 rounded-full w-5 h-5 flex items-center justify-center text-zinc-400 hover:text-red-500 text-xs shadow-sm">
                                    ×
                                </button>
                            </div>
                        @elseif($listing->screenshot_path && !$removeScreenshot)
                            {{-- Existing screenshot --}}
                            <div class="mb-2 flex items-center gap-3">
                                <a href="{{ Storage::disk('public')->url($listing->screenshot_path) }}" target="_blank" rel="noopener">
                                    <img src="{{ Storage::disk('public')->url($listing->screenshot_path) }}"
                                         class="h-20 rounded-lg object-cover border border-zinc-200 hover:border-violet-300 transition-colors">
                                </a>
                                <button type="button" wire:click="$set('removeScreenshot', true)"
                                        class="text-xs text-red-400 hover:text-red-600 transition-colors">
                                    Remover print
                                </button>
                            </div>
                        @endif

                        @if(!($listing->screenshot_path && !$removeScreenshot && !$screenshot))
                            <input wire:model="screenshot" type="file" accept="image/jpeg,image/png,image/webp"
                                   class="block w-full text-sm text-zinc-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200 transition-colors">
                            <p class="text-xs text-zinc-400 mt-1">JPG, PNG ou WebP · máx. 2MB</p>
                        @else
                            <p class="text-xs text-zinc-400">Para substituir, remova o print atual primeiro.</p>
                        @endif
                        @error('screenshot') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex gap-3 pt-2 border-t border-zinc-100">
                    <button type="submit"
                            wire:loading.attr="disabled" wire:target="save"
                            class="bg-violet-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-violet-700 transition-colors disabled:opacity-50">
                        <span wire:loading.remove wire:target="save">Salvar alterações</span>
                        <span wire:loading wire:target="save">Salvando...</span>
                    </button>
                    <a href="{{ route('market.show', $listing) }}" wire:navigate
                       class="text-zinc-500 px-4 py-2 rounded-lg text-sm hover:bg-zinc-100 transition-colors">
                        Cancelar
                    </a>
                </div>

            </form>
        </x-card>
    </div>
</div>
