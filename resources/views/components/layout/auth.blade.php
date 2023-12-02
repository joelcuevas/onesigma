<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?? config('app.name') }}</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans:400,500,600,700|Noto+Serif:400,500,600,700">
  @livewireStyles
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900">
  {{ $slot }}
  @livewireScripts
</body>
</html>