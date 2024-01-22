<div>
    <x-slot name="header" x-data x-on:engineer-updated.window="$refresh">
        <h2 class="text-xl leading-tight text-gray-800">
            <span class="font-semibold">{{ __('Editar Ingeniero') }}</span>
        </h2>
    </x-slot>

    <div class="x-card">
        <form wire:submit="update">
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
                            <x-input-label>{{ __('Track') }}</x-input-label>

                            <x-select-input wire:model="track">
                                @foreach ($tracks as $track => $title)
                                    <option value="{{ $track }}">{{ $title }}</option>
                                @endforeach
                            </x-select-input>

                            <x-input-error :messages="$errors->get('track')" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('engineers.show', $engineer) }}" class="hover:underline">
                    {{ __('Cancelar') }}
                </a>
                <x-primary-button type="submit">Guardar</x-primary-button>
            </div>
        </form>
    </div>
</div>
