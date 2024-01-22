@props(['id', 'label'])

<div class="flex items-center space-x-2">
    <input
        type="radio"
        {!! $attributes->merge(['id' => $id, 'class' => 'h-4 w-4 border-gray-300 text-indigo-600 cursor-pointer focus:ring-indigo-600']) !!}
    />
    <label for="{{ $id }}" class="cursor-pointer font-medium leading-6 text-gray-900">{{ $label }}</label>
</div>
