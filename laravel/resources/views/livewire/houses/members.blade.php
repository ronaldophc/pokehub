<div>
    <x-page-header>
        <div class="flex items-center gap-3">
            <a href="{{ route('houses.show', $house) }}" wire:navigate class="text-zinc-400 hover:text-zinc-600 transition-colors">&larr;</a>
            <h1 class="text-xl font-semibold text-zinc-900">{{ __('Members · :house', ['house' => $house->name]) }}</h1>
        </div>
    </x-page-header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Invite code --}}
        <x-card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-zinc-700">{{ __('Invite code') }}</p>
                    <p class="text-xs text-zinc-400 mt-0.5">{{ __('Share the link to invite someone to the house') }}</p>
                    <p class="font-mono font-semibold text-zinc-900 mt-2 text-lg tracking-widest">{{ $house->invite_code }}</p>
                    <p class="text-xs text-zinc-400 mt-1">{{ url('/join/' . $house->invite_code) }}</p>
                </div>
                <button wire:click="regenerateCode"
                        wire:confirm="{{ __('Regenerating the code invalidates the current link. Continue?') }}"
                        wire:loading.attr="disabled"
                        wire:target="regenerateCode"
                        class="text-sm text-zinc-400 hover:text-zinc-700 transition-colors disabled:opacity-50">
                    <span wire:loading.remove wire:target="regenerateCode">{{ __('Regenerate') }}</span>
                    <span wire:loading wire:target="regenerateCode">{{ __('Regenerating...') }}</span>
                </button>
            </div>
        </x-card>

        {{-- Members list --}}
        <x-card class="divide-y divide-zinc-100">
            @foreach($members as $member)
                <div class="flex items-center justify-between py-3 first:pt-0 last:pb-0">
                    <div>
                        <p class="text-sm font-medium text-zinc-900">{{ $member->user->name }}</p>
                        <p class="text-xs text-zinc-400">{{ $member->user->email }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span @class([
                            'text-xs font-medium px-2 py-0.5 rounded-full',
                            'bg-violet-100 text-violet-700' => $member->role === 'owner',
                            'bg-blue-100 text-blue-700' => $member->role === 'admin',
                            'bg-zinc-100 text-zinc-600' => $member->role === 'member',
                        ])>
                            {{ __($member->role === 'member' ? 'Member' : ucfirst($member->role)) }}
                        </span>

                        @if($member->user_id !== $house->owner_id)
                            @if($house->owner_id === auth()->id())
                                @if($member->role === 'member')
                                    <button wire:click="promoteToAdmin({{ $member->user_id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="promoteToAdmin({{ $member->user_id }})"
                                            class="text-xs text-blue-400 hover:text-blue-600 transition-colors disabled:opacity-50">
                                        <span wire:loading.remove wire:target="promoteToAdmin({{ $member->user_id }})">{{ __('Promote') }}</span>
                                        <span wire:loading wire:target="promoteToAdmin({{ $member->user_id }})">{{ __('Promoting...') }}</span>
                                    </button>
                                @elseif($member->role === 'admin')
                                    <button wire:click="demoteToMember({{ $member->user_id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="demoteToMember({{ $member->user_id }})"
                                            class="text-xs text-zinc-400 hover:text-zinc-600 transition-colors disabled:opacity-50">
                                        <span wire:loading.remove wire:target="demoteToMember({{ $member->user_id }})">{{ __('Demote') }}</span>
                                        <span wire:loading wire:target="demoteToMember({{ $member->user_id }})">{{ __('Demoting...') }}</span>
                                    </button>
                                    <button wire:click="transferOwnership({{ $member->user_id }})"
                                            wire:confirm="{{ __('Transfer leadership to :name?', ['name' => $member->user->name]) }}"
                                            wire:loading.attr="disabled"
                                            wire:target="transferOwnership({{ $member->user_id }})"
                                            class="text-xs text-amber-400 hover:text-amber-600 transition-colors disabled:opacity-50">
                                        <span wire:loading.remove wire:target="transferOwnership({{ $member->user_id }})">{{ __('Transfer') }}</span>
                                        <span wire:loading wire:target="transferOwnership({{ $member->user_id }})">{{ __('Transferring...') }}</span>
                                    </button>
                                @endif
                            @endif

                            <button wire:click="removeMember({{ $member->user_id }})"
                                    wire:confirm="{{ __('Remove :name from house?', ['name' => $member->user->name]) }}"
                                    wire:loading.attr="disabled"
                                    wire:target="removeMember({{ $member->user_id }})"
                                    class="text-xs text-red-400 hover:text-red-600 transition-colors disabled:opacity-50">
                                <span wire:loading.remove wire:target="removeMember({{ $member->user_id }})">{{ __('Remove') }}</span>
                                <span wire:loading wire:target="removeMember({{ $member->user_id }})">{{ __('Removing...') }}</span>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </x-card>

        {{-- House settings --}}
        <x-card class="space-y-5">
            <h3 class="text-sm font-semibold text-zinc-700">{{ __('House settings') }}</h3>

            {{-- Rename --}}
            @if($editingName)
                <form wire:submit="rename" class="flex items-start gap-3">
                    <div class="flex-1">
                        <input wire:model="name" type="text"
                               class="w-full border-zinc-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500">
                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit"
                            wire:loading.attr="disabled" wire:target="rename"
                            class="bg-violet-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-violet-700 transition-colors disabled:opacity-50 whitespace-nowrap">
                        <span wire:loading.remove wire:target="rename">{{ __('Save') }}</span>
                        <span wire:loading wire:target="rename">{{ __('Saving...') }}</span>
                    </button>
                    <button type="button" wire:click="cancelRename"
                            class="text-zinc-500 px-4 py-2 rounded-lg text-sm hover:bg-zinc-100 transition-colors">
                        {{ __('Cancel') }}
                    </button>
                </form>
            @else
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-zinc-400">{{ __('House name') }}</p>
                        <p class="text-sm font-medium text-zinc-900 mt-0.5">{{ $house->name }}</p>
                    </div>
                    <button wire:click="startRename"
                            class="text-sm text-zinc-400 hover:text-zinc-700 transition-colors">
                        {{ __('Edit') }}
                    </button>
                </div>
            @endif

            {{-- Delete (owner only) --}}
            @if($house->owner_id === auth()->id())
                <div class="pt-4 border-t border-zinc-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-700">{{ __('Delete house') }}</p>
                        <p class="text-xs text-zinc-400 mt-0.5">{{ __('Permanently removes the house and all its pokémons.') }}</p>
                    </div>
                    <button wire:click="delete"
                            wire:confirm="{{ __('Delete :name? This action cannot be undone.', ['name' => $house->name]) }}"
                            wire:loading.attr="disabled" wire:target="delete"
                            class="text-sm text-red-500 hover:text-red-700 font-medium transition-colors disabled:opacity-50">
                        <span wire:loading.remove wire:target="delete">{{ __('Delete') }}</span>
                        <span wire:loading wire:target="delete">{{ __('Deleting...') }}</span>
                    </button>
                </div>
            @endif
        </x-card>

    </div>
</div>
