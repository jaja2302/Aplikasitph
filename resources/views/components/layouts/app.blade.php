<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GIS Application') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="icon" href="{{ asset('images/CBIpreview.png') }}">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <!-- Scripts -->
    @stack('scripts')

</head>

<body class="h-full font-sans antialiased">
    <!-- Page Loader -->
    <div id="loader" class="fixed inset-0 z-[9999] flex items-center justify-center bg-white bg-opacity-75">
        <div class="flex flex-col items-center">
            <div class="animate-spin rounded-full h-20 w-20 border-b-2 border-green-600"></div>
            <span class="mt-4 text-gray-600">Mohon Tunggu...</span>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
    <div x-data="{ show: true }"
        x-show="show"
        x-transition
        x-init="setTimeout(() => show = false, 3000)"
        class="fixed top-4 right-4 z-50">
        <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('message') }}
        </div>
    </div>
    @endif

    <!-- Navigation Bar (Only show when authenticated) -->
    @auth
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <img class="h-8 w-auto" src="{{ asset('images/CBIpreview.png') }}" alt="Logo">
                    </div>
                    <!-- Navigation Links -->
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('dashboard') }}"
                            class="{{ request()->routeIs('dashboard') ? 'border-green-500' : 'border-transparent' }} text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Dashboard
                        </a>
                    </div>


                    @if(check_previlege_cmp())
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('maps-management') }}"
                            class="{{ request()->routeIs('maps-management') ? 'border-green-500' : 'border-transparent' }} text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Maps Management
                        </a>
                    </div>
                    @endif
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('tph-management') }}"
                            class="{{ request()->routeIs('tph-management') ? 'border-green-500' : 'border-transparent' }} text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            TPH Management
                        </a>
                    </div>

                </div>

                <!-- User Menu -->
                <div class="flex items-center">
                    <div class="flex-shrink-0 relative ml-5">
                        <div class="flex items-center">
                            <div class="mr-3 text-right">
                                <p class="text-sm font-medium text-gray-700">{{ Auth::user()->nama_lengkap }}</p>
                                <p class="text-xs text-gray-500">{{ Auth::user()->Jabatan->nama ?? '-' }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="mr-2 -ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    <!-- Main Content -->
    @auth
    <div class="min-h-screen bg-gray-50">
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            {{$slot}}
        </main>
    </div>
    @else
    {{$slot}}
    @endauth

    <script>
        // Global loader functions
        window.showLoader = function() {
            const loader = document.getElementById('loader');
            if (loader) {
                loader.style.display = 'flex';
            }
        }

        window.hideLoader = function() {
            const loader = document.getElementById('loader');
            if (loader) {
                loader.style.display = 'none';
            }
        }

        // Hide loader when page is fully loaded
        window.addEventListener('load', function() {
            hideLoader();
        });
    </script>
</body>

</html>