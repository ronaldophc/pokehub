<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="space-y-6">

    <div>
        <h1 class="text-xl font-bold text-zinc-900">{{ __('Welcome back') }}</h1>
        <p class="text-sm text-zinc-400 mt-0.5">{{ __('Enter your details to access your houses') }}</p>
    </div>

    <x-auth-session-status :status="session('status')" />

    <form wire:submit="login" class="space-y-4">

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="form.email" id="email" type="email" name="email"
                          class="block w-full" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-1" />
        </div>

        <div>
            <div class="flex items-center justify-between mb-1">
                <x-input-label for="password" :value="__('Password')" />
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" wire:navigate
                       class="text-xs text-violet-600 hover:text-violet-800 transition-colors">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>
            <x-text-input wire:model="form.password" id="password" type="password" name="password"
                          class="block w-full" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-1" />
        </div>

        <div class="flex items-center gap-2">
            <input wire:model="form.remember" id="remember" type="checkbox"
                   class="rounded border-zinc-300 text-violet-600 shadow-sm focus:ring-violet-500">
            <label for="remember" class="text-sm text-zinc-500">{{ __('Remember me') }}</label>
        </div>

        <x-primary-button class="w-full justify-center py-2.5">
            {{ __('Log in') }}
        </x-primary-button>

    </form>

    <p class="text-center text-sm text-zinc-400">
        {{ __("Don't have an account?") }}
        <a href="{{ route('register') }}" wire:navigate
           class="text-violet-600 font-medium hover:text-violet-800 transition-colors">
            {{ __('Create one') }}
        </a>
    </p>

</div>
