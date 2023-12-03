<div x-data x-on:engineers-updated="$wire.$refresh">
  <x-layout.panel>
    <div class="py-6 sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-lg font-semibold leading-6 text-gray-900">{{ __('Miembros del Equipo') }}</h1>
        <p class="mt-2 text-sm text-gray-700">
          <x-heroicon-o-users class="inline-block h-5 w-5 mr-1 align-bottom" />
          {{ count($team->engineers) }} {{ __('ingenieros') }}
        </p>
      </div>
    </div>

    @if ($team->engineers->count())
      <x-table :headers="['Nombre', 'Posición', 'Rol', '']">
        <x-slot:body>
          @foreach ($team->engineers->sortBy('name') as $engineer)
            <tr>
              <x-table.td class="font-medium text-gray-900">{{ $engineer->name }}</x-table.td>
              <x-table.td>{{ $engineer->position }}</x-table.td>
              <x-table.td>{{ __(ucfirst($engineer->pivot->role)) }}</x-table.td>
              <x-table.td class="font-medium text-right">
                <x-table.link href="#">Ladder</x-table.link>
              </x-table.td>
            </tr>
          @endforeach
        </x-slot:body>
      </x-table>
    @endif
  </x-layout.panel>
</div>