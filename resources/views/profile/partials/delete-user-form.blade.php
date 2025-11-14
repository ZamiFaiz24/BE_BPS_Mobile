{{-- BAGIAN 1 (VERSI PERBAIKAN) --}}
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-red-600">Hapus Akun</h3>
        <p class="text-sm text-gray-600 mt-1">Setelah akun dihapus, semua data akan hilang permanen</p>
    </div>

    <div class="p-6">
        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                class="px-5 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition shadow-sm">
            Hapus Akun
        </button>
    </div>

    {{-- Modal konfirmasi Anda sudah sangat bagus, tidak perlu diubah --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <div class="flex items-start gap-3 mb-6">
                <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus Akun</h3>
                    <p class="mt-2 text-sm text-gray-600">
                        Apakah Anda yakin ingin menghapus akun? Masukkan password untuk konfirmasi.
                    </p>
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input id="password" name="password" type="password"
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                       placeholder="Masukkan password Anda">
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                        class="px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition shadow-sm">
                    Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
</div>