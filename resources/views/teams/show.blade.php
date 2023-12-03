<x-layout.app pageTitle="{{ $team->name }} : {{ __('Equipo') }}">
  <x-slot:header>
    {{ $team->name }} <span class="text-gray-400 font-medium">: {{ __('Equipo') }}</span>
  </x-slot>

  <livewire:teams.team-members-table :$team />
</x-layout.app>