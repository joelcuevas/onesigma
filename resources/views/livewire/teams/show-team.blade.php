<div class="space-y-6">
  <x-slot:header>
    {{ $team->name }} <span class="text-gray-400 font-medium">: {{ __('Equipo') }}</span>
  </x-slot:header>

  <div x-data x-on:engineers-updated="$wire.$refresh" class="space-y-6">
    <div>
      <h3 class="text-base font-semibold leading-6 text-gray-900">{{ __('Últimos 7 días') }}</h3>
      <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
          <dt class="truncate text-sm font-medium text-gray-500">{{ __('Weekly Coding Days') }}</dt>
          <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $team->getMetric('wcd') }}</dd>
        </div>
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
          <dt class="truncate text-sm font-medium text-gray-500">{{ __('Autonomía') }}</dt>
          <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">--</dd>
        </div>
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
          <dt class="truncate text-sm font-medium text-gray-500">{{ __('Maduréz') }}</dt>
          <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">--</dd>
        </div>
      </dl>
    </div>

    <x-layout.panel class="px-8 py-4">
      <div class="py-4 sm:flex sm:items-center">
        <div class="sm:flex-auto">
          <h1 class="text-lg font-semibold leading-6 text-gray-900">{{ __('Miembros del Equipo') }}</h1>
          <p class="mt-2 text-sm text-gray-700">
            <x-heroicon-o-users class="inline-block h-5 w-5 mr-1 align-bottom" />
            {{ count($team->members) }} {{ __('miembros') }}
          </p>
        </div>
      </div>

      @if ($team->members->count())
        <x-table :headers="['Nombre', 'Dominio', 'Rol', 'WCD', '']">
          <x-slot:body>
            @foreach ($team->members as $member)
              <tr>
                <x-table.td class="font-medium text-gray-900">{{ $member->name }}</x-table.td>
                <x-table.td>{{ $member->career_name }} @ {{ $member->domain_name }}</x-table.td>
                <x-table.td>{{ __(ucfirst($member->pivot->role)) }}</x-table.td>
                <x-table.td>{{ $member->getMetric('wcd') }}</x-table.td>
                <x-table.td class="font-medium text-right">
                  <x-table.link href="{{ route('engineers.show', $member) }}">Detalles</x-table.link>
                </x-table.td>
              </tr>
            @endforeach
          </x-slot:body>
        </x-table>
      @endif
    </x-layout.panel>
  </div>

  @if ($team->nestedTeams->count())
    <x-layout.panel class="px-8 py-4">
      <div class="py-4 sm:flex sm:items-center">
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
</div>