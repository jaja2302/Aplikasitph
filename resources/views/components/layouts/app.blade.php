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
    @filamentStyles
    @livewireStyles
    @stack('styles')

    <!-- Scripts -->
    @livewireScripts
    @filamentScripts
    @stack('scripts')

    @livewire('notifications')

</head>

<body class="h-full font-sans antialiased">
    <!-- Page Loader -->
    <x-loader text="Mohon Tunggu..." size="w-20 h-20" />


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
        @auth
        <div class="p-4">
            <div class="bg-white p-4 rounded-lg shadow-lg border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div>
                            <p class="text-gray-600">Selamat datang,</p>
                            <p class="font-semibold text-gray-800">{{ Auth::user()->nama_lengkap }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endauth
        {{ $slot }}
    </div>

    <script type="module">
        // Tampilkan loader saat halaman mulai dimuat
        document.addEventListener('DOMContentLoaded', () => {
            showLoader();
            // Sembunyikan loader setelah semua konten dimuat
            window.addEventListener('load', () => {
                hideLoader();
            });
        });
    </script>
</body>

</html>