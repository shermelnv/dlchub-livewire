<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'PLCHub') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Adjust if using other build tools --}}
    @livewireStyles
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100 min-h-screen flex items-center justify-center">
    {{ $slot }}

    @livewireScripts
</body>
</html>
