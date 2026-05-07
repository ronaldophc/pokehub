<x-app-layout>
    <x-page-header>
        <h1 class="text-xl font-semibold text-zinc-900">{{ __('Profile') }}</h1>
    </x-page-header>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <x-card>
            <livewire:profile.update-profile-information-form />
        </x-card>

        <x-card>
            <livewire:profile.update-password-form />
        </x-card>

        <x-card>
            <livewire:profile.delete-user-form />
        </x-card>

    </div>
</x-app-layout>
