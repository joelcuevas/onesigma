<div>
    <x-slot name="header">
        <div class="sm:flex sm:items-center sm:justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ __('Equipo') }}: {{ $team->name }}</h2>
            <div class="mt-3 space-x-4 sm:ml-4 sm:mt-0">
                <x-link-button href="{{ route('teams.edit', $team) }}">
                    {{ __('Editar') }}
                </x-link-button>
            </div>
        </div>
    </x-slot>

    <div class="flex flex-col space-y-6">
        <livewire:stats.metrics-summary :metricable="$team" />
        <livewire:stats.skills-summary :skillable="$team" />

        @if (! $team->isCluster())
            <div class="x-card">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-base font-medium leading-tight text-gray-900">{{ __('Ingenieros') }}</h1>
                        <p class="mt-1 leading-tight text-gray-500">{{ __('Miembros del equipo que suman a las métricas de performance.') }}</p>
                    </div>
                    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                        <livewire:teams.edit-members :$team relationship="engineers" name="edit-engineers" />
                    </div>
                </div>

                <div class="mt-6 flow-root">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            @if ($team->engineers->count())
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead>
                                        <tr>
                                            <th class="py-2 text-left text-sm font-medium uppercase tracking-wide text-gray-500 sm:pl-0">Nombre</th>
                                            <th class="py-2 text-left text-sm font-medium uppercase tracking-wide text-gray-500">Posición</th>
                                            <th class="py-2 text-left text-sm font-medium uppercase tracking-wide text-gray-500">Stats</th>
                                            <th class="py-2 text-left text-sm font-medium uppercase tracking-wide text-gray-500">Rol</th>
                                            <th class="relative py-2"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @foreach ($team->engineers as $engineer)
                                            <tr>
                                                <td class="whitespace-nowrap py-2 font-medium text-gray-900 sm:pl-0">
                                                    <a class="hover:underline" href="{{ route('engineers.show', $engineer) }}">
                                                        {{ $engineer->name }}
                                                    </a>
                                                </td>
                                                <td class="whitespace-nowrap py-2 text-gray-500">
                                                    {{ $engineer->title }}
                                                </td>
                                                <td class="whitespace-nowrap py-2 text-gray-500">Nivel: {{ $engineer->grade }}</td>
                                                <td class="whitespace-nowrap py-2 text-gray-500">
                                                    {{ $engineer->team->role->name }}
                                                </td>
                                                <td class="relative whitespace-nowrap py-2 text-right font-medium sm:pr-0">
                                                    <a href="{{ route('engineers.show', $engineer) }}" class="text-indigo-600 hover:text-indigo-900">Perfil</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                    <div class="text-center border p-6 rounded-md">
                                    <x-heroicon-o-users class="mx-auto w-10 h-10 text-gray-400 mb-3" />
                                    <x-primary-button x-on:click.prevent="$dispatch('open-modal', { name: 'edit-engineers' })">
                                      <x-heroicon-o-plus class="w-5 h-5 mr-1" />
                                      {{ __('Agregar Ingenieros') }}
                                    </x-primary-button>
                                    </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
            <div class="x-card">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-base font-medium leading-tight text-gray-900">{{ __('Staff de Soporte') }}</h1>
                        <p class="mt-1 leading-tight text-gray-500">{{ __('Usuarios de soporte, que pueden visualizar o gestionar al equipo.') }}</p>
                    </div>
                    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                        <livewire:teams.edit-members :$team relationship="users" name="edit-users" />
                    </div>
                </div>

                <div class="mt-6 flow-root">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            @if ($team->users->count())
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead>
                                    <tr>
                                        <th class="py-2 text-left text-sm font-medium uppercase tracking-wide text-gray-500 sm:pl-0">Nombre</th>
                                        <th class="py-2 text-left text-sm font-medium uppercase tracking-wide text-gray-500">Rol</th>
                                        <th class="relative py-2"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach ($team->users as $user)
                                        <tr>
                                            <td class="whitespace-nowrap py-2 font-medium text-gray-900 sm:pl-0">
                                                <a class="hover:underline" href="#">
                                                    {{ $user->name }}
                                                </a>
                                            </td>
                                            <td class="whitespace-nowrap py-2 text-gray-500">
                                                {{ $user->team->role->name }}
                                            </td>
                                            <td class="relative whitespace-nowrap py-2 text-right font-medium sm:pr-0">
                                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Perfil</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                                <div class="text-center border p-6 rounded-md">
                                    <x-heroicon-o-users class="mx-auto w-10 h-10 text-gray-400 mb-3" />
                                    <x-primary-button x-on:click.prevent="$dispatch('open-modal', { name: 'edit-users' })">
                                      <x-heroicon-o-plus class="w-5 h-5 mr-1" />
                                      {{ __('Agregar Usuarios') }}
                                    </x-primary-button>
                                    </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
