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

    <!-- Logout Button - Only show when authenticated -->
    @auth('pengguna')
    <div class="fixed top-4 right-4 z-50">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow-lg transition duration-150 ease-in-out flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H3zm7 4a1 1 0 1 0-2 0v4a1 1 0 1 0 2 0V7zm2.707 1.293a1 1 0 0 0-1.414 1.414L12.586 11H7a1 1 0 1 0 0 2h5.586l-1.293 1.293a1 1 0 1 0 1.414 1.414l3-3a1 1 0 0 0 0-1.414l-3-3z" clip-rule="evenodd" />
                </svg>
                Logout
            </button>
        </form>
    </div>
    @endauth

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