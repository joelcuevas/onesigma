<x-layout.panel>
  <x-table :headers="['Nombre', 'Posición', 'WCD', '']">
    <x-slot:body>
      @foreach ($engineers as $engineer)
        <tr>
          <x-table.td class="font-medium text-gray-900">{{ $engineer->name }}</x-table.td>
          <x-table.td>{{ $engineer->position }}</x-table.td>
          <x-table.td>{{ $engineer->weeklyCodingDays }}</x-table.td>
          <x-table.td class="font-medium text-right">
            <x-table.link href="{{ route('engineers.show', $engineer) }}">Detalles</x-table.link>
          </x-table.td>
        </tr>
      @endforeach
    </x-slot:body>
  </x-table>
</x-layout.panel>
