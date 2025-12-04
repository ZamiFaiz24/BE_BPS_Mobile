<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    {{-- Error Message --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded relative">
                            <strong class="font-bold">Oops! Ada kesalahan:</strong>
                            <ul class="mt-1 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Name --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD]"
                                placeholder="Masukkan nama lengkap">
                        </div>

                        {{-- Email --}}
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD]"
                                placeholder="user@example.com">
                        </div>

                        {{-- Role --}}
                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <select name="role" id="role" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD]">
                                <option value="">-- Pilih Role --</option>
                                <option value="superadmin" {{ old('role', $user->role) == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                                <option value="operator" {{ old('role', $user->role) == 'operator' ? 'selected' : '' }}>Operator</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Superadmin memiliki akses penuh, Operator hanya dapat mengelola konten.</p>
                        </div>

                        {{-- Password (Optional) --}}
                        <div class="mb-4" x-data="{ showPassword: false }">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Password Baru (Kosongkan jika tidak ingin mengubah)
                            </label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" name="password" id="password"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD] pr-10"
                                    placeholder="Minimal 8 karakter">
                                <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700">
                                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-4.803m5.596-3.856a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L19.414 18.414m-2.828-14.14l1.414-1.414M9.172 9.172L7.757 7.757m9.9 9.9l1.414 1.414M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Isi hanya jika ingin mengubah password.</p>
                        </div>

                        {{-- Password Confirmation --}}
                        <div class="mb-6" x-data="{ showPasswordConfirmation: false }">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Konfirmasi Password Baru
                            </label>
                            <div class="relative">
                                <input :type="showPasswordConfirmation ? 'text' : 'password'" name="password_confirmation" id="password_confirmation"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD] pr-10"
                                    placeholder="Ulangi password baru">
                                <button type="button" @click="showPasswordConfirmation = !showPasswordConfirmation" class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700">
                                    <svg x-show="!showPasswordConfirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg x-show="showPasswordConfirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-4.803m5.596-3.856a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L19.414 18.414m-2.828-14.14l1.414-1.414M9.172 9.172L7.757 7.757m9.9 9.9l1.414 1.414M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-4 pt-4 border-t">
                            <button type="submit" class="px-6 py-2 bg-[#0093DD] hover:bg-[#0070C0] text-white font-semibold rounded-lg transition">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
