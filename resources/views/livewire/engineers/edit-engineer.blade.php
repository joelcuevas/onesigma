<div>
    <x-slot name="header" x-data x-on:engineer-updated.window="$refresh">
        <h2 class="text-xl leading-tight text-gray-800">
            <span class="font-semibold">{{ __('Editar Ingeniero') }}</span>
        </h2>
    </x-slot>

    <div class="x-card">
        <form wire:submit="update">
            <div class="space-y-6 border-b border-gray-900/10 pb-8">
                <h2 class="text-lg font-bold text-gray-900">
                    {{ __('Perfil Profesional') }}
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
                            {{ __('Nombre oficial en la organización. El ingeniero no puede modificarlo.') }}
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
                            {{ __('Email oficial en la organización. El ingeniero no puede modificarlo.') }}
                        </div>
                    </div>
                </div>

                <div>
                    <x-input-label>{{ __('Track') }}</x-input-label>
                    <div class="grid grid-cols-1 gap-x-12 gap-y-3 lg:grid-cols-12">
                        <div class="lg:col-span-5">
                            <x-select-input wire:model="track">
                                @foreach ($tracks as $track => $title)
                                    <option value="{{ $track }}">{{ $title }}</option>
                                @endforeach
                            </x-select-input>

                            <x-input-error :messages="$errors->get('track')" />
                        </div>
                        <div class="flex pt-2 text-gray-500 lg:col-span-7 lg:items-start">
                            <x-heroicon-o-question-mark-circle class="mr-2 h-5 w-5 min-w-5" />
                            {{ __('Track de desarrollo de carrera. Debe coincidir con el registrado en RR.HH.') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-x-6">
                <x-primary-button type="submit">Guardar</x-primary-button>
                <a href="{{ route('engineers.show', $engineer) }}" class="hover:underline">
                    {{ __('Cancelar') }}
                </a>
            </div>
        </form>
    </div>
</div>
