@props(['pageTitle'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $pageTitle.' - '.config('app.name') ?? config('app.name') }}</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans:400,500,600,700|Noto+Serif:400,500,600,700">
  @livewireStyles
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased">
  <div class="min-h-full">
    <nav x-data="{ open: false }" class="bg-gray-800">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="text-white font-bold text-2xl">
                {{ config('app.name') }}
              </div>
            </div>
            <div class="hidden md:block">
              <div class="ml-6 flex items-baseline space-x-4">
                <a href="{{ route('teams') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">{{ __('Equipos') }}</a>
                <a href="{{ route('engineers') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">{{ __('Ingenieros') }}</a>
              </div>
            </div>
          </div>

          <div class="hidden md:block">
            <div class="ml-4 flex items-center md:ml-6">
              <!-- Profile dropdown -->
              @auth
                <div x-data="{ open: false }" @keydown.escape.stop="open = false" @click.away="open = false" class="relative ml-3">
                  <div>
                    <button type="button" class="relative flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" id="user-menu-button" x-ref="button" @click="open = true">
                      <span class="absolute -inset-1.5"></span>
                      <img class="h-8 w-8 rounded-full" src="{{ auth()->user()->avatar }}" alt="">
                    </button>
                  </div>

                  <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" x-ref="menu-items" tabindex="-1" @keydown.tab="open = false" @keydown.enter.prevent="open = false" @keyup.space.prevent="open = false" style="display: none;">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700" x-state:on="Active" x-state:off="Not Active" role="menuitem" tabindex="-1" id="user-menu-item-0" @mouseenter="onMouseEnter($event)" @mousemove="onMouseMove($event, 0)" @mouseleave="onMouseLeave($event)" @click="open = false; focusButton()">Your Profile</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-1" @mouseenter="onMouseEnter($event)" @mousemove="onMouseMove($event, 1)" @mouseleave="onMouseLeave($event)" @click="open = false; focusButton()">Settings</a>
                    <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-2" @mouseenter="onMouseEnter($event)" @mousemove="onMouseMove($event, 2)" @mouseleave="onMouseLeave($event)" @click="open = false; focusButton()">Sign out</a>
                  </div>
                </div>
              @endauth
            </div>
          </div>

          <!-- Mobile menu button -->
          <div class="-mr-2 flex md:hidden">
            <button type="button" class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" aria-controls="mobile-menu" @click="open = !open" aria-expanded="false" x-bind:aria-expanded="open.toString()">
              <span class="absolute -inset-0.5"></span>
              <svg x-state:on="Menu open" x-state:off="Menu closed" class="h-6 w-6 block" :class="{ 'hidden': open, 'block': !(open) }" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"></path>
              </svg>
              <svg x-state:on="Menu open" x-state:off="Menu closed" class="h-6 w-6 hidden" :class="{ 'block': open, 'hidden': !(open) }" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile menu -->
      <div class="md:hidden" id="mobile-menu" x-show="open" style="display: none;">
        <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
          <a href="{{ route('teams') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium">{{ __('Equipos') }}</a>
          <a href="{{ route('engineers') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium">{{ __('Ingenieros') }}</a>
        </div>
        @auth
          <div class="border-t border-gray-700 pb-3 pt-4">
            <div class="flex items-center px-5">
              <div class="flex-shrink-0">
                <img class="h-10 w-10 rounded-full" src="{{ auth()->user()->avatar }}" alt="">
              </div>
              <div class="ml-3">
                <div class="text-base font-medium leading-none text-white">{{ auth()->user()->name }}</div>
                <div class="text-sm font-medium leading-none text-gray-400">{{ auth()->user()->nickname }}</div>
              </div>
            </div>
            <div class="mt-3 space-y-1 px-2">
              <a href="{{ route('logout') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Sign out</a>
            </div>
          </div>
        @endauth
      </div>
    </nav>

    <header class="bg-white shadow">
      <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
          <h1 class="text-3xl leading-10 align-middle  font-bold tracking-tight text-gray-900">
              {{ isset($header) ? $header : $pageTitle }}
          </h1>
        </div>
        @if (isset($actions) && $actions)
          <div class="mt-4 flex md:ml-4 md:mt-0 gap-x-3 md:flex-row-reverse">
            {{ $actions }}
          </div>
        @endif
      </div>
    </header>

    <main class="text-gray-600">
      <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        {{ $slot }}
      </div>
    </main>
  </div>
  @livewireScripts
</body>
</html>