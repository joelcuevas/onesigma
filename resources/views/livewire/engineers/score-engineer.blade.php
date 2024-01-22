<div>
    <x-slot name="header" x-data x-on:engineer-updated.window="$refresh">
        <h2 class="text-xl leading-tight text-gray-800">
            <span class="font-semibold">{{ $engineer->name }}</span>
        </h2>
    </x-slot>

    <div class="x-card">
        <form wire:submit="score">
            <div class="space-y-12">
                <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-3">
                    <div>
                        <h2 class="font-medium text-gray-900">Capacidades</h2>
                        <p class="mt-2 leading-6 text-gray-600">Engineering ladders.</p>
                    </div>

                    <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 md:col-span-2 md:grid-cols-6">
                        @for ($i = 0; $i < 5; $i++)
                            <div class="md:col-span-4">
                                <x-input-label>{{ $engineer->position["s{$i}_label"] }}</x-input-label>
                                @for ($l = 0; $l <= 5; $l++)
                                    <div class="mr-4 inline-block">
                                        <x-radio-input wire:model="s{{ $i }}" :name="'s'.$i" :value="$l" :id="'s'.$i.'l'.$l" :label="$l"></x-radio-input>
                                    </div>
                                @endfor

                                <x-input-error :messages="$errors->get('s'.$i)" />
                            </div>
                        @endfor
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-3">
                    <div>
                        <h2 class="font-medium text-gray-900">Competencias</h2>
                        <p class="mt-2 leading-6 text-gray-600">Hard skills por dominio.</p>
                    </div>

                    <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 md:col-span-2 md:grid-cols-6">
                        @for ($i = 5; $i < 10; $i++)
                            <div class="md:col-span-4">
                                <x-input-label>{{ $engineer->position["s{$i}_label"] }}</x-input-label>
                                @for ($l = 0; $l <= 5; $l++)
                                    <div class="mr-4 inline-block">
                                        <x-radio-input wire:model="s{{ $i }}" :name="'s'.$i" :value="$l" :id="'s'.$i.'l'.$l" :label="$l"></x-radio-input>
                                    </div>
                                @endfor

                                <x-input-error :messages="$errors->get('s'.$i)" />
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('engineers.show', $engineer) }}" class="hover:underline">
                    {{ __('Cancelar') }}
                </a>
                <x-primary-button type="submit">{{ __('Guardar Evaluación') }}</x-primary-button>
            </div>
        </form>
    </div>
</div>
