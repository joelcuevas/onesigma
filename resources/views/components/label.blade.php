@props(['text'])

<label {{ $attributes->merge(['class' => 'mt-2 block text-sm font-medium leading-6 text-gray-900']) }}>
  {{ __($text) }}
</label>