<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-cyan-50 p-4">
        <div class="flex w-full max-w-6xl rounded-2xl shadow-2xl overflow-hidden bg-white">
            <!-- Bagian Kiri - Hero Section -->
            <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-[#0093DD] via-[#00B4D8] to-[#0077B6] relative">
                <div class="absolute inset-0 bg-pattern opacity-10"></div>
                <div class="relative z-10 flex flex-col items-center justify-center w-full px-12 py-16 text-white">
                    <!-- Logo BPS -->
                    <div class="mb-6">
                        {{-- <img src="{{ asset('images/logo-bps.png') }}" alt="Logo BPS" class="h-24 w-auto drop-shadow-2xl"> --}}
                        <div class="h-24 w-24 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                            <span class="text-5xl font-bold">BPS</span>
                        </div>
                    </div>
                    
                    <!-- Tagline -->
                    <h1 class="text-3xl font-bold text-center mb-3">BPS Mobile</h1>
                    <p class="text-lg text-center text-blue-100 mb-8 max-w-sm">
                        Sistem Informasi Badan Pusat Statistik
                    </p>
                    
                    <!-- Features -->
                    <div class="space-y-3 w-full max-w-sm">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-base">Dashboard Interaktif</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <span class="text-base">Akses Cepat & Aman</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <span class="text-base">Laporan Real-time</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian Kanan - Form Register -->
            <div class="w-full lg:w-1/2 p-8 lg:p-12">
                <!-- Header -->
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Daftar Akun</h2>
                    <p class="text-gray-600">Buat akun baru Anda</p>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Name')" class="text-gray-700 font-medium mb-2" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <x-text-input id="name" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0093DD] focus:border-transparent transition duration-200" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama lengkap" />
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium mb-2" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <x-text-input id="email" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0093DD] focus:border-transparent transition duration-200" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@email.com" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium mb-2" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <x-text-input id="password" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0093DD] focus:border-transparent transition duration-200" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-gray-700 font-medium mb-2" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <x-text-input id="password_confirmation" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0093DD] focus:border-transparent transition duration-200" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Konfirmasi password" />
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-[#0093DD] to-[#00B4D8] hover:from-[#0077B6] hover:to-[#0096C7] text-white font-semibold rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0093DD] transition duration-200 transform hover:scale-[1.02]">
                        Daftar Sekarang
                    </button>
                </form>

                <!-- Footer -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="font-semibold text-[#0093DD] hover:text-[#0077B6] transition duration-200">
                            Masuk di sini
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
