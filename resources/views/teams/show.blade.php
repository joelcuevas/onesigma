<x-layout.app title="{{ $team->name }} : {{ __('Equipo') }}">
  <x-slot:header>
    {{ $team->name }} <span class="text-gray-400 font-medium">: {{ __('Equipo') }}</span>
  </x-slot>

  <livewire:teams.team-members-table :$team />

  @if ($team->nestedTeams->count())
    <x-layout.panel class="px-8 py-4">
      <div class="py-6 sm:flex sm:items-center">
        <div class="sm:flex-auto">
          <h1 class="text-lg font-semibold leading-6 text-gray-900">
            {{ __('Equipos Anidados') }}
          </h1>
          <p class="mt-2 text-sm text-gray-700">
            <x-heroicon-o-briefcase class="inline-block h-5 w-5 mr-1 align-bottom" />
            {{ count($team->nestedTeams) }} {{ __('equipos') }}
          </p>
        </div>
      </div>

      <x-table :headers="['Nombre', '']">
        <x-slot:body>
          @foreach ($team->nestedTeams->sortBy('name') as $nestedTeam)
            <tr>
              <x-table.td class="font-medium text-gray-900">{{ $nestedTeam->name }}</x-table.td>  
              <x-table.td class="font-medium text-right">
                <x-table.link href="{{ route('teams.show', $nestedTeam) }}">Detalles</x-table.link>
              </x-table.td>
            </tr>
          @endforeach
        </x-slot:body>
      </x-table>  
    </x-layout.panel>
  @endif
</x-layout.app>