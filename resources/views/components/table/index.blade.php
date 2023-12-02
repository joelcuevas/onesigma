@props(['headers'])

<table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-gray-300']) }}>
  @if (isset($headers))
    <thead>
      <tr>
        @foreach ($headers as $h)
          <th class="py-3.5 text-left text-sm font-semibold text-gray-900">{{ __($h) }}</th>
        @endforeach
      </tr>
    </thead>
  @endif
  <tbody class="divide-y divide-gray-200 bg-white">
    {{ $body }}
  </tbody>
</table>