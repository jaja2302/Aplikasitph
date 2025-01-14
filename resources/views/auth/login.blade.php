<x-layouts.app>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-green-600 to-green-800">
        <div class="flex w-full max-w-4xl mx-4">
            <!-- Left side - Company Profile -->
            <div class="hidden md:flex md:w-1/2 bg-white rounded-l-lg items-center justify-center p-12">
                <div class="text-center">
                    <img src="{{ asset('images/CBIpreview.png') }}" alt="Company Logo" class="w-full h-full mx-auto mb-6">
                    <h2 class="text-2xl font-bold text-green-700 mb-1">Citra Borneo Indah</h2>
                    <span class="text-xs text-green-600 block mb-4">Karya Nyata untuk Negeri</span>
                    <p class="text-gray-600 text-sm mb-4">Portal Informasi Titik TPH</p>
                    <div class="w-16 h-1 bg-green-500 mx-auto"></div>
                </div>
            </div>

            <!-- Right side - Login Form -->
            <div class="w-full md:w-1/2 bg-white md:rounded-l-none rounded-lg shadow-2xl p-8">
                <div class="md:hidden text-center mb-8">
                    <img src="{{ asset('images/CBIpreview.png') }}" alt="Company Logo" class="w-24 h-24 mx-auto mb-4">
                    <h2 class="text-2xl font-bold text-green-700">SSMS</h2>
                </div>

                <h1 class="text-2xl font-bold mb-8 text-center text-gray-800">Login ke Akun Anda</h1>

                @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ $errors->first() }}
                </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="email">
                            Email
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </span>
                            <input class="pl-10 w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:border-green-500"
                                id="email"
                                name="email"
                                type="text"
                                required
                                value="{{ old('email') }}"
                                placeholder="Masukkan email">
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="password">
                            Password
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <input class="pl-10 w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:border-green-500"
                                id="password"
                                name="password"
                                type="password"
                                required
                                placeholder="Masukkan password">
                        </div>
                    </div>

                    <div>
                        <button class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50"
                            type="submit">
                            Masuk
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        © 2025 SSMS All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>