<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title inertia>
        WebFlex
    </title>

    @viteReactRefresh
    @vite('resources/js/inertia.tsx')

    @inertiaHead

</head>

<body>

    @inertia

</body>

</html>