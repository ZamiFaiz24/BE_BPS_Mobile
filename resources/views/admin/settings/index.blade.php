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
            
            {{-- Notifikasi Sukses & Error (Sama seperti Opsi 1) --}}
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

            {{-- FORM UTAMA PENGATURAN --}}
            {{-- `x-data` di sini akan mengelola state tab --}}
            <form x-data="{ tab: 'umum' }" action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- PERUBAHAN: Navigasi Tab --}}
                <div class="mb-5 border-b border-gray-200 bg-white rounded-t-lg shadow-sm">
                    <nav class="-mb-px flex space-x-6 px-6" aria-label="Tabs">
                        <button type="button" 
                                @click="tab = 'umum'"
                                :class="{ 'border-[#0093DD] text-[#0093DD]': tab === 'umum', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'umum' }"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Pengaturan Umum
                        </button>
                        <button type="button" 
                                @click="tab = 'keamanan'"
                                :class="{ 'border-[#0093DD] text-[#0093DD]': tab === 'keamanan', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'keamanan' }"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Keamanan
                        </button>
                        <button type="button" 
                                @click="tab = 'sinkronisasi'"
                                :class="{ 'border-[#0093DD] text-[#0093DD]': tab === 'sinkronisasi', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'sinkronisasi' }"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Sinkronisasi & API
                        </button>
                        <button type="button" 
                                @click="tab = 'notifikasi'"
                                :class="{ 'border-[#0093DD] text-[#0093DD]': tab === 'notifikasi', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'notifikasi' }"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Notifikasi Email
                        </button>
                    </nav>
                </div>

                {{-- PERUBAHAN: Konten Tab (Kartu-kartu dibungkus dengan `x-show`) --}}
                
                {{-- Panel 1: Pengaturan Umum --}}
                <div x-show="tab === 'umum'" x-cloak>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        {{-- Header kartu sengaja dihilangkan karena judul sudah ada di Tab --}}
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
                                <div class="flex items-center">
                                    <label for="site_logo_input" class="cursor-pointer px-4 py-2 border border-gray-300 rounded-l-md text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 whitespace-nowrap">
                                        Browse...
                                    </label>
                                    <span id="site_logo_text" class="flex-1 px-3 py-2 border-t border-b border-r border-gray-300 text-gray-500 text-sm rounded-r-md truncate">No file selected.</span>
                                </div>
                                <input type="file" name="site_logo" id="site_logo_input" accept="image/*" class="hidden" 
                                       onchange="document.getElementById('site_logo_text').textContent = this.files[0] ? this.files[0].name : 'No file selected.'">
                                <p class="text-xs text-gray-500 mt-1">PNG/JPG, maks 2MB</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Favicon</label>
                                @if(!empty($settings['site_favicon']))
                                    <div class="mb-2 p-2 bg-gray-50 rounded border border-gray-200 inline-block">
                                        <img src="{{ asset('storage/' . $settings['site_favicon']) }}" alt="Favicon" class="h-8">
                                    </div>
                                @endif
                                <div class="flex items-center">
                                    <label for="site_favicon_input" class="cursor-pointer px-4 py-2 border border-gray-300 rounded-l-md text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 whitespace-nowrap">
                                        Browse...
                                    </label>
                                    <span id="site_favicon_text" class="flex-1 px-3 py-2 border-t border-b border-r border-gray-300 text-gray-500 text-sm rounded-r-md truncate">No file selected.</span>
                                </div>
                                <input type="file" name="site_favicon" id="site_favicon_input" accept="image/png, image/x-icon" class="hidden"
                                       onchange="document.getElementById('site_favicon_text').textContent = this.files[0] ? this.files[0].name : 'No file selected.'">
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
                </div>
                
                {{-- Panel 2: Keamanan --}}
                <div x-show="tab === 'keamanan'" x-cloak>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
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

                {{-- Panel 3: Sinkronisasi & API --}}
                <div x-show="tab === 'sinkronisasi'" x-cloak>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
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
                        </div>
                        <div class="pt-4 border-t bg-[#F0F9FF] px-6 py-4 rounded-b-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Sinkronisasi Terakhir</p>
                                    <p class="text-xs text-gray-600 mt-0.5">{{ $settings['last_sync'] ?? 'Belum pernah' }}</p>
                                </div>
                                @if (Route::has('admin.sync.manual'))
                                    <button type="button" 
                                            onclick="if(confirm('Apakah Anda yakin ingin memulai sinkronisasi manual?')) document.getElementById('sync-form').submit();"
                                            class="px-4 py-2 bg-[#0093DD] text-white text-sm font-medium rounded-md hover:bg-[#0080C0] transition shadow-sm">
                                        Sinkron Sekarang
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Panel 4: Notifikasi Email --}}
                <div x-show="tab === 'notifikasi'" x-cloak>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
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
                                </LAbel>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons (Di luar Tab, tapi di dalam Form) --}}
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="reset" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 transition shadow-sm">
                        Reset
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-[#0093DD] text-white font-medium rounded-md hover:bg-[#0080C0] transition shadow-md">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
            {{-- AKHIR FORM UTAMA --}}


            {{-- Form untuk Sinkronisasi Manual (diletakkan di luar form utama) --}}
            @if (Route::has('admin.sync.manual'))
                <form id="sync-form" method="POST" action="{{ route('admin.sync.manual') }}" class="hidden">
                    @csrf
                </form>
            @endif

        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('bps_api_key');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</x-app-layout>