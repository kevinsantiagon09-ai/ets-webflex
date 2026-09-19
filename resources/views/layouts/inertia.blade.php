<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @viteReactRefresh
    <x-inertia::head/>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/inertia.tsx'])
    @endif

    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
</head>

<body>
    
    <div class="w-full">
      <x-inertia::app />
    </div>
</body>
</html>
