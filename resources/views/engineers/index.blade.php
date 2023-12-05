<x-layout.app title="{{ __('Ingenieros') }}">
  <x-layout.panel>
    <div class="text-right">
      <form action="{{ route('engineers') }}" method="get">
        <div class="inline-block relative rounded-md shadow-sm">
          <div class="pointer-events-none absolute inset-y-0 left-0 text-gray-400 flex items-center pl-3">
            <x-heroicon-o-magnifying-glass class="h-5 w-5" />
          </div>
          <a href="{{ route('engineers') }}" class="absolute inset-y-0 right-2 flex items-center pl-3 text-gray-400 hover:text-indigo-600">
            <x-heroicon-o-x-mark class="h-5 w-5" />
          </a>
          <input 
            type="text" 
            name="name" 
            placeholder="{{ __('Buscar ingeniero') }}" 
            class="block w-full rounded-md border-0 py-1.5 pl-10 pr-8 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6"
            value="{{ request()->name }}">
        </div>
      </form>
    </div>

    <x-table :headers="['Nombre', 'Posición', '']">
      <x-slot:body>
        @foreach ($engineers as $engineer)
          <tr>
            <x-table.td class="font-medium text-gray-900">{{ $engineer->name }}</x-table.td>
            <x-table.td>{{ $engineer->position }}</x-table.td>
            <x-table.td class="font-medium text-right">
              <x-table.link href="{{ route('engineers.show', $engineer) }}">Detalles</x-table.link>
            </x-table.td>
          </tr>
        @endforeach
      </x-slot:body>
    </x-table>
  </x-layout.panel>
</x-layout.app>