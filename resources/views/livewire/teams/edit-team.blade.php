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
            <div class="space-y-6 border-b border-gray-900/10 pb-9">
                <div class="">
                    <h2 class="text-lg font-bold text-gray-900">{{ __('Datos del Equipo') }}</h2>
                </div>

                <div>
                    <x-input-label>{{ __('Nombre') }}</x-input-label>
                    <div class="grid grid-cols-1 items-center gap-x-12 gap-y-3 lg:grid-cols-12">
                        <div class="lg:col-span-5">
                            <x-text-input wire:model="name" />
                            <x-input-error :messages="$errors->get('name')" />
                        </div>
                        <div class="flex text-gray-500 lg:col-span-7 lg:items-center">
                            <x-heroicon-o-question-mark-circle class="mr-2 h-5 w-5" />
                            {{ __('El nombre del equipo debe de ser único.') }}
                        </div>
                    </div>
                </div>

                <div>
                    <div class="grid grid-cols-1 gap-x-12 gap-y-3 lg:grid-cols-12">
                        <div class="lg:col-span-5">
                            <div class="flex items-start items-center">
                                <div class="flex h-6 items-center">
                                    <input id="is_cluster" wire:model="is_cluster" type="checkbox" class="h-5 w-5 rounded border-gray-300 text-indigo-600 shadow focus:ring-indigo-600" />
                                </div>
                                <div class="ml-3">
                                    <label for="is_cluster" class="font-medium text-gray-700">{{ __('Convertir en cluster') }}</label>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('is_cluster')" />
                        </div>
                        <div class="flex text-gray-500 lg:col-span-7 lg:items-center">
                            <x-heroicon-o-question-mark-circle class="mr-2 h-5 w-5" />
                            {{ __('Un equipo cluster puede contener a otros equipos, pero no ingenieros.') }}
                        </div>
                    </div>
                </div>

                <div>
                    <x-input-label>{{ __('Cluster') }}</x-input-label>
                    <div class="grid grid-cols-1 items-center gap-x-12 gap-y-3 lg:grid-cols-12">
                        <div class="lg:col-span-5">
                            <x-select-input wire:model="parent_id">
                                <option value="" selected>{{ __('Ninguno') }}</option>
                                @foreach ($parents as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </x-select-input>
                            <x-input-error :messages="$errors->get('parent_id')" />
                        </div>
                        <div class="flex text-gray-500 lg:col-span-7 lg:items-center">
                            <x-heroicon-o-question-mark-circle class="mr-2 h-5 w-5" />
                            {{ __('Solo los equipos cluster pueden ser padres de otros equipos.') }}
                        </div>
                    </div>
                </div>

                <div>
                    <x-input-label>{{ __('Estatus') }}</x-input-label>
                    <div class="grid grid-cols-1 items-center gap-x-12 gap-y-3 lg:grid-cols-12">
                        <div class="lg:col-span-3">
                            <x-select-input wire:model="status">
                                @foreach (App\Models\Enums\TeamStatus::cases() as $c)
                                    <option value="{{ $c->value }}">{{ $c->name }}</option>
                                @endforeach
                            </x-select-input>
                            <x-input-error :messages="$errors->get('status')" />
                        </div>
                        <div class="flex text-gray-500 lg:col-span-7 lg:col-start-6 lg:items-center">
                            <x-heroicon-o-question-mark-circle class="mr-2 h-5 w-5" />
                            {{ __('Un equipo inactivo no se considera en el cálculo de métricas.') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-9 flex items-center gap-x-6">
                <x-primary-button type="submit">{{ __('Guardar') }}</x-primary-button>
                <a href="{{ $team->exists ? route('teams.show', $team) : route('teams') }}" class="hover:underline">
                    {{ __('Cancelar') }}
                </a>
            </div>
        </form>
    </div>
</div>
