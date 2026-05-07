<div>
    <x-page-header>
        <div class="flex items-center justify-between gap-4">
            <h1 class="text-xl font-semibold text-zinc-900">{{ __('My Houses') }}</h1>
            <div class="flex items-center gap-2">
                <input wire:model.live.debounce.200ms="search" type="search"
                       placeholder="{{ __('Search houses...') }}"
                       class="border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500 w-44">
                <button wire:click="$toggle('showJoinForm')"
                        class="text-sm text-zinc-500 px-4 py-2 rounded-lg hover:bg-zinc-100 transition-colors">
                    {{ __('Enter with code') }}
                </button>
                <button wire:click="$set('showForm', true)"
                        class="bg-violet-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-violet-700 transition-colors">
                    {{ __('+  New House') }}
                </button>
            </div>
        </div>

        @if($showJoinForm)
            <form wire:submit="joinByCode" class="flex gap-2 items-start mt-4">
                <div>
                    <input wire:model="joinCode"
                           type="text"
                           placeholder="{{ __('Code (e.g. AB3K7XZ)') }}"
                           maxlength="7"
                           class="border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500 uppercase w-44"
                           autofocus>
                    @error('joinCode') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <button type="submit"
                        wire:loading.attr="disabled"
                        wire:target="joinByCode"
                        class="bg-violet-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-violet-700 transition-colors disabled:opacity-50">
                    <span wire:loading.remove wire:target="joinByCode">{{ __('Enter') }}</span>
                    <span wire:loading wire:target="joinByCode">{{ __('Entering...') }}</span>
                </button>
            </form>
        @endif
    </x-page-header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-4">

        @if($showForm)
            <x-card>
                <h3 class="text-sm font-semibold text-zinc-700 mb-4">{{ __('New house') }}</h3>
                <form wire:submit="create" class="flex gap-3 items-start">
                    <div class="flex-1">
                        <input wire:model="name" type="text" placeholder="{{ __('House name') }}"
                               class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500"
                               autofocus>
                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit"
                            wire:loading.attr="disabled"
                            wire:target="create"
                            class="bg-violet-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-violet-700 transition-colors disabled:opacity-50">
                        <span wire:loading.remove wire:target="create">{{ __('Create') }}</span>
                        <span wire:loading wire:target="create">{{ __('Creating...') }}</span>
                    </button>
                    <button type="button" wire:click="$set('showForm', false)"
                            class="text-zinc-500 px-4 py-2 rounded-lg text-sm hover:bg-zinc-100 transition-colors">
                        {{ __('Cancel') }}
                    </button>
                </form>
            </x-card>
        @endif

        @forelse($houses as $house)
            <x-card class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-zinc-900">{{ $house->name }}</h3>
                    <p class="text-sm text-zinc-400 mt-0.5">
                        {{ __('Owner') }}: {{ $house->owner->name }}
                        &middot;
                        {{ __('Code') }}: <span class="font-mono font-medium text-zinc-600">{{ $house->invite_code }}</span>
                    </p>
                </div>
                <a href="{{ route('houses.show', $house) }}" wire:navigate
                   class="text-sm text-violet-600 font-medium hover:text-violet-700 transition-colors">
                    {{ __('Open →') }}
                </a>
            </x-card>
        @empty
            <x-card class="text-center py-12">
                <p class="text-zinc-400 text-sm">{{ __('You are not in any house yet.') }}</p>
                <p class="text-zinc-400 text-sm">{{ __('Create one or ask a friend for an invite code.') }}</p>
            </x-card>
        @endforelse

    </div>
</div>
