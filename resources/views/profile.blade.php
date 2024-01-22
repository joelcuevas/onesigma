<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Perfil') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <x-panel>
            <div class="max-w-xl">
                <livewire:profile.update-profile-information-form />
            </div>
        </x-panel>

        @if (Auth::user()->hasPassword())
            <x-panel>
                <div class="max-w-xl">
                    <livewire:profile.update-password-form />
                </div>
            </x-panel>
        @endif

        <x-panel>
            <div class="max-w-xl">
                <livewire:profile.delete-user-form />
            </div>
        </x-panel>
    </div>
</x-app-layout>
