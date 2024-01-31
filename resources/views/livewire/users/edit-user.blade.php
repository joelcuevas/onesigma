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
                <div class="space-y-6 border-b border-gray-900/10 pb-8">
                    <h2 class="text-lg font-bold text-gray-900">
                        {{ __('Perfil del Usuario') }}
                    </h2>

                    <div>
                        <x-input-label>{{ __('Nombre') }}</x-input-label>
                        <div class="grid grid-cols-1 gap-x-12 gap-y-3 lg:grid-cols-12">
                            <div class="lg:col-span-5">
                                <x-text-input wire:model="name" />
                                <x-input-error :messages="$errors->get('name')" />
                            </div>
                            <div class="flex pt-2 text-gray-500 lg:col-span-7 lg:items-start">
                                <x-heroicon-o-question-mark-circle class="mr-2 h-5 w-5 min-w-5" />
                                {{ __('El usuario puede modificarlo desde su perfil.') }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <x-input-label>{{ __('Email') }}</x-input-label>
                        <div class="grid grid-cols-1 gap-x-12 gap-y-3 lg:grid-cols-12">
                            <div class="lg:col-span-5">
                                <x-text-input wire:model="email" />
                                <x-input-error :messages="$errors->get('email')" />
                            </div>
                            <div class="flex pt-2 text-gray-500 lg:col-span-7 lg:items-start">
                                <x-heroicon-o-question-mark-circle class="mr-2 h-5 w-5 min-w-5" />
                                {{ __('El usuario puede modificarlo. Puede ser usado para iniciar sesion con Github.') }}
                            </div>
                        </div>
                    </div>

                    @if (! $user->exists || $user->hasPassword())
                        <div>
                            <x-input-label>{{ __('Contraseña') }}</x-input-label>
                            <div class="grid grid-cols-1 gap-x-12 gap-y-3 lg:grid-cols-12">
                                <div class="lg:col-span-5">
                                    <x-text-input wire:model="password" />
                                    <x-input-error :messages="$errors->get('password')" />
                                </div>
                                <div class="flex pt-2 text-gray-500 lg:col-span-7 lg:items-start">
                                    <x-heroicon-o-question-mark-circle class="mr-2 h-5 w-5 min-w-5" />
                                    {{ __('Solo si desea que el usuario inicie sesión contraseña; vacío si no.') }}
                                </div>
                            </div>
                        </div>
                    @endif

                    <div>
                        <x-input-label>{{ __('Rol') }}</x-input-label>
                        <div class="grid grid-cols-1 gap-x-12 gap-y-3 lg:grid-cols-12">
                            <div class="lg:col-span-5">
                                <x-select-input wire:model="role">
                                    @foreach ($allRoles as $role)
                                        <option value="{{ $role->value }}">{{ $role->name }}</option>
                                    @endforeach
                                </x-select-input>

                                <x-input-error :messages="$errors->get('role')" />
                            </div>
                            <div class="flex pt-2 text-gray-500 lg:col-span-7 lg:items-start">
                                <x-heroicon-o-question-mark-circle class="mr-2 h-5 w-5 min-w-5" />
                                {{ __('Importante: los admins pueden crear otros usuarios.') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex items-center gap-x-6">
                    <x-primary-button type="submit">Guardar</x-primary-button>
                    <a href="{{ route('users') }}" class="hover:underline">
                        {{ __('Cancelar') }}
                    </a>
                </div>
            </form>
        </div>

        @if ($user->exists)
            @can('delete', $user)
                <div class="x-card">
                    <div class="space-y-5">
                        <h2 class="text-lg font-bold text-red-800">{{ __('Eliminar Usuario') }}</h2>
                        <p class="max-w-2xl font-medium text-red-800">
                            {{ __('Una vez que la cuenta sea borrada, se eliminará permanentemente toda la información y recursos del usuario. Esta acción no se puede deshacer.') }}
                        </p>

                        <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
                            {{ __('Eliminar Usuario') }}
                        </x-danger-button>

                        <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
                            <form wire:submit="delete" class="p-6">
                                <h2 class="mb-4 text-lg font-medium text-gray-900">
                                    {{ __('¿Confirmas que deseas eliminar al usuario?') }}
                                </h2>

                                <p class="mt-1 text-gray-600">
                                    {{ __('Una vez que la cuenta sea borrada, se eliminará permanentemente toda la información y recursos del usuario. Esta acción no se puede deshacer.') }}
                                </p>

                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button x-on:click="$dispatch('close')">
                                        {{ __('Cancelar') }}
                                    </x-secondary-button>

                                    <x-danger-button class="ms-3">
                                        {{ __('Eliminar Usuario') }}
                                    </x-danger-button>
                                </div>
                            </form>
                        </x-modal>
                    </div>
                </div>
            @endcan
        @endif
    </div>
</div>
