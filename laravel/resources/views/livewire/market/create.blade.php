<div>
    <x-page-header>
        <div class="flex items-center gap-3">
            <a href="{{ route('market.index') }}" wire:navigate class="text-zinc-400 hover:text-zinc-600 transition-colors">&larr;</a>
            <h1 class="text-xl font-semibold text-zinc-900">Novo anúncio</h1>
        </div>
    </x-page-header>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @error('limit')
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">
                {{ $message }}
            </div>
        @enderror

        <x-card>
            <form wire:submit="save" class="space-y-5">

                {{-- Pokémon --}}
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Pokémon <span class="text-red-400">*</span></label>
                    <x-pokemon-field :species="$form->species" sprite-field="form.spriteUrl" />
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
                <x-tm-shiny-field :species="$form->species" :is-shiny="$form->isShiny" shiny-id="mktIsShiny" />

                {{-- Held X --}}
                <x-held-field label="Held X (opcional)" :items="$heldX"
                              name-field="form.heldXName" tier-field="form.heldXTier"
                              :current-name="$form->heldXName" />

                {{-- Held Y --}}
                <x-held-field label="Held Y (opcional)" :items="$heldY"
                              name-field="form.heldYName" tier-field="form.heldYTier"
                              :current-name="$form->heldYName" />

                {{-- Helds inativos --}}
                <x-extra-helds-field :extra-helds="$form->extraHelds" :all-items="$allItems" />

                {{-- Preço --}}
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Preço <span class="text-red-400">*</span></label>
                    <input wire:model="form.price" type="text" placeholder="ex: 500kk ou 1.5kkk"
                           class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500">
                    @error('form.price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Contato --}}
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Discord (opcional)</label>
                    <input wire:model="form.contactDiscord" type="text" placeholder="usuario#0000 ou @usuario"
                           class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500">
                    @error('form.contactDiscord') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
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
                            <div class="mb-2 relative inline-block">
                                <img src="{{ $screenshot->temporaryUrl() }}" class="h-32 rounded-lg object-cover border border-zinc-200">
                                <button type="button" wire:click="$set('screenshot', null)"
                                        class="absolute -top-2 -right-2 bg-white border border-zinc-200 rounded-full w-5 h-5 flex items-center justify-center text-zinc-400 hover:text-red-500 text-xs shadow-sm">
                                    ×
                                </button>
                            </div>
                        @endif
                        <input wire:model="screenshot" type="file" accept="image/jpeg,image/png,image/webp"
                               class="block w-full text-sm text-zinc-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200 transition-colors">
                        @error('screenshot') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        <p class="text-xs text-zinc-400 mt-1">JPG, PNG ou WebP · máx. 2MB</p>
                    </div>
                </div>

                <div class="flex gap-3 pt-2 border-t border-zinc-100">
                    <button type="submit"
                            wire:loading.attr="disabled" wire:target="save"
                            class="bg-violet-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-violet-700 transition-colors disabled:opacity-50">
                        <span wire:loading.remove wire:target="save">Publicar anúncio</span>
                        <span wire:loading wire:target="save">Publicando...</span>
                    </button>
                    <a href="{{ route('market.index') }}" wire:navigate
                       class="text-zinc-500 px-4 py-2 rounded-lg text-sm hover:bg-zinc-100 transition-colors">
                        Cancelar
                    </a>
                </div>

            </form>
        </x-card>
    </div>
</div>
