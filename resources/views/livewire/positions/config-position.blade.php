<div>
    <x-slot name="header">
        <div class="sm:flex sm:items-center sm:justify-between">
            <h2 class="text-xl leading-tight text-gray-800">
                <span class="font-semibold">{{ $position->title }}</span>
                <span class="text-gray-500">- {{ __('Configurar') }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="flex flex-col space-y-6">
        <div class="x-card">
            <form wire:submit="save">
                <div class="space-y-6 border-b border-gray-900/10 pb-8">
                    <h2 class="text-lg font-bold text-gray-900">
                        {{ __('Datos de la Posición') }}
                    </h2>

                    <div>
                        <x-input-label>{{ __('Título') }}</x-input-label>
                        <div class="grid grid-cols-1 gap-x-12 gap-y-3 lg:grid-cols-12">
                            <div class="lg:col-span-5">
                                <x-text-input wire:model="title" />
                                <x-input-error :messages="$errors->get('title')" />
                            </div>
                            <div class="flex pt-2 text-gray-500 lg:col-span-7 lg:items-start">
                                <x-heroicon-o-question-mark-circle class="mr-2 h-5 w-5 min-w-5" />
                                {{ __('Título de la posición. El nivel se agrega automáticamente.') }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <x-input-label>{{ __('Prefijo') }}</x-input-label>
                        <div class="grid grid-cols-1 gap-x-12 gap-y-3 lg:grid-cols-12">
                            <div class="lg:col-span-2">
                                <x-text-input wire:model="group" maxlength="3" />
                                <x-input-error :messages="$errors->get('group')" />
                            </div>
                            <div class="flex pt-2 text-gray-500 lg:col-span-7 lg:col-start-6 lg:items-start">
                                <x-heroicon-o-question-mark-circle class="mr-2 h-5 w-5 min-w-5" />
                                {{ __('Prefijo para la clave de la posición. El nivel se agrega automáticamente.') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex items-center gap-x-6">
                    <x-primary-button type="submit">Guardar</x-primary-button>
                    <a href="{{ route('positions.show', $position) }}" class="hover:underline">
                        {{ __('Cancelar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>