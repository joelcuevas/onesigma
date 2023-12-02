<x-layout.app pageTitle="{{ __('Equipos') }}">
  <x-slot:actions>
    <x-button primary href="{{ route('teams.create') }}" label="Nuevo Equipo" icon="plus" />
  </x-slot>

  <x-layout.panel>
    <x-table :headers="['Nombre', '']">
      <x-slot:body>
        @foreach ($teams as $team)
          <tr>
            <x-table.td class="font-medium text-gray-900">{{ $team->name }}</x-table.td>
            <x-table.td class="font-medium text-right">
              <x-table.link href="{{ route('teams.show', $team) }}">{{ __('Detalles') }}</x-table.link>
            </x-table.td>
          </tr>
        @endforeach
      </x-slot>
    </x-table>
  </x-layout.panel>
</x-layout.app>