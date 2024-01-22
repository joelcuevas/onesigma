<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Equipos') }}
        </h2>
    </x-slot>

    <div class="x-card">
        <div class="divide-y divide-gray-200">
            @foreach ($teams as $team)
                <div class="py-2" style="padding-left: {{ $team->depth * 1.5 }}rem">
                    <a href="{{ route('teams.show', $team) }}" class="font-medium hover:underline">
                        {{ $team->name }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
