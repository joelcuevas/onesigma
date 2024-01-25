@props(['grade'])

@php
    $class = match ($grade) {
        'A+', 'A', 'B' => 'text-green-800',
        'C', 'D' => 'text-orange-600',
        'E', 'F' => 'text-red-600',
        default => '--',
    };
@endphp

<div class="{{ $class }} inline-block">
    {{ $grade }}
</div>
