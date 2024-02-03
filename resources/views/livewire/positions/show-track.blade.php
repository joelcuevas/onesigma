<div>
    <x-slot name="header">
        <div class="sm:flex sm:items-center sm:justify-between">
            <h2 class="text-xl leading-tight text-gray-800">
                <span class="font-semibold text-gray-500">{{ __('Track:') }}</span>
                <span class="font-semibold">{{ $position->title }}</span>
            </h2>
            <div class="mt-3 space-x-4 sm:ml-4 sm:mt-0">
                <x-link-button href="{{ route('tracks.config', $position) }}">
                    <x-heroicon-o-cog class="mr-2 h-5 w-5" />
                    {{ __('Configurar Track') }}
                </x-link-button>
            </div>
        </div>
    </x-slot>

    <div class="flex flex-col space-y-6">
        <div class="x-card">
            <div class="mb-6 sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-base font-medium leading-tight text-gray-900">{{ __('Posiciones') }}</h1>
                    <p class="mt-1 leading-tight text-gray-500">{{ __('Escala de posiciones dentro del track de carrera.') }}</p>
                </div>
                <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                    <livewire:positions.create-position :$position />
                </div>
            </div>
            <table class="min-w-full divide-y divide-gray-300">
                <thead>
                    <tr>
                        <th class="py-2 text-left text-sm font-medium uppercase tracking-wide text-gray-500">{{ __('Posiciones') }}</th>
                        <th class="py-2 text-left text-sm font-medium uppercase tracking-wide text-gray-500">{{ __('Clave') }}</th>
                        <th class="py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach ($positions->sortBy('name') as $position)
                        <tr>
                            <td class="whitespace-nowrap py-2 font-medium text-gray-900">
                                <a class="hover:underline" href="#">
                                    {{ $position->title }}
                                </a>
                            </td>
                            <td class="whitespace-nowrap py-2 text-gray-500">
                                {{ $position->code }}
                            </td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
