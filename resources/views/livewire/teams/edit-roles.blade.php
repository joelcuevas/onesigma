<div 
  x-data="{ open: false }" 
  x-on:close-modal="open=false" 
  class="inline-block">

  <x-button primary x-on:click="open=true" label="Editar Roles" icon="users" />

  <x-modal>
    <form wire:submit.prevent="save">
      <div class="bg-white p-4 pb-6 sm:p-8 rounded-tl-lg rounded-tr-lg">
        <h3>{{ $team->name }}</h3>

        <div class="sm:space-y-4 mt-6">
          @foreach ($team->members as $member)
            <div class="grid grid-cols-1 sm:grid-cols-2 mt-3 sm:mt-0">
              <div class="flex items-center">
                <x-label text="{{ $member->name }}" class="sm:mt-0" />
              </div>
              <div class="flex items-center">
                <x-select 
                  name="team-roles[{{ $member->id }}]" 
                  class="sm:mt-0"
                  wire:model="roles.{{ $member->id }}">

                  @foreach (App\Models\Enums\TeamRole::cases() as $c)
                    <option value="{{ $c->value }}">{{ __($c->name) }}</option>
                  @endforeach
                </x-select>
              </div>
            </div>
          @endforeach
        </div>
      </div>
      <div class="bg-gray-50 px-4 py-3 gap-x-3 flex flex-row-reverse md:flex-row sm:px-6 rounded-bl-lg rounded-br-lg">
        <x-button primary type="submit" label="Guardar" />
        <x-button tertiary wire:click="resetModal" x-on:click="open=false" label="Cancelar" />
      </div>
    </form>
  </x-modal>
</div>