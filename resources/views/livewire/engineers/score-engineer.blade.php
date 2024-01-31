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
            <div>
                <div class="space-y-6 border-b border-gray-900/10 pb-8">
                    <h2 class="text-lg font-bold text-gray-900">{{ __('Capacidades') }}</h2>
                    <x-engineers.scorer :$engineer start="0" />
                </div>
                <div class="mt-8 space-y-6 border-b border-gray-900/10 pb-8">
                    <h2 class="text-lg font-bold text-gray-900">{{ __('Competencias') }}</h2>
                    <x-engineers.scorer :$engineer start="5" />
                </div>
            </div>

            <div class="mt-8 flex items-center gap-x-6">
                <x-primary-button type="submit">{{ __('Guardar') }}</x-primary-button>
                <a href="{{ route('engineers.show', $engineer) }}" class="hover:underline">
                    {{ __('Cancelar') }}
                </a>
            </div>
        </form>
    </div>
</div>
