<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah User Baru') }}
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

                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        {{-- Name --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD]"
                                placeholder="Masukkan nama lengkap">
                        </div>

                        {{-- Email --}}
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
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
                                <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                                <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>Operator</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Superadmin memiliki akses penuh, Operator hanya dapat mengelola konten.</p>
                        </div>

                        {{-- Password --}}
                        <div class="mb-4" x-data="{ showPassword: false }">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" name="password" id="password" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-10 focus:ring-[#0093DD] focus:border-[#0093DD]"
                                    placeholder="Minimal 8 karakter">
                                <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700">
                                    <svg x-show="!showPassword" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <svg x-show="showPassword" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/>
                                        <path d="M15.171 13.576l1.414 1.414a1 1 0 00.707-.293.999.999 0 00-.293-1.614A9.958 9.958 0 0010 17c-4.478 0-8.268-2.943-9.542-7a9.968 9.968 0 011.900-3.416l1.514 1.514a4 4 0 004.714 4.714l1.485 1.485z"/>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Password minimal 8 karakter.</p>
                        </div>

                        {{-- Password Confirmation --}}
                        <div class="mb-6" x-data="{ showPasswordConfirm: false }">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input :type="showPasswordConfirm ? 'text' : 'password'" name="password_confirmation" id="password_confirmation" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-10 focus:ring-[#0093DD] focus:border-[#0093DD]"
                                    placeholder="Ulangi password">
                                <button type="button" @click="showPasswordConfirm = !showPasswordConfirm" class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700">
                                    <svg x-show="!showPasswordConfirm" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <svg x-show="showPasswordConfirm" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/>
                                        <path d="M15.171 13.576l1.414 1.414a1 1 0 00.707-.293.999.999 0 00-.293-1.614A9.958 9.958 0 0010 17c-4.478 0-8.268-2.943-9.542-7a9.968 9.968 0 011.900-3.416l1.514 1.514a4 4 0 004.714 4.714l1.485 1.485z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-4 pt-4 border-t">
                            <button type="submit" class="px-6 py-2 bg-[#0093DD] hover:bg-[#0070C0] text-white font-semibold rounded-lg transition">
                                <i class="fas fa-save mr-2"></i>Simpan User
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
