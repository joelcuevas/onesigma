<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            @if ($team->exists)
                {{ __('Editar Equipo') }}
            @else
                {{ __('Nuevo Equipo') }}
            @endif
        </h2>
    </x-slot>

    <div class="x-card">
        <form wire:submit="update">
            <div class="space-y-12">
                <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-3">
                    <div>
                        <h2 class="font-medium text-gray-900">{{ __('Datos del Equipo') }}</h2>
                        <p class="mt-2 leading-6 text-gray-600">
                            {{ __('Información básica del equipo. Los nombres de equipo deben de ser únicos. No es posible cambiar el parent a un subequipo del mismo árbol.') }}
                        </p>
                    </div>

                    <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 md:col-span-2 md:grid-cols-6">
                        <div class="md:col-span-4">
                            <x-input-label>{{ __('Nombre') }}</x-input-label>
                            <x-text-input wire:model="name" />
                            <x-input-error :messages="$errors->get('name')" />
                        </div>

                        <div class="md:col-span-3">
                            <x-input-label>{{ __('Parent') }}</x-input-label>

                            <x-select-input wire:model="parent_id">
                                <option value="" selected>{{ __('Ninguno') }}</option>
                                @foreach ($parents as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </x-select-input>

                            <x-input-error :messages="$errors->get('parent_id')" />
                        </div>

                        <div class="md:col-span-6">
                            <div class="flex items-start items-center">
                                <div class="flex h-6 items-center">
                                    <input id="is_cluster" wire:model="is_cluster" type="checkbox" class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600" />
                                </div>
                                <div class="ml-3">
                                    <label for="is_cluster" class="font-medium text-gray-900">{{ __('Equipo Cluster') }}</label>
                                </div>
                            </div>

                            <x-input-error :messages="$errors->get('is_cluster')" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ $team->exists ? route('teams.show', $team) : route('teams') }}" class="hover:underline">
                    {{ __('Cancelar') }}
                </a>
                <x-primary-button type="submit">{{ __('Guardar') }}</x-primary-button>
            </div>
        </form>
    </div>
</div>
