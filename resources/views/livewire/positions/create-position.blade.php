<div x-data>
    <x-secondary-button x-on:click.prevent="$dispatch('open-modal', { name: 'create-position' })">
        <x-heroicon-o-plus class="mr-2 h-4 w-4" />
        {{ __('Agregar Posición') }}
    </x-secondary-button>

    <x-toast-notification on="position-created" title="Posición creada" />

    <x-modal name="create-position" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="save" class="p-6">
            <h2 class="mb-4 text-lg font-medium text-gray-900">
                {{ __('¿Confirmas que desear agregar una nueva posición?') }}
            </h2>

            <p class="mt-1 text-gray-600">
                {{ __('Se creará una nueva posición con el siguiente nivel disponible en el track. Puedes ajustar el objetivo de sus métricas una vez creada.') }}
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                <x-primary-button class="ms-3">
                    {{ __('Agregar Posición') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</div>
