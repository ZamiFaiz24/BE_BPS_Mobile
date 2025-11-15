<x-guest-layout>
    <!-- Background putih bersih -->
    <div class="min-h-screen flex items-center justify-center p-4 bg-white">
        <div class="w-full max-w-5xl grid lg:grid-cols-5 bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
            
            <!-- Left: Illustration dengan 3 warna BPS sesuai logo (Biru, Hijau, Oranye) -->
            <div class="lg:col-span-2 bg-gradient-to-br from-[#0093DD] via-[#00A651] to-[#FF8C00] p-8 flex flex-col items-center justify-center text-white relative overflow-hidden">
                <!-- Decorative circles dengan warna BPS -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-[#FF8C00]/20 rounded-full -translate-y-32 translate-x-32"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-[#00A651]/20 rounded-full translate-y-32 -translate-x-32"></div>
                <div class="absolute top-1/2 left-1/2 w-48 h-48 bg-white/10 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
                
                <div class="space-y-6 text-center relative z-10">
                    <!-- Logo dalam kotak putih -->
                    <div class="w-32 h-32 mx-auto bg-white rounded-2xl flex items-center justify-center shadow-xl">
                        <img src="{{ asset('images/logo-bps.png') }}" alt="BPS" class="w-20 object-contain">
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold drop-shadow-lg">BPS Mobile</h2>
                        <p class="text-white/90 mt-2 text-lg drop-shadow">Sistem Informasi Statistik</p>
                    </div>
                    
                   <div class="space-y-2 pt-4">
                    <div class="bg-white/15 backdrop-blur-sm rounded-lg p-3 text-sm border border-white/20 shadow-lg">
                        🏛️ Sistem Resmi Internal BPS
                    </div>
                    <div class="bg-white/15 backdrop-blur-sm rounded-lg p-3 text-sm border border-white/20 shadow-lg">
                        🗃️ Manajemen Data Terpusat
                    </div>
                    <div class="bg-white/15 backdrop-blur-sm rounded-lg p-3 text-sm border border-white/20 shadow-lg">
                        🔒 Akses Aman & Terbatas
                    </div>
                </div>
                </div>
            </div>

            <!-- Right: Form (3 cols) -->
            <div class="lg:col-span-3 p-8 lg:p-12">
                <div class="space-y-6">
                    <!-- Header -->
                    <div class="text-center">
                        <h2 class="text-3xl font-bold text-gray-800">Selamat Datang</h2>
                        <p class="text-gray-600 mt-2">Sistem Internal BPS Kabupaten Kebumen</p>
                    </div>

                    <!-- Demo Credentials -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 bg-blue-50 rounded-lg border border-blue-100">
                            <p class="text-xs text-gray-500 mb-1">Super Admin</p>
                            <p class="text-xs font-mono text-gray-700">admin@bpsapp.com</p>
                            <p class="text-xs font-mono text-gray-700">123456789</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg border border-green-100">
                            <p class="text-xs text-gray-500 mb-1">Operator</p>
                            <p class="text-xs font-mono text-gray-700">operator@bpsapp.com</p>
                            <p class="text-xs font-mono text-gray-700">admin123</p>
                        </div>
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <!-- Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#0093DD] focus:border-transparent transition"
                                   placeholder="nama@email.com">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input id="password" type="password" name="password" required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#0093DD] focus:border-transparent transition"
                                   placeholder="••••••••">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Remember & Forgot -->
                        <div class="flex items-center justify-between text-sm">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="remember" class="w-4 h-4 text-[#0093DD] rounded border-gray-300 focus:ring-[#0093DD]">
                                <span class="ml-2 text-gray-600">Ingat saya</span>
                            </label>
                            @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[#0093DD] hover:text-[#0077B6] font-medium">
                                Lupa password?
                            </a>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-[#0093DD] to-[#00B4D8] text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                            Masuk ke Dashboard
                        </button>
                    </form>

                    <!-- Footer - HAPUS link daftar -->
                    <div class="text-center pt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-500">
                            🔒 Sistem ini hanya untuk pegawai BPS yang berwenang.<br>
                            Hubungi administrator untuk mendapatkan akses.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>