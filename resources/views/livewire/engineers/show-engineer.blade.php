<div>
    <x-slot name="header">
        <div class="sm:flex sm:items-center sm:justify-between">
            <h2 class="text-xl leading-tight text-gray-800">
                <span class="font-semibold">{{ $engineer->name }}</span>
                - {{ $engineer->title }} - {{ $engineer->grade }}
            </h2>
            <div class="mt-3 space-x-4 sm:ml-4 sm:mt-0">
                <x-primary-link-button href="{{ route('engineers.score', $engineer) }}">
                    {{ __('Evaluar') }}
                </x-primary-link-button>
                <x-link-button href="{{ route('engineers.edit', $engineer) }}">
                    {{ __('Editar') }}
                </x-link-button>
            </div>
        </div>
    </x-slot>

    <div class="flex flex-col space-y-6">
        <livewire:stats.metrics-summary :metricable="$engineer" />
        <livewire:stats.skills-summary :skillable="$engineer" />
    </div>
</div>
