<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GIS Application') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @filamentStyles
    @livewireStyles
    @stack('styles')

    <!-- Scripts -->
    @livewireScripts
    @filamentScripts
    @stack('scripts')

    <style>
        .loader {
            animation: fadeOut 0.5s ease-in-out 1.5s forwards;
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                visibility: visible;
            }

            to {
                opacity: 0;
                visibility: hidden;
            }
        }

        .loader-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body class="h-full font-sans antialiased">
    <!-- Page Loader -->
    <div class="loader fixed inset-0 z-[9999] flex items-center justify-center bg-gray-900">
        <div class="text-center">
            <div class="loader-spin inline-block w-16 h-16 border-4 border-primary-200 border-t-primary-600 rounded-full"></div>
            <h2 class="mt-4 text-xl font-semibold text-white">Loading...</h2>
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

    <!-- Main Content -->
    <div class="min-h-screen bg-gray-50">
        {{ $slot }}
    </div>

    <!-- Loading Indicator -->
    <div wire:loading class="fixed top-0 left-0 right-0">
        <div class="h-1 bg-blue-500 animate-pulse"></div>
    </div>
</body>

</html>