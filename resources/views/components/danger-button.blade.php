<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'rounded-md bg-red-600 px-4 py-2 font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600']) }}
>
    {{ $slot }}
</button>
