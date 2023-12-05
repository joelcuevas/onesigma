@props(['id', 'label', 'value', 'helpInline' => null, 'help' => null])

<div {{ $attributes->whereDoesntStartWith('wire:model')->merge([]) }}>
  <div class="relative flex items-start">
    <div class="flex h-4 items-center">
      <input 
        {{ $attributes->whereStartsWith('wire:model') }}
        id="{{ $id }}" 
        value="{{ $value }}" 
        type="radio" 
        class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600">
    </div>
    <div class="ml-3 text-sm leading-4">
      <label for="{{ $id }}" class="font-medium text-gray-900 cursor-pointer">
        {{ __($label) }}
        @if ($helpInline)
          <span class="ml-1 text-gray-500">{{ __($helpInline) }}</span>
        @endif
        @if ($help)
          <div class="mt-1 text-gray-500">{{ __($help) }}</div>
        @endif
      </label>
    </div>
  </div>
</div>