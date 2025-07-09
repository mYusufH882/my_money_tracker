<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'My Money Tracker') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-indigo-50 via-white to-cyan-50">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <!-- Logo/Header -->
            <div class="flex justify-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">💰 Money Tracker</h1>
            </div>

            {{ $slot }}
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-sm text-gray-500">
            © {{ date('Y') }} My Money Tracker. Built with Laravel & Livewire. By Yuscode
        </div>
    </div>

    @livewireScripts
    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>