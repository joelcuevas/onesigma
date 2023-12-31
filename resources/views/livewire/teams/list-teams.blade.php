<x-layout.panel class="px-8 py-4">
  <x-table :headers="['Nombre', 'Miembros', 'WCD']">
    <x-slot:body>
      @foreach ($teams as $team)
        <tr>
          <x-table.td class="font-medium text-gray-900">
            <div style="padding-left: {{ $team->nestedLevel * 25 }}px">{{ $team->name }}</div>
          </x-table.td>
          <x-table.td>
            {{ $team->members_count }}
          </x-table.td>
          <x-table.td>
            {{ $team->getMetric('wcd') }}
          </x-table.td>
          <x-table.td class="font-medium text-right">
            <x-table.link href="{{ route('teams.show', $team) }}">{{ __('Detalles') }}</x-table.link>
          </x-table.td>
        </tr>
      @endforeach
    </x-slot>
  </x-table>
</x-layout.panel>
