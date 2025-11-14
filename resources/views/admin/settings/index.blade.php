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
                        <div class="p-6 space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Dashboard</label>
                                <input type="text" name="dashboard_title"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD]"
                                       value="{{ old('dashboard_title', $settings['dashboard_title'] ?? '') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Aplikasi</label>
                                <input type="text" name="site_description"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD]"
                                       value="{{ old('site_description', $settings['site_description'] ?? '') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Logo Dashboard</label>
                                @if(!empty($settings['site_logo']) || !empty($settings['logo']))
                                    <div class="mb-2 p-3 bg-gray-50 rounded border border-gray-200 inline-block">
                                        <img src="{{ asset($settings['site_logo'] ?? $settings['logo']) }}" alt="Logo" class="h-12 object-contain">
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
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden" x-data="{ editMode: false }">
                        <div class="p-6 space-y-5">
                            <!-- Header dengan Tombol Edit -->
                            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Konfigurasi API</h3>
                                    <p class="text-sm text-gray-500 mt-1">Kelola API Key dan jadwal sinkronisasi data</p>
                                </div>
                                <button type="button" 
                                        @click="editMode = !editMode"
                                        :class="editMode ? 'bg-gray-500 hover:bg-gray-600' : 'bg-[#0093DD] hover:bg-[#0080C0]'"
                                        class="px-4 py-2 text-white text-sm font-medium rounded-md transition shadow-sm flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path x-show="!editMode" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        <path x-show="editMode" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span x-text="editMode ? 'Batal Edit' : 'Edit Konfigurasi'"></span>
                                </button>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">BPS API Key</label>
                                <div class="relative">
                                    <input type="password" 
                                           id="bps_api_key" 
                                           name="api_key"
                                           :disabled="!editMode"
                                           class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD] disabled:bg-gray-50 disabled:text-gray-500 disabled:cursor-not-allowed"
                                           :value="editMode ? '{{ old('api_key', $settings['api_key'] ?? '') }}' : '{{ !empty($settings['api_key']) ? '••••••••••••••••' : '' }}'"
                                           :placeholder="editMode ? 'Masukkan API Key BPS' : 'API Key tersimpan dengan aman'">
                                    <button type="button" 
                                            onclick="togglePassword()"
                                            :disabled="!editMode"
                                            :class="editMode ? 'text-gray-400 hover:text-[#0093DD]' : 'text-gray-300 cursor-not-allowed'"
                                            class="absolute right-2 top-1/2 -translate-y-1/2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    <span x-show="!editMode">🔒 API Key dilindungi. Klik tombol "Edit Konfigurasi" untuk mengubah.</span>
                                    <span x-show="editMode">⚠️ API Key akan disimpan ke file .env untuk keamanan</span>
                                </p>
                            </div>
                            
                            <!-- Warning Box (hanya muncul saat edit mode) -->
                            <div x-show="editMode" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r">
                                <div class="flex">
                                    <svg class="h-5 w-5 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-yellow-700 font-medium">Informasi Keamanan</p>
                                        <p class="text-xs text-yellow-600 mt-1">API Key akan tersimpan di file .env dan tidak ditampilkan di database untuk keamanan aplikasi.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jadwal Sinkronisasi</label>
                                    <select name="sync_schedule"
                                            :disabled="!editMode"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD] disabled:bg-gray-50 disabled:text-gray-500 disabled:cursor-not-allowed">
                                        <option value="disabled" {{ ($settings['sync_schedule'] ?? '') == 'disabled' ? 'selected' : '' }}>Nonaktif</option>
                                        <option value="hourly" {{ ($settings['sync_schedule'] ?? '') == 'hourly' ? 'selected' : '' }}>Setiap Jam</option>
                                        <option value="daily" {{ ($settings['sync_schedule'] ?? '') == 'daily' ? 'selected' : '' }}>Setiap Hari</option>
                                        <option value="weekly" {{ ($settings['sync_schedule'] ?? '') == 'weekly' ? 'selected' : '' }}>Setiap Minggu</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Timeout (detik)</label>
                                    <input type="number" 
                                           name="scraping_timeout"
                                           :disabled="!editMode"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD] disabled:bg-gray-50 disabled:text-gray-500 disabled:cursor-not-allowed"
                                           value="{{ old('scraping_timeout', $settings['scraping_timeout'] ?? 30) }}" min="10" max="300">
                                </div>
                            </div>

                            <!-- Info Status Edit Mode -->
                            <div x-show="editMode" 
                                 x-transition:enter="transition ease-out duration-200"
                                 class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r">
                                <div class="flex">
                                    <svg class="h-5 w-5 text-blue-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-blue-700 font-medium">Mode Edit Aktif</p>
                                        <p class="text-xs text-blue-600 mt-1">Jangan lupa klik tombol "Simpan Perubahan" di bawah setelah selesai mengedit.</p>
                                    </div>
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
                                            class="px-4 py-2 bg-[#0093DD] text-white text-sm font-medium rounded-md hover:bg-[#0080C0] transition shadow-sm flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
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
            if (input.disabled) return; // Tidak bisa toggle jika disabled
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</x-app-layout>