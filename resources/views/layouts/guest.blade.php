<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'My Money Tracker') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col justify-center py-6 sm:py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-indigo-50 via-white to-cyan-50">
        
        <!-- Main content container -->
        <div class="w-full max-w-sm sm:max-w-md mx-auto">
            <!-- Logo/Header -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-indigo-600 rounded-xl flex items-center justify-center">
                        <span class="text-xl sm:text-2xl">💰</span>
                    </div>
                </div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Money Tracker</h1>
                <p class="mt-2 text-xs sm:text-sm text-gray-600">Kelola keuangan Anda dengan mudah</p>
            </div>

            <!-- Form card -->
            <div class="bg-white shadow-lg rounded-lg sm:rounded-xl overflow-hidden border border-gray-200">
                <div class="px-6 py-8 sm:px-8 sm:py-10">
                    {{ $slot }}
                </div>
            </div>

            <!-- Footer links -->
            <div class="mt-8 text-center space-y-4">
                <div class="flex flex-col sm:flex-row sm:justify-center sm:space-x-6 space-y-2 sm:space-y-0 text-sm">
                    <a href="#" class="text-gray-600 hover:text-indigo-600 transition-colors">Tentang</a>
                    <a href="#" class="text-gray-600 hover:text-indigo-600 transition-colors">Bantuan</a>
                    <a href="#" class="text-gray-600 hover:text-indigo-600 transition-colors">Privasi</a>
                </div>
                
                <div class="text-xs text-gray-500">
                    © {{ date('Y') }} Money Tracker. Dibuat dengan ❤️ menggunakan Laravel & Livewire.
                </div>
            </div>
        </div>

        <!-- Background decoration -->
        <div class="fixed inset-0 -z-10 overflow-hidden">
            <div class="absolute -top-1/2 -right-1/2 w-96 h-96 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full opacity-20 animate-pulse"></div>
            <div class="absolute -bottom-1/2 -left-1/2 w-96 h-96 bg-gradient-to-tr from-cyan-100 to-blue-100 rounded-full opacity-20 animate-pulse"></div>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>