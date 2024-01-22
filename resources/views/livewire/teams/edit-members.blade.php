<div x-data>
    <x-secondary-button x-on:click.prevent="$dispatch('open-modal', {name: '{{ $name }}'})">
        <x-heroicon-o-users class="mr-2 h-4 w-4" />
        {{ __('Editar') }}
    </x-secondary-button>

    <x-toast-notification on="team-members-updated" title="Equipo actualizado" />

    <x-modal name="{{ $name }}">
        <div class="x-card">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Editar Miembros') }}
            </h2>

            <div class="mt-3 flex space-x-4">
                <x-select-input wire:model="orphanId" required>
                    <option value="" disabled selected>Agregar miembro...</option>

                    @foreach ($orphans->sortBy('name') as $o)
                        <option value="{{ $o->id }}">{{ $o->name }} {{ '<'.$o->email.'>' }}</option>
                    @endforeach
                </x-select-input>

                <x-secondary-button wire:click="add" class="group">
                    <x-heroicon-o-plus class="h-4 w-4" />
                </x-secondary-button>
            </div>

            <form wire:submit="save">
                <div class="mt-2 divide-y divide-gray-100">
                    @foreach ($members->sortBy('name') as $member)
                        <div wire:key="{{ $member->id }}" class="py-4 sm:flex sm:items-center sm:justify-between sm:py-2">
                            <div class="overflow-hidden text-ellipsis whitespace-nowrap sm:w-80">
                                {{ $member->name }}
                            </div>
                            <div class="mt-2 flex w-full justify-stretch space-x-4 sm:mt-0 sm:w-64">
                                <x-select-input name="team-roles[{{ $member->id }}]" wire:model="memberRoles.{{ $member->id }}">
                                    @foreach ($allRoles as $c)
                                        <option value="{{ $c->value }}">{{ __($c->name) }}</option>
                                    @endforeach
                                </x-select-input>
                                <x-secondary-button wire:click="remove({{ $member->id }})" class="group">
                                    <x-heroicon-o-trash class="h-4 w-4 group-hover:text-red-900" />
                                </x-secondary-button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <a href="#" x-on:click.prevent="$dispatch('close-modal', { name: '{{ $name }}' })" class="hover:underline">
                        {{ __('Cancelar') }}
                    </a>
                    <x-primary-button>{{ __('Guardar') }}</x-primary-button>
                </div>
            </form>
        </div>
    </x-modal>
</div>
