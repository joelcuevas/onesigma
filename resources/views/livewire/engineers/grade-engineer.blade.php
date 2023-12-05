<div 
  x-data="{
    open: false,
    step: @entangle('step')
  }" 
  x-on:close-modal="open=false" 
  class="inline-block">

  <x-button primary x-on:click="open=true" label="Evaluar" icon="trophy" />

  <x-modal>
    <form wire:submit.prevent="grade">
      <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 rounded-tl-lg rounded-tr-lg">
        <h2 class="text-base font-semibold leading-7 text-gray-900 mb-4">
          {{ __('Evaluación') }} : {{ $engineer->name }}
        </h2>

        <div class="col-span-full">
          <div x-show="step == 0">
            <div class="space-y-5 mb-4">
              <x-radio wire:model="track" id="career" value="career" label="Carrera" help="{{ $engineer->career->name }}" />
              <x-radio wire:model="track" id="domain" value="domain" label="Dominio" help="{{ $engineer->domain->name }}" />
            </div>
          </div>

          <div x-show="step >= 1 && step <= 5">
            <h3>{{ ucwords($dimension) }}</h3>

            <div class="space-y-5 mb-4">
              @foreach ($levels as $d)
                <x-radio wire:model="score" id="{{ $d }}" value="{{ $loop->iteration }}" label="{{ ucwords($d) }}" />
              @endforeach
            </div>
          </div>
        </div>
      </div>
      <div class="bg-gray-50 px-4 py-3 gap-x-3 sm:flex sm:flex-row-reverse sm:px-6 rounded-bl-lg rounded-br-lg">
        <x-button primary type="submit" label="{{ $step < 5 ? 'Siguiente' : 'Guardar Evaluación' }}" />
        <x-button wire:click="resetModal" x-on:click="open=false" label="Cancelar" />
      </div>
    </form>
  </x-modal>
</div>