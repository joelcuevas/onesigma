<div
    x-data="{
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
            return el.getElementsByTagName('input')[0] === document.activeElement
        },
    }"
>
    <x-slot name="header" x-data x-on:engineer-updated.window="$refresh">
        <h2 class="text-xl leading-tight text-gray-800">
            <span class="font-semibold">{{ $engineer->name }}</span>
        </h2>
    </x-slot>

    <div class="x-card">
        <form wire:submit="score">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mb-6">
                        <h2 class="font-bold text-gray-900">{{ __('Capacidades') }}</h2>
                        <p class="mt-1 leading-6 text-gray-600">Engineering ladders.</p>
                    </div>
                    <x-engineers.scorer :$engineer start="0" />
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <div>
                        <h2 class="font-medium text-gray-900">Competencias</h2>
                        <p class="mt-2 leading-6 text-gray-600">Hard skills por dominio.</p>
                    </div>
                    <x-engineers.scorer :$engineer start="5" />
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
