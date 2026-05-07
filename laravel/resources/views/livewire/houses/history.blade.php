<div>
    <x-page-header>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('houses.show', $house) }}" wire:navigate class="text-zinc-400 hover:text-zinc-600 transition-colors">&larr;</a>
                <h1 class="text-xl font-semibold text-zinc-900">{{ __('History · :house', ['house' => $house->name]) }}</h1>
            </div>
            <div class="w-56">
                <input wire:model.live.debounce.200ms="search" type="search"
                       placeholder="{{ __('Search pokémon or player...') }}"
                       class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500">
            </div>
        </div>
    </x-page-header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <x-card class="overflow-hidden p-0">
            @if($checkouts->isEmpty())
                <div class="text-center py-16 text-zinc-400 text-sm">
                    {{ __('No checkouts recorded yet.') }}
                </div>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-zinc-100 bg-zinc-50">
                            <th class="text-left px-4 py-3 text-xs font-medium text-zinc-500 uppercase tracking-wide">{{ __('Pokémon') }}</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-zinc-500 uppercase tracking-wide">{{ __('Player') }}</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-zinc-500 uppercase tracking-wide">{{ __('Taken at') }}</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-zinc-500 uppercase tracking-wide">{{ __('Returned at') }}</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-zinc-500 uppercase tracking-wide">{{ __('Duration') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @foreach($checkouts as $checkout)
                            <tr class="hover:bg-zinc-50/50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2.5">
                                        @if($checkout->pokemon->sprite_url)
                                            <img src="{{ $checkout->pokemon->sprite_url }}"
                                                 class="w-8 h-8 object-contain flex-shrink-0"
                                                 alt="{{ $checkout->pokemon->species }}">
                                        @endif
                                        <div>
                                            <p class="font-medium text-zinc-900">{{ $checkout->pokemon->species }}</p>
                                            @if($checkout->pokemon->name)
                                                <p class="text-xs text-zinc-400">{{ $checkout->pokemon->name }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-zinc-700">{{ $checkout->user->name }}</td>
                                <td class="px-4 py-3 text-zinc-500">
                                    <span title="{{ $checkout->checked_out_at->format('d/m/Y H:i') }}">
                                        {{ $checkout->checked_out_at->diffForHumans() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($checkout->returned_at)
                                        <span class="text-zinc-500" title="{{ $checkout->returned_at->format('d/m/Y H:i') }}">
                                            {{ $checkout->returned_at->diffForHumans() }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs font-medium text-amber-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            {{ __('In use') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-zinc-500">
                                    @if($checkout->returned_at)
                                        {{ $checkout->checked_out_at->diffForHumans($checkout->returned_at, true) }}
                                    @else
                                        {{ $checkout->checked_out_at->diffForHumans(now(), true) }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($checkouts->hasPages())
                    <div class="px-4 py-3 border-t border-zinc-100">
                        {{ $checkouts->links() }}
                    </div>
                @endif
            @endif
        </x-card>
    </div>
</div>
