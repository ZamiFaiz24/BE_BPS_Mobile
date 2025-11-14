<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Ubah Password</h3>
        <p class="text-sm text-gray-600 mt-1">Pastikan menggunakan password yang kuat</p>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="p-6">
        @csrf
        @method('put')

        <div class="space-y-5 max-w-xl">
            <div>
                <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-2">
                    Password Lama
                </label>
                <input id="update_password_current_password" name="current_password" type="password" 
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0093DD] focus:border-transparent" 
                       placeholder="Masukkan password lama">
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <div>
                <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-2">
                    Password Baru
                </label>
                <input id="update_password_password" name="password" type="password" 
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0093DD] focus:border-transparent" 
                       placeholder="Minimal 8 karakter">
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div>
                <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Konfirmasi Password
                </label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0093DD] focus:border-transparent" 
                       placeholder="Ulangi password baru">
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center gap-3 mt-6 pt-4 border-t border-gray-200">
            <button type="submit" class="px-6 py-2.5 bg-[#0093DD] text-white font-medium rounded-lg hover:bg-[#0080C0] transition shadow-sm">
                Simpan
            </button>
            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-green-600 font-medium">
                    ✓ Tersimpan
                </p>
            @endif
        </div>
    </form>
</div>
