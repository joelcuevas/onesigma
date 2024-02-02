<div>
    <x-slot name="header">
        <div class="sm:flex sm:items-center sm:justify-between">
            <h2 class="text-xl leading-tight text-gray-800">
                <span class="font-semibold text-gray-500">{{ __('Track:') }}</span>
                <span class="font-semibold">{{ $position->title }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="flex flex-col space-y-6">
        <div class="x-card">
            <form wire:submit="save">
                <div class="space-y-8">
                    <div class="space-y-6 border-b border-gray-900/10 pb-10">
                        <h2 class="text-lg font-bold text-gray-900">
                            {{ __('Configuración del Track') }}
                        </h2>

                        <div>
                            <x-input-label>{{ __('Título') }}</x-input-label>
                            <div class="grid grid-cols-1 gap-x-12 gap-y-3 lg:grid-cols-12">
                                <div class="lg:col-span-5">
                                    <x-text-input wire:model="title" />
                                    <x-input-error :messages="$errors->get('title')" />
                                </div>
                                <div class="flex pt-2 text-gray-500 lg:col-span-7 lg:items-start">
                                    <x-heroicon-o-information-circle class="mr-2 h-5 w-5 min-w-5" />
                                    {{ __('Título de las posiciones asociadas a este track. El nivel se agrega automáticamente.') }}
                                </div>
                            </div>
                        </div>

                        <div>
                            <x-input-label>{{ __('Clave') }}</x-input-label>
                            <div class="grid grid-cols-1 gap-x-12 gap-y-3 lg:grid-cols-12">
                                <div class="lg:col-span-2">
                                    <x-text-input wire:model="code" maxlength="3" />
                                    <x-input-error :messages="$errors->get('code')" />
                                </div>
                                <div class="flex pt-2 text-gray-500 lg:col-span-7 lg:col-start-6 lg:items-start">
                                    <x-heroicon-o-information-circle class="mr-2 h-5 w-5 min-w-5" />
                                    {{ __('Prefijo de la clave de las posiciones asociadas a este track.') }}
                                </div>
                            </div>
                        </div>

                        @if (! $position->exists)
                            <div>
                                <x-input-label>{{ __('Tipo') }}</x-input-label>
                                <div class="grid grid-cols-1 gap-x-12 gap-y-3 lg:grid-cols-12">
                                    <div class="lg:col-span-3">
                                        <x-select-input wire:model="type">
                                            @foreach (\App\Models\Enums\PositionType::cases() as $type)
                                                <option value="{{ $type->value }}">{{ $type->name }}</option>
                                            @endforeach
                                        </x-select-input>
                                        <x-input-error :messages="$errors->get('type')" />
                                    </div>
                                    <div class="flex pt-2 text-gray-500 lg:col-span-7 lg:col-start-6 lg:items-start">
                                        <x-heroicon-o-question-mark-circle class="mr-2 h-5 w-5 min-w-5" />
                                        {{ __('Importante: El tipo de un track no puede ser editado después de crearse.') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    @foreach ([['Capacidades', 0], ['Competencias', 5]] as $group)
                        <div class="space-y-6 border-b border-gray-900/10 pb-8">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">
                                    {{ __($group[0].' Esperadas') }}
                                </h2>

                                <p class="flex text-gray-500">
                                    {{ __('Nombre y descripción de cada uno de los niveles de las habilidades esperadas para la posición.') }}
                                </p>
                            </div>

                            @for ($i = $group[1]; $i < $group[1]+5; $i++)
                                <div>
                                    <x-input-label>{{ __($group[0]) }} - {{ __('Habilidad') }} #{{ $i + 1 - $group[1] }}</x-input-label>
                                    <div class="mb-4 grid grid-cols-1 gap-x-12 gap-y-3 lg:grid-cols-12">
                                        <div class="lg:col-span-5">
                                            <x-text-input wire:model="labels.{{ $i }}" maxlength="12" />
                                            <x-input-error :messages="$errors->get('labels.'.$i)" />
                                        </div>
                                        <div class="lg:col-span-7 lg:col-start-6">
                                            <div class="space-y-6">
                                                @for ($j = 0; $j < 6; $j++)
                                                    <div class="grid grid-cols-1 gap-x-12 gap-y-3 lg:grid-cols-12">
                                                        <div class="lg:col-span-12">
                                                            <div class="flex items-center">
                                                                <div class="w-24 font-medium text-gray-600">{{ __('Nivel') }} {{ $j }}:</div>
                                                                <x-text-input wire:model="levels.{{ $i }}.{{ $j }}" maxlength="255" />
                                                            </div>
                                                            <x-input-error :messages="$errors->get('levels.'.$i.'.'.$j)" />
                                                        </div>
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 flex items-center gap-x-6">
                    <x-primary-button type="submit">Guardar</x-primary-button>
                    <a href="{{ $position->exists ? route('tracks.show', $position) : route('tracks') }}" class="hover:underline">
                        {{ __('Cancelar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
