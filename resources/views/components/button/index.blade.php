@props(['label' => '', 'primary' => null, 'shadow' => null, 'icon' => null, 'type' => 'button', 'href' => null])

@php

$classes = 'rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-100';
$primaryClasses = 'rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600';
$shadowClasses = 'rounded-md bg-indigo-50 px-3 py-2 text-sm font-semibold text-indigo-600 shadow-sm hover:bg-indigo-100';

if ($primary === true) {
    $classes = $primaryClasses;
} elseif ($shadow === true) {
    $classes = $shadowClasses;
}

@endphp


@if ($href)
<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
  @if ($icon) 
    <x-icon name="heroicon-o-{{ $icon }}" class="inline-block h-5 w-5 align-middle mr-0.5" />
  @endif
  <span class="align-middle">{{ __($label) }}</span>
</a>
@else
<button type="{{ $type }}" {{ $attributes->merge(['type' => 'button', 'class' => $classes]) }}>
  @if ($icon) 
    <x-icon name="heroicon-o-{{ $icon }}" class="inline-block h-5 w-5 align-middle mr-0.5" />
  @endif
  <span class="align-middle">{{ __($label) }}</span>
</button>
@endif