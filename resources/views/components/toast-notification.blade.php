@props(['on', 'title', 'content'])

<div x-data="{ show: false, timeout: null }" x-init="
        @this.on('{{ $on }}', () => { 
            clearTimeout(timeout); 
            show = true; 
            timeout = setTimeout(() => { show = false }, 2000); 
        })">
    <div class="pointer-events-none fixed inset-0 flex items-end px-4 py-6 sm:items-start sm:p-6">
        <div class="flex w-full flex-col items-center space-y-4 sm:items-end">
            <div x-show="show" x-transition style="display: none" class="pointer-events-auto w-full max-w-md overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                <div class="p-4">
                    <div class="flex items-start items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="font-medium text-gray-900">{{ __($title ?? 'Guardado') }}</p>
                        </div>
                        <div class="ml-4 flex flex-shrink-0">
                            <button x-on:click="show = false" type="button" class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="pl-9">
                        @if (isset($content))
                            <p class="mt-1 text-gray-500">{{ __($content) }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
