<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center p-4 bg-white">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8 text-center">
                <div class="w-20 h-20 mx-auto mb-6 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                
                <h2 class="text-2xl font-bold text-gray-800 mb-3">Pendaftaran Ditutup</h2>
                <p class="text-gray-600 mb-6">
                    Sistem ini hanya untuk pegawai BPS Kabupaten Kebumen yang berwenang. 
                    Pendaftaran akun baru hanya dapat dilakukan oleh administrator.
                </p>
                
                <div class="space-y-3">
                    <a href="{{ route('login') }}" 
                       class="block w-full py-3 bg-[#0093DD] text-white font-semibold rounded-lg hover:bg-[#0080C0] transition">
                        Kembali ke Login
                    </a>
                    
                    <p class="text-sm text-gray-500">
                        Butuh akses? Hubungi administrator sistem.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
