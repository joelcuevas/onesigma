<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Posiciones') }}
            </h2>
            <x-link-button href="{{ route('positions') }}">
                {{ __('Nueva Posición') }}
            </x-link-button>
        </div>
    </x-slot>

    <div class="x-card">
        <table class="min-w-full divide-y divide-gray-300">
            <thead>
                <tr>
                    <th class="py-2 text-left text-sm font-medium uppercase tracking-wide text-gray-500">Título</th>
                    <th class="py-2 text-left text-sm font-medium uppercase tracking-wide text-gray-500">Clave</th>
                    <th class="py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @foreach ($positions->sortBy('name') as $position)
                    <tr>
                        <td class="whitespace-nowrap py-2 font-medium text-gray-900">
                            <a class="hover:underline" href="{{ route('positions.show', $position) }}">
                                {{ $position->title }}
                            </a>
                        </td>
                        <td class="whitespace-nowrap py-2 text-gray-500">
                            {{ $position->group }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
