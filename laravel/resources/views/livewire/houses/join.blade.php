<div>
    <x-page-header>
        <h1 class="text-xl font-semibold text-zinc-900">{{ __('Join House') }}</h1>
    </x-page-header>

    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <x-card class="text-center space-y-4">
            <p class="text-zinc-400 text-sm">{{ __('You were invited to') }}</p>
            <h2 class="text-2xl font-bold text-zinc-900">{{ $house->name }}</h2>
            <p class="text-zinc-400 text-sm">{{ __('Owner') }}: {{ $house->owner->name }}</p>

            <button wire:click="join"
                    wire:loading.attr="disabled"
                    wire:target="join"
                    class="w-full bg-violet-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-violet-700 transition-colors disabled:opacity-50">
                <span wire:loading.remove wire:target="join">{{ __('Join house') }}</span>
                <span wire:loading wire:target="join">{{ __('Joining...') }}</span>
            </button>

            <a href="{{ route('houses.index') }}" wire:navigate
               class="block text-sm text-zinc-400 hover:text-zinc-600 transition-colors">
                {{ __('Cancel') }}
            </a>
        </x-card>
    </div>
</div>
