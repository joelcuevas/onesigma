@php

$subtree = 'root';
$prevDepth = -1;

@endphp

<div x-data="{
    subtrees: {root : true}
}">
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
            @php
                if ($team->depth < $prevDepth) {
                    for ($i = 0; $i < $prevDepth - $team->depth; $i++) {
                        echo '</div>';
                    }
                }

                if ($team->depth > $prevDepth) {

                    echo '<div 
                        x-show="subtrees[\''.$subtree.'\'] ?? true" 
                        x-collapse 
                        data-subtree 
                        x-ref="subtree'.$subtree.'"
                        class="divide-y divide-gray-200"
                    >';
                }

                $subtree = $team->path;
                $prevDepth = $team->depth;
            @endphp

            <div class="grid grid-cols-12 py-2">
                <div class="col-span-6" >
                    <div class="flex items-center" style="padding-left: {{ $team->depth * 1.8 }}rem">
                        @if ($team->isCluster())
                            <button
                                class="flex items-center"
                                x-on:click="function() {
                                    subtrees['{{$team->path}}'] = ! (subtrees['{{$team->path}}'] ?? true);
                                }"
                            >
                                <x-heroicon-s-chevron-right 
                                    class="w-4 h-4 text-gray-500 mr-3" 
                                    x-bind:class="{ 'rotate-90': subtrees['{{$team->path}}'] ?? true }"
                                />
                            </button>
                        @endif

                        <div>
                            <a href="{{ route('teams.show', $team) }}" class="{{ $team->isCluster() ? 'font-medium' : '' }} hover:underline">
                                {{ $team->name }}
                            </a>

                            <span class="text-gray-400">
                                ( <x-stats.grade :grade="$team->grade" />) 
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
