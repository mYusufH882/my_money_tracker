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
<body class="font-sans antialiased bg-gray-50">
    <!-- Mobile menu overlay -->
    <div x-data="{ sidebarOpen: false }" class="min-h-screen">
        <!-- Off-canvas menu for mobile -->
        <div x-show="sidebarOpen" class="relative z-50 lg:hidden" role="dialog" aria-modal="true">
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900/80"></div>

            <div class="fixed inset-0 flex">
                <div x-show="sidebarOpen"
                     x-transition:enter="transition ease-in-out duration-300 transform"
                     x-transition:enter-start="-translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in-out duration-300 transform"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="-translate-x-full"
                     @click.outside="sidebarOpen = false"
                     class="relative mr-16 flex w-full max-w-xs flex-1">
                    
                    <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                        <button type="button" class="-m-2.5 p-2.5" @click="sidebarOpen = false">
                            <span class="sr-only">Close sidebar</span>
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Mobile Sidebar content -->
                    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-4">
                        <div class="flex h-16 shrink-0 items-center">
                            <h1 class="text-lg sm:text-xl font-bold text-gray-900">💰 Money Tracker</h1>
                        </div>
                        @include('components.sidebar-nav')
                        
                        <!-- Mobile user section -->
                        <div class="mt-auto">
                            <div class="flex items-center gap-x-4 py-3 text-sm font-semibold leading-6 text-gray-900">
                                <div class="h-8 w-8 rounded-full bg-gray-500 flex items-center justify-center">
                                    <span class="text-sm font-medium text-white">
                                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                    </span>
                                </div>
                                <span class="sr-only">Your profile</span>
                                <span aria-hidden="true">{{ auth()->user()->name ?? 'User' }}</span>
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-x-3 rounded-md p-2 text-sm leading-6 text-gray-700 hover:text-indigo-600 hover:bg-gray-50">
                                    <svg class="h-6 w-6 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Static sidebar for desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
            <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-6">
                <div class="flex h-16 shrink-0 items-center">
                    <h1 class="text-xl font-bold text-gray-900">💰 Money Tracker</h1>
                </div>
                @include('components.sidebar-nav')
            </div>
        </div>

        <!-- Main content -->
        <div class="lg:pl-72">
            <!-- Top navigation -->
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <!-- Mobile menu button -->
                <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="sidebarOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <!-- Separator -->
                <div class="h-6 w-px bg-gray-200 lg:hidden"></div>

                <!-- Header content -->
                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <!-- Page title -->
                    <div class="flex flex-1 items-center">
                        <h2 class="text-sm sm:text-lg font-semibold text-gray-900 truncate">{{ $title ?? 'Dashboard' }}</h2>
                    </div>
                    
                    <!-- Desktop profile -->
                    <div class="hidden lg:flex lg:items-center lg:gap-x-4 lg:gap-x-6">
                        <!-- Profile dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button type="button" 
                                    class="flex items-center gap-x-2 rounded-full bg-gray-50 p-1.5 text-sm leading-6 text-gray-900 hover:bg-gray-100"
                                    @click="open = !open">
                                <div class="h-8 w-8 rounded-full bg-gray-500 flex items-center justify-center">
                                    <span class="text-sm font-medium text-white">
                                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                    </span>
                                </div>
                                <span class="flex items-center">
                                    <span class="ml-2 text-sm font-semibold">{{ auth()->user()->name ?? 'User' }}</span>
                                    <svg class="ml-2 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </button>
                            
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 @click.outside="open = false"
                                 class="absolute right-0 z-10 mt-2.5 w-48 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5">
                                
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'User' }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email ?? 'user@example.com' }}</p>
                                </div>
                                
                                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile user indicator -->
                    <div class="flex lg:hidden items-center">
                        <div class="h-8 w-8 rounded-full bg-gray-500 flex items-center justify-center">
                            <span class="text-xs font-medium text-white">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <main class="py-4 sm:py-6 lg:py-8">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>