<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="space-y-6">

    <div>
        <h1 class="text-xl font-bold text-zinc-900">{{ __('Create account') }}</h1>
        <p class="text-sm text-zinc-400 mt-0.5">{{ __('Join PokeHub and manage your pokémons') }}</p>
    </div>

    <form wire:submit="register" class="space-y-4">

        <div>
            <x-input-label for="name" :value="__('In-game Nick')" />
            <x-text-input wire:model="name" id="name" type="text" name="name"
                          class="block w-full" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" type="email" name="email"
                          class="block w-full" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input wire:model="password" id="password" type="password" name="password"
                          class="block w-full" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation" type="password"
                          name="password_confirmation" class="block w-full" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <x-primary-button class="w-full justify-center py-2.5">
            {{ __('Register') }}
        </x-primary-button>

    </form>

    <p class="text-center text-sm text-zinc-400">
        {{ __('Already have an account?') }}
        <a href="{{ route('login') }}" wire:navigate
           class="text-violet-600 font-medium hover:text-violet-800 transition-colors">
            {{ __('Sign in') }}
        </a>
    </p>

</div>
