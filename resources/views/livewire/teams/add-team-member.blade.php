<x-button.modal-form 
  buttonLabel="Agregar Ingeniero" 
  submitLabel="Agregar"
  submitAction="add"> 

  <x-label text="Ingeniero" />
  <x-combobox modelId="engineerId" modelLabel="engineerName" :items="$engineers" />

  <x-label text="Rol" />
  <x-select wire:model="role">
    <option value="D">{{ __('Developer') }}</option>
    <option value="TL">{{ __('Tech Lead') }}</option>
    <option value="TPM">{{ __('Technical Program Manager') }}</option>
    <option value="EM">{{ __('Engineering Manager') }}</option>
  </x-select>
</x-button.modal-form>