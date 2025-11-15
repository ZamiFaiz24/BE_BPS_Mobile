<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Informasi Profil</h3>
        <p class="text-sm text-gray-600 mt-1">Perbarui informasi profil dan email akun Anda</p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="p-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                <input id="name" name="name" type="text" 
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0093DD] focus:border-transparent" 
                       value="{{ old('name', $user->name) }}" required autofocus>
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input id="email" name="email" type="email" 
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0093DD] focus:border-transparent" 
                       value="{{ old('email', $user->email) }}" required>
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-3 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded-r">
                        <p class="text-sm text-yellow-700">
                            Email Anda belum diverifikasi.
                            <button form="send-verification" class="underline hover:no-underline font-medium">
                                Kirim ulang email verifikasi
                            </button>
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-3 mt-6 pt-4 border-t border-gray-200">
            <button type="submit" class="px-6 py-2.5 bg-[#0093DD] text-white font-medium rounded-lg hover:bg-[#0080C0] transition shadow-sm">
                Simpan
            </button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-green-600 font-medium">
                    ✓ Tersimpan
                </p>
            @endif
        </div>
    </form>
</div>