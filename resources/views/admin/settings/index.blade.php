<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-1 h-8 bg-gradient-to-b from-[#0093DD] to-[#0070AA] rounded-full"></div>
            <h2 class="font-semibold text-xl text-[#0093DD] leading-tight">
                Pengaturan Aplikasi
            </h2>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto space-y-6">
            
            @if (session('status'))
                <div class="bg-green-50 border-l-4 border-green-600 p-4 rounded-r shadow-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-green-700 font-medium">{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-600 p-4 rounded-r shadow-sm">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-red-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <ul class="text-red-700 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Kolom Kiri --}}
                    <div class="space-y-6">
                        {{-- Pengaturan Umum --}}
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 bg-[#0093DD] border-b border-[#0080C0]">
                                <h3 class="text-base font-semibold text-white flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Pengaturan Umum
                                </h3>
                            </div>
                            <div class="p-6 space-y-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Dashboard</label>
                                    <input type="text" name="site_name" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD]" 
                                           value="{{ old('site_name', $settings['site_name'] ?? '') }}" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Aplikasi</label>
                                    <input type="text" name="site_description" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD]" 
                                           value="{{ old('site_description', $settings['site_description'] ?? '') }}">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Logo Dashboard</label>
                                    @if(!empty($settings['site_logo']))
                                        <div class="mb-2 p-3 bg-gray-50 rounded border border-gray-200 inline-block">
                                            <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo" class="h-12 object-contain">
                                        </div>
                                    @endif
                                    <input type="file" name="site_logo" accept="image/*" 
                                           class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border file:border-gray-300 file:text-sm file:font-medium file:bg-[#E6F4FB] file:text-[#0093DD] hover:file:bg-[#CCE9F7]">
                                    <p class="text-xs text-gray-500 mt-1">PNG/JPG, maks 2MB</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Favicon</label>
                                    @if(!empty($settings['site_favicon']))
                                        <div class="mb-2 p-2 bg-gray-50 rounded border border-gray-200 inline-block">
                                            <img src="{{ asset('storage/' . $settings['site_favicon']) }}" alt="Favicon" class="h-8">
                                        </div>
                                    @endif
                                    <input type="file" name="site_favicon" accept="image/png, image/x-icon" 
                                           class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border file:border-gray-300 file:text-sm file:font-medium file:bg-[#E6F4FB] file:text-[#0093DD] hover:file:bg-[#CCE9F7]">
                                    <p class="text-xs text-gray-500 mt-1">ICO/PNG, 32x32px</p>
                                </div>

                                <div class="pt-4 border-t border-gray-200">
                                    <label class="flex items-center cursor-pointer group">
                                        <input type="checkbox" name="maintenance_mode" value="1" 
                                               class="w-4 h-4 text-[#0093DD] border-gray-300 rounded focus:ring-[#0093DD]"
                                               {{ !empty($settings['maintenance_mode']) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700 group-hover:text-gray-900">Aktifkan Mode Maintenance</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Keamanan --}}
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 bg-[#0093DD] border-b border-[#0080C0]">
                                <h3 class="text-base font-semibold text-white flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Keamanan
                                </h3>
                            </div>
                            <div class="p-6 space-y-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                                    <input type="password" name="password"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD]"
                                           placeholder="Minimal 8 karakter">
                                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD]"
                                           placeholder="Ulangi password baru">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kolom Kanan --}}
                    <div class="space-y-6">
                        {{-- Sinkronisasi & API --}}
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 bg-[#0093DD] border-b border-[#0080C0]">
                                <h3 class="text-base font-semibold text-white flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Sinkronisasi & API
                                </h3>
                            </div>
                            <div class="p-6 space-y-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Base URL BPS</label>
                                    <input type="url" name="bps_base_url"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD]"
                                           value="{{ old('bps_base_url', $settings['bps_base_url'] ?? 'https://kebumenkab.bps.go.id') }}">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">BPS API Key</label>
                                    <div class="relative">
                                        <input type="password" id="bps_api_key" name="bps_api_key"
                                               class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD]"
                                               value="{{ old('bps_api_key', $settings['bps_api_key'] ?? '') }}">
                                        <button type="button" onclick="togglePassword()"
                                                class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#0093DD]">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jadwal Sinkronisasi</label>
                                        <select name="sync_schedule"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD]">
                                            <option value="disabled" {{ ($settings['sync_schedule'] ?? '') == 'disabled' ? 'selected' : '' }}>Nonaktif</option>
                                            <option value="hourly" {{ ($settings['sync_schedule'] ?? '') == 'hourly' ? 'selected' : '' }}>Setiap Jam</option>
                                            <option value="daily" {{ ($settings['sync_schedule'] ?? '') == 'daily' ? 'selected' : '' }}>Setiap Hari</option>
                                            <option value="weekly" {{ ($settings['sync_schedule'] ?? '') == 'weekly' ? 'selected' : '' }}>Setiap Minggu</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Timeout (detik)</label>
                                        <input type="number" name="scraping_timeout"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD]"
                                               value="{{ old('scraping_timeout', $settings['scraping_timeout'] ?? 30) }}" min="10" max="300">
                                    </div>
                                </div>

                                <div class="pt-4 border-t bg-[#F0F9FF] -mx-6 -mb-6 px-6 py-4 rounded-b-lg">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Sinkronisasi Terakhir</p>
                                            <p class="text-xs text-gray-600 mt-0.5">{{ $settings['last_sync'] ?? 'Belum pernah' }}</p>
                                        </div>
                                        @if (Route::has('admin.sync.manual'))
                                            <form method="POST" action="{{ route('admin.sync.manual') }}">
                                                @csrf
                                                <button type="submit" class="px-4 py-2 bg-[#0093DD] text-white text-sm font-medium rounded-md hover:bg-[#0080C0] transition shadow-sm">
                                                    Sinkron Sekarang
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Notifikasi Email --}}
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 bg-[#0093DD] border-b border-[#0080C0]">
                                <h3 class="text-base font-semibold text-white flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    Notifikasi Email
                                </h3>
                            </div>
                            <div class="p-6 space-y-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Admin</label>
                                    <input type="email" name="admin_email"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD]"
                                           value="{{ old('admin_email', $settings['admin_email'] ?? '') }}"
                                           placeholder="admin@example.com">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pengirim</label>
                                    <input type="text" name="mail_from_name"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD]"
                                           value="{{ old('mail_from_name', $settings['mail_from_name'] ?? '') }}"
                                           placeholder="BPS Dashboard">
                                </div>

                                <div class="pt-4 border-t border-gray-200">
                                    <label class="flex items-center cursor-pointer group">
                                        <input type="checkbox" name="email_notifications" value="1" 
                                               class="w-4 h-4 text-[#0093DD] border-gray-300 rounded focus:ring-[#0093DD]"
                                               {{ !empty($settings['email_notifications']) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700 group-hover:text-gray-900">Aktifkan Notifikasi Email</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="reset" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 transition shadow-sm">
                        Reset
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-[#0093DD] text-white font-medium rounded-md hover:bg-[#0080C0] transition shadow-md">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('bps_api_key');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</x-app-layout>