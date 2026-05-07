<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="space-y-5">
    <div>
        <h3 class="text-base font-semibold text-zinc-800">{{ __('Delete Account') }}</h3>
        <p class="text-sm text-zinc-400 mt-0.5">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}</p>
    </div>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="text-sm font-medium text-red-500 hover:text-red-700 transition-colors"
    >
        {{ __('Delete Account') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-6 space-y-5">
            <div>
                <h3 class="text-base font-semibold text-zinc-900">{{ __('Are you sure you want to delete your account?') }}</h3>
                <p class="mt-1 text-sm text-zinc-500">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.') }}</p>
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" class="sr-only" />
                <x-text-input wire:model="password" id="password" name="password" type="password"
                              class="block w-full" placeholder="{{ __('Password') }}" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-red-700 focus:outline-none transition-colors">
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
