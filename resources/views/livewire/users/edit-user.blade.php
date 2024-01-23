<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            @if ($user->exists)
                {{ __('Editar Usuario') }}
            @else
                {{ __('Nuevo Usuario') }}
            @endif
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="x-card">
            <form wire:submit="save">
                <div class="space-y-12">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-3">
                        <div>
                            <h2 class="font-medium text-gray-900">Perfil Profesional</h2>
                            <p class="mt-2 leading-6 text-gray-600">Datos oficiales de la organización. Los ingenieros no pueden modificarlos en su perfil personal.</p>
                        </div>

                        <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 md:col-span-2 md:grid-cols-6">
                            <div class="md:col-span-4">
                                <x-input-label>{{ __('Nombre') }}</x-input-label>
                                <x-text-input wire:model="name" />
                                <x-input-error :messages="$errors->get('name')" />
                            </div>

                            <div class="md:col-span-4">
                                <x-input-label>{{ __('Email') }}</x-input-label>
                                <x-text-input wire:model="email" />
                                <x-input-error :messages="$errors->get('email')" />
                            </div>

                            <div class="md:col-span-3">
                                <x-input-label>{{ __('Rol') }}</x-input-label>

                                <x-select-input wire:model="role">
                                    @foreach ($allRoles as $role)
                                        <option value="{{ $role->value }}">{{ $role->name }}</option>
                                    @endforeach
                                </x-select-input>

                                <x-input-error :messages="$errors->get('role')" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <a href="{{ route('users') }}" class="hover:underline">
                        {{ __('Cancelar') }}
                    </a>
                    <x-primary-button type="submit">Guardar</x-primary-button>
                </div>
            </form>
        </div>

        @if ($this->user->exists)
            <div class="x-card">
                <div class="space-y-4">
                    <h2 class="font-medium text-gray-900">{{ __('Eliminar Usuario') }}</h2>
                    <p class="text-gray-600">Once the account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>
                    <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
                        {{ __('Delete Account') }}
                    </x-danger-button>

                    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
                        <form wire:submit="delete" class="p-6">
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Are you sure you want to delete the user?') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Once the account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                            </p>

                            <div class="mt-6 flex justify-end">
                                <x-secondary-button x-on:click="$dispatch('close')">
                                    {{ __('Cancel') }}
                                </x-secondary-button>

                                <x-danger-button class="ms-3">
                                    {{ __('Delete Account') }}
                                </x-danger-button>
                            </div>
                        </form>
                    </x-modal>
                </div>
            </div>
        @endif
    </div>
</div>
