<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'VitalFlow')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <main class="flex min-h-screen items-start overflow-x-hidden overflow-y-auto px-4 py-4 sm:px-6 lg:h-screen lg:items-center lg:overflow-hidden lg:px-8">
        @yield('content')
    </main>
</body>
</html>
