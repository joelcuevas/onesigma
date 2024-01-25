<div>
    <x-slot name="header">
        <div class="sm:flex sm:items-center sm:justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ __('Equipos') }}</h2>
            <div class="mt-3 space-x-4 sm:ml-4 sm:mt-0">
                @can('create', App\Models\Team::class)
                    <x-link-button href="{{ route('teams.create') }}">
                        {{ __('Nuevo Equipo') }}
                    </x-link-button>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="x-card">
        <div class="divide-y divide-gray-200">
            @foreach ($teams as $team)
                <div class="grid grid-cols-12 py-2">
                    <div class="col-span-6" style="padding-left: {{ $team->depth * 1.5 }}rem">
                        <a href="{{ route('teams.show', $team) }}" class="font-medium hover:underline">
                            {{ $team->name }}
                            <span class="text-gray-400">
                                (
                                <x-stats.grade :grade="$team->grade" />
                                )
                            </span>
                        </a>
                    </div>
                    <div class="col-span-2"></div>
                    <div class="col-span-2">
                        @if (! $team->isCluster())
                            {{ $team->engineers->count() }} {{ __('ingenieros') }}
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
