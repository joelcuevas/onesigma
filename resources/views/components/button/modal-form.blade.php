@props(['buttonLabel', 'submitAction', 'submitLabel'])

<div x-data="{open: false}" x-on:close-modal="open=false" class="inline-block">
  <x-button primary x-on:click="open=true" label="{{ $buttonLabel }}" icon="plus" />

  <x-modal>
    <form wire:submit.prevent="{{ $submitAction }}">
      <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 rounded-tl-lg rounded-tr-lg">
        {{ $slot }}
      </div>
      <div class="bg-gray-50 px-4 py-3 gap-x-3 sm:flex sm:flex-row-reverse sm:px-6 rounded-bl-lg rounded-br-lg">
        <x-button primary type="submit" label="{{ $submitLabel }}" />
        <x-button x-on:click="open=false" label="Cancelar" />
      </div>
    </form>
  </x-modal>
</div>