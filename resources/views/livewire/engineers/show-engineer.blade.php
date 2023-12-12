<div x-data x-on:engineer-updated.window="$wire.$refresh">
  <x-slot:header>
    <div class="md:flex md:items-center md:justify-between md:space-x-5">
      <div class="flex items-center space-x-5">
        <div class="flex-shrink-0">
          <div class="relative">
            @if ($engineer->user)
            <img class="h-16 w-16 rounded-full" src="{{ $engineer->user->avatar }}" alt="">
            @else
            <div class="h-16 w-16 rounded-full bg-gray-600 text-white text-center flex items-center justify-center text-2xl">
              {{ $engineer->initials }}
            </div>
            @endif
            <span class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></span>
          </div>
        </div>
        <div>
          <h1 class="text-gray-900">{{ $engineer->name }}</h1>
          <p class="text-sm font-medium text-gray-500 flex">
            <span class="inline-block mr-4 flex items-center">
              <x-heroicon-o-briefcase class="inline-block mr-1 h-4 w-4" />
              {{ __($engineer->career->name) }}
              {{ $engineer->career_level ? '('.$engineer->career_level.')' : '' }}
            </span>
            <span class="inline-block mr-4 flex items-center">
              <x-heroicon-o-light-bulb class="inline-block mr-1 h-4 w-4" />
              {{ __($engineer->domain->name) }}
              {{ $engineer->domain_level ? '('.$engineer->domain_level.')' : '' }}
            </span>
          </p>
        </div>
      </div>
    </div>
  </x-slot>

  <x-slot:actions>
    
  </x-slot>

  <div class="mb-6">
    <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">
      <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
        <dt class="truncate text-sm font-medium text-gray-500">{{ __('Weekly Coding Days') }}</dt>
        <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $engineer->getMetric('wcd') }}</dd>
      </div>
      <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
        <dt class="truncate text-sm font-medium text-gray-500">{{ __('Autonomía') }}</dt>
        <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">--</dd>
      </div>
      <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
        <dt class="truncate text-sm font-medium text-gray-500">{{ __('Madurez') }}</dt>
        <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">--</dd>
      </div>
    </dl>
  </div>

  <div class="grid grid-cols-1 gap-6 lg:grid-flow-col-dense lg:grid-cols-3">
    <div class="space-y-6 lg:col-span-2 lg:col-start-1">
      <x-layout.panel>
        <div style="height: 24rem;">
          <livewire:livewire-radar-chart 
            key="{{ $this->careerChart->reactiveKey() }}"
            :radar-chart-model="$this->careerChart" />
        </div>
        <livewire:engineers.grade-engineer :$engineer />
      </x-layout.panel>
    </div>
    <div class="lg:col-span-1 lg:col-start-3">
      <x-layout.panel>
        <div class="mb-4 flex flex-wrap items-center justify-between sm:flex-nowrap">
          <h3 class="text-base font-semibold leading-6 text-gray-900">Información</h3>
          <a href="#" class="!text-gray-400 hover:!text-gray-600">
            <x-heroicon-o-pencil-square class="h-5 w-5" />
          </a>
        </div>
        <div class="mb-2">
          <dt class="text-sm font-medium leading-6 text-gray-900">Email</dt>
          <dd class="text-sm leading-6 text-gray-700">
            {{ $engineer->email ?? __('No especificado') }}
          </dd>
        </div>
      </x-layout.panel>
    </div>
  </div>
</div>