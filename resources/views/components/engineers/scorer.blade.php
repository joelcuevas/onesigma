@props(['engineer', 'start'])
<div class="space-y-3">
    @for ($i = $start; $i < $start + 5; $i++)
        <div>
            <x-input-label>{{ $engineer->position->getSkillLabel($i) }}</x-input-label>

            <div class="grid grid-cols-1 gap-x-12 gap-y-3 md:grid-cols-12">
                <div class="md:col-span-5">
                    <div class="grid grid-cols-6 gap-3">
                        @for ($l = 0; $l <= 5; $l++)
                            <label
                                x-data="{ selected: false }"
                                x-bind:class="{
                                    'bg-indigo-600 text-white hover:bg-indigo-500': s{{ $i }} == '{{ $l }}',
                                    'ring-1 ring-inset ring-gray-300 bg-white text-gray-900 hover:bg-gray-50':
                                        s{{ $i }} != '{{ $l }}',
                                    'ring-2 ring-indigo-600 ring-offset-2': selected,
                                }"
                                class="flex cursor-pointer items-center justify-center rounded-md px-3 py-3 text-sm font-semibold uppercase focus:outline-none sm:flex-1"
                            >
                                <input x-on:focusin="selected = true" x-on:focusout="selected = false" wire:model="s{{ $i }}" type="radio" name="s{{ $i }}" value="{{ $l }}" class="sr-only" />
                                <span>{{ $l }}</span>
                            </label>
                        @endfor
                    </div>

                    <x-input-error :messages="$errors->get('s'.$i)" />
                </div>
                <div class="text-gray-500 md:col-span-7">
                    @if (isset($engineer->position->levels[$i]))
                        @for ($l = 0; $l <= 5; $l++)
                            <div x-show="s{{ $i }} == {{ $l }}" style="display: none">
                                <p class="leading-tight">{{ $engineer->position->levels[$i]["l{$l}_description"] }}</p>
                            </div>
                        @endfor
                    @endif
                </div>
            </div>
        </div>
    @endfor
</div>
