<x-layout.app pageTitle="{{ __('Equipo') }}: {{ $team->name }}">
  <x-slot:header>
    <span class="text-gray-400 font-medium">{{ __('Team') }}:</span> {{ $team->name }}
  </x-slot>

  <livewire:teams.team-members-table :$team />
</x-layout.app>