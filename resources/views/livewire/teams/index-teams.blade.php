@php
    $node = 'root';
    $depth = -1;
@endphp

<div
    x-data="{
        expanded: @entangle('expanded').live,

        isExpanded: function (node) {
            return this.expanded[node] ?? true
        },

        toggleExpanded: function (node) {
            return (this.expanded[node] = ! (this.expanded[node] ?? true))
        },
    }"
>
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
        @foreach ($teams as $team)
            @if ($team->depth < $depth)
                @for ($i = 0; $i < $depth - $team->depth; $i++)
                    {!! '</div>' !!}
                @endfor
            @endif

            @if ($team->depth > $depth)
                {!!
                    '<div 
                    x-show="isExpanded(\''.$node.'\')" 
                    x-collapse  
                    class="divide-y divide-gray-200"
                    >'
                !!}
            @endif

            @php
                $node = $team->path;
                $depth = $team->depth;
            @endphp

            <div class="grid grid-cols-12 py-2">
                <div class="col-span-6">
                    <div class="flex items-center" style="padding-left: {{ $team->depth * 1.8 }}rem">
                        @if ($team->isCluster())
                            <button class="flex items-center" x-on:click="toggleExpanded('{{ $team->path }}')">
                                <x-heroicon-s-chevron-right class="mr-3 h-4 w-4 text-gray-500" x-bind:class="{ 'rotate-90': isExpanded('{{$team->path}}') }" />
                            </button>
                        @endif

                        <div>
                            <a href="{{ route('teams.show', $team) }}" class="{{ $team->isCluster() ? 'font-semibold' : '' }} hover:underline">
                                {{ $team->name }}
                            </a>

                            <span class="text-gray-400">
                                (
                                <x-stats.grade :grade="$team->grade" />
                                )
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-span-2"></div>
                <div class="col-span-2 text-gray-500">
                    @if (! $team->isCluster())
                        {{ $team->engineers->count() }} {{ __('ingenieros') }}
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
