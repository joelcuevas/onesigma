<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'rounded-md bg-indigo-600 px-4 py-2 font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600']) }}
>
    {{ $slot }}
</button>
