<x-layout.panel class="px-8 py-4">
  <x-table :headers="['Nombre', 'Miembros', 'Equipos']">
    <x-slot:body>
      @foreach ($teams as $team)
        <tr>
          <!-- pl-0 pl-1 pl-2 pl-3 -->
          <x-table.td class="font-medium text-gray-900" style="padding-left: {{ $team->nestedLevel * 25 }}px">
            <div>{{ $team->name }}</div>
          </x-table.td>
          <x-table.td>
            {{ $team->members_count }}
          </x-table.td>
          <x-table.td>
            {{ $team->nestedTeams->count() }}
          </x-table.td>
          <x-table.td class="font-medium text-right">
            <x-table.link href="{{ route('teams.show', $team) }}">{{ __('Detalles') }}</x-table.link>
          </x-table.td>
        </tr>
      @endforeach
    </x-slot>
  </x-table>
</x-layout.panel>
