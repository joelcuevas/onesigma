<div x-data="{
    s0: @entangle('s0'),
    s1: @entangle('s1'),
    s2: @entangle('s2'),
    s3: @entangle('s3'),
    s4: @entangle('s4'),
    s5: @entangle('s5'),
    s6: @entangle('s6'),
    s7: @entangle('s7'),
    s8: @entangle('s8'),
    s9: @entangle('s9'),
    focused: function (el) {
        return el.getElementsByTagName('input')[0] === document.activeElement;
    }
}">
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
                                
                                <div class="grid gap-3 grid-cols-6">
                                    @for ($l = 0; $l <= 5; $l++)
                                        <label
                                            x-data="{ selected: false }"
                                            x-bind:class="{
                                                'bg-indigo-600 text-white hover:bg-indigo-500' : s{{$i}} == '{{$l}}',
                                                'ring-1 ring-inset ring-gray-300 bg-white text-gray-900 hover:bg-gray-50' : s{{$i}} != '{{$l}}',
                                                'ring-2 ring-indigo-600 ring-offset-2': selected,
                                            }"

                                            class="flex items-center justify-center rounded-md py-3 px-3 text-sm font-semibold uppercase sm:flex-1 cursor-pointer focus:outline-none"
                                        >
                                            <input 
                                                x-on:focusin="selected = true"
                                                x-on:focusout="selected = false"
                                                wire:model="s{{ $i }}" type="radio" name="s{{$i}}" value="{{$l}}" class="sr-only">
                                            <span>{{ $l }}</span>
                                        </label>
                                    @endfor
                                </div>

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
                                
                                <div class="grid gap-3 grid-cols-6">
                                    @for ($l = 0; $l <= 5; $l++)
                                        <label
                                            x-data="{ selected: false }"
                                            x-bind:class="{
                                                'bg-indigo-600 text-white hover:bg-indigo-500' : s{{$i}} == '{{$l}}',
                                                'ring-1 ring-inset ring-gray-300 bg-white text-gray-900 hover:bg-gray-50' : s{{$i}} != '{{$l}}',
                                                'ring-2 ring-indigo-600 ring-offset-2': selected,
                                            }"

                                            class="flex items-center justify-center rounded-md py-3 px-3 text-sm font-semibold uppercase sm:flex-1 cursor-pointer focus:outline-none"
                                        >
                                            <input 
                                                x-on:focusin="selected = true"
                                                x-on:focusout="selected = false"
                                                wire:model="s{{ $i }}" type="radio" name="s{{$i}}" value="{{$l}}" class="sr-only">
                                            <span>{{ $l }}</span>
                                        </label>
                                    @endfor
                                </div>

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
