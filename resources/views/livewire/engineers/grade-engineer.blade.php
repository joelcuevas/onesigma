<div 
  x-data="{ open: false, step: @entangle('step'), track: @entangle('track'), score: @entangle('score') }" 
  x-on:close-modal="open=false" 
  class="inline-block">

  <x-button primary x-on:click="open=true" label="Evaluar" icon="trophy" />

  <x-modal width="5xl">
    <form wire:submit.prevent="grade">
      <div class="bg-white p-4 pb-6 sm:p-8 rounded-tl-lg rounded-tr-lg">
        <h2 class="text-base font-semibold text-gray-900 mb-4">
          {{ __('Evaluación') }} : {{ $engineer->name }}
        </h2>

        <div class="grid grid-cols-1 gap-6 md:grid-flow-col-dense md:grid-cols-2">
          <div>
            <h3 class="font-medium mb-5">{{ $dimension->label }}</h3>

            <div class="space-y-5">
              @foreach ($dimension->levels as $l)
                <x-radio 
                  wire:model.live="score" 
                  id="{{ $l->name }}" 
                  value="{{ $loop->iteration }}" 
                  label="{{ $l->label }}"
                  help="{{ $l->description }}" />

                  <div 
                    x-show="score == {{ $l->score }}"
                    x-collapse
                    class="bg-gray-100 rounded-lg p-6 pb-2 pl-7 md:hidden grade-engineer-help text-sm text-gray-500">
                    
                    {!! $l->help !!}
                  </div>
              @endforeach
            </div>

            @error('score') <div class="text-red-800">{{ $message }}</div> @enderror
          </div>

          <div class="hidden md:block pl-8 border-l">
            @foreach ($dimension->levels as $l)
              <div x-show="score == {{ $l->score }}" class="grade-engineer-help text-sm text-gray-500 pt-10">
                {!! $l->help !!}
              </div>
            @endforeach
          </div>
        </div>
      </div>
      <div class="bg-gray-50 px-4 py-3 gap-x-3 flex flex-row-reverse md:flex-row sm:px-6 rounded-bl-lg rounded-br-lg">
        <x-button primary type="submit" label="{{ $step < 4 ? 'Siguiente' : 'Guardar' }}" />
        <x-button tertiary wire:click="resetModal" x-on:click="open=false" label="Cancelar" />
      </div>
    </form>
  </x-modal>
</div>