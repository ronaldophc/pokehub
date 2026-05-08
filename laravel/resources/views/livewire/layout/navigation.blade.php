<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-zinc-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-14">

            <div class="flex items-center gap-8">
                <a href="{{ route('houses.index') }}" wire:navigate class="flex items-center gap-2">
                    <span class="text-white font-bold text-lg tracking-tight">Poke<span class="text-violet-400">Hub</span></span>
                </a>

                <div class="hidden sm:flex items-center gap-1">
                    @auth
                        <a href="{{ route('houses.index') }}" wire:navigate
                           class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors
                                  {{ request()->routeIs('houses.*') ? 'text-white bg-zinc-700' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
                            {{ __('Houses') }}
                        </a>
                    @endauth
                    <a href="{{ route('market.index') }}" wire:navigate
                       class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors
                              {{ request()->routeIs('market.*') ? 'text-white bg-zinc-700' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
                        Market
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex items-center">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm text-zinc-400 hover:text-white hover:bg-zinc-800 transition-colors">
                                <span x-data="{{ json_encode(['name' => auth()->user()?->name ?? '']) }}"
                                      x-text="name"
                                      x-on:profile-updated.window="name = $event.detail.name">
                                </span>
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile')" wire:navigate>
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <button wire:click="logout" class="w-full text-start">
                                <x-dropdown-link>{{ __('Log Out') }}</x-dropdown-link>
                            </button>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-1">
                        <a href="{{ route('login') }}" wire:navigate
                           class="px-3 py-1.5 rounded-md text-sm font-medium text-zinc-400 hover:text-white hover:bg-zinc-800 transition-colors">
                            Entrar
                        </a>
                        <a href="{{ route('register') }}" wire:navigate
                           class="px-3 py-1.5 rounded-md text-sm font-medium bg-violet-600 text-white hover:bg-violet-700 transition-colors">
                            Cadastrar
                        </a>
                    </div>
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 rounded-md text-zinc-400 hover:text-white hover:bg-zinc-800 transition-colors">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-zinc-800">
        <div class="px-4 py-3 space-y-1">
            @auth
                <a href="{{ route('houses.index') }}" wire:navigate
                   class="block px-3 py-2 rounded-md text-sm font-medium text-zinc-400 hover:text-white hover:bg-zinc-800 transition-colors">
                    {{ __('Houses') }}
                </a>
            @endauth
            <a href="{{ route('market.index') }}" wire:navigate
               class="block px-3 py-2 rounded-md text-sm font-medium text-zinc-400 hover:text-white hover:bg-zinc-800 transition-colors">
                Market
            </a>
        </div>

        <div class="border-t border-zinc-800 px-4 py-3 space-y-1">
            @auth
                <div class="text-sm font-medium text-white mb-1"
                     x-data="{{ json_encode(['name' => auth()->user()?->name ?? '']) }}"
                     x-text="name"
                     x-on:profile-updated.window="name = $event.detail.name">
                </div>
                <div class="text-xs text-zinc-500 mb-3">{{ auth()->user()?->email }}</div>

                <a href="{{ route('profile') }}" wire:navigate class="block px-3 py-2 rounded-md text-sm text-zinc-400 hover:text-white hover:bg-zinc-800 transition-colors">
                    {{ __('Profile') }}
                </a>
                <button wire:click="logout" class="w-full text-start px-3 py-2 rounded-md text-sm text-zinc-400 hover:text-white hover:bg-zinc-800 transition-colors">
                    {{ __('Log Out') }}
                </button>
            @else
                <a href="{{ route('login') }}" wire:navigate class="block px-3 py-2 rounded-md text-sm text-zinc-400 hover:text-white hover:bg-zinc-800 transition-colors">
                    Entrar
                </a>
                <a href="{{ route('register') }}" wire:navigate class="block px-3 py-2 rounded-md text-sm text-zinc-400 hover:text-white hover:bg-zinc-800 transition-colors">
                    Cadastrar
                </a>
            @endauth
        </div>
    </div>
</nav>
