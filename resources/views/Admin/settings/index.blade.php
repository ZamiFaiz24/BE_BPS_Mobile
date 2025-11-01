<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#0093DD] leading-tight">
            ⚙️ Pengaturan Aplikasi
        </h2>
    </x-slot>

    <div class="py-8 px-0 sm:px-6 lg:px-8 bg-gray-100 min-h-screen">
        @if (session('status'))
            <div class="mb-6 p-4 bg-green-100 text-green-800 border-l-4 border-green-500 rounded-r-lg max-w-5xl mx-auto shadow-md">
                <strong>✓ Berhasil!</strong> {{ session('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 text-red-800 border-l-4 border-red-500 rounded-r-lg max-w-5xl mx-auto shadow-md">
                <strong>⚠ Error:</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8 max-w-5xl mx-auto">
            @csrf

            {{-- Pengaturan Umum --}}
            <section class="bg-white rounded-xl shadow-lg p-8 border-t-4 border-[#0093DD]">
                <div class="flex items-center mb-6">
                    <div class="bg-[#0093DD] text-white rounded-full w-10 h-10 flex items-center justify-center mr-3">
                        <i data-lucide="settings" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-bold text-[#0093DD]">Pengaturan Umum</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Judul Dashboard --}}
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">📌 Judul Dashboard</label>
                        <input type="text" name="site_name" 
                               class="h-11 text-base w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#0093DD] focus:border-transparent transition" 
                               value="{{ old('site_name', $settings['site_name'] ?? '') }}" required>
                        <p class="text-xs text-gray-500 mt-1">Nama yang muncul di header dashboard</p>
                    </div>

                    {{-- Deskripsi Singkat --}}
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">📝 Deskripsi Aplikasi</label>
                        <input type="text" name="site_description" 
                               class="h-11 text-base w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#0093DD] focus:border-transparent transition" 
                               value="{{ old('site_description', $settings['site_description'] ?? '') }}" 
                               placeholder="Deskripsi singkat aplikasi">
                        <p class="text-xs text-gray-500 mt-1">Muncul di meta description & footer</p>
                    </div>

                    {{-- Logo Dashboard --}}
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">🖼️ Logo Dashboard</label>
                        @if(!empty($settings['site_logo']))
                            <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo" class="h-16 mb-3 bg-gray-50 p-2 rounded-lg border border-gray-200">
                        @endif
                        <input type="file" name="site_logo" accept="image/*" 
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#0093DD]/10 file:text-[#0093DD] hover:file:bg-[#0093DD]/20 transition">
                        <p class="text-xs text-gray-500 mt-1">Format: PNG/JPG, Maks 2MB</p>
                    </div>

                    {{-- Favicon --}}
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">⭐ Favicon</label>
                        @if(!empty($settings['site_favicon']))
                            <img src="{{ asset('storage/' . $settings['site_favicon']) }}" alt="Favicon" class="h-10 mb-3 bg-gray-50 p-1 rounded border border-gray-200">
                        @endif
                        <input type="file" name="site_favicon" accept="image/png, image/x-icon" 
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#0093DD]/10 file:text-[#0093DD] hover:file:bg-[#0093DD]/20 transition">
                        <p class="text-xs text-gray-500 mt-1">Format: ICO/PNG, 32x32 atau 64x64px</p>
                    </div>

                    {{-- Mode Perbaikan --}}
                    <div class="md:col-span-2 bg-yellow-50 border border-yellow-200 rounded-lg p-4 mt-2">
                        <label for="maintenance_mode" class="flex items-center cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1" class="sr-only" {{ !empty($settings['maintenance_mode']) ? 'checked' : '' }}>
                                <div class="block bg-gray-300 w-14 h-8 rounded-full"></div>
                                <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition shadow-md"></div>
                            </div>
                            <div class="ml-4">
                                <div class="font-semibold text-gray-800">🚧 Mode Perbaikan</div>
                                <p class="text-xs text-gray-600 mt-0.5">Nonaktifkan akses publik sementara untuk maintenance</p>
                            </div>
                        </label>
                    </div>
                </div>
            </section>

            {{-- Sinkronisasi & API --}}
            <section class="bg-white rounded-xl shadow-lg p-8 border-t-4 border-[#68B92E]">
                <div class="flex items-center mb-6">
                    <div class="bg-[#68B92E] text-white rounded-full w-10 h-10 flex items-center justify-center mr-3">
                        <i data-lucide="refresh-cw" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-bold text-[#68B92E]">Sinkronisasi & API</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Jadwal Sinkronisasi --}}
                    <div>
                        <label for="sync_schedule" class="block font-semibold mb-2 text-gray-700">⏰ Jadwal Sinkronisasi Otomatis</label>
                        <select name="sync_schedule" id="sync_schedule"
                                class="h-11 text-base w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#68B92E] focus:border-transparent transition">
                            <option value="disabled" {{ ($settings['sync_schedule'] ?? '') == 'disabled' ? 'selected' : '' }}>❌ Nonaktif</option>
                            <option value="daily" {{ ($settings['sync_schedule'] ?? '') == 'daily' ? 'selected' : '' }}>🌙 Setiap Hari (00:00 WIB)</option>
                            <option value="weekly" {{ ($settings['sync_schedule'] ?? '') == 'weekly' ? 'selected' : '' }}>📅 Setiap Minggu</option>
                            <option value="hourly" {{ ($settings['sync_schedule'] ?? '') == 'hourly' ? 'selected' : '' }}>⏱️ Setiap Jam</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Atur jadwal scraping data dari BPS</p>
                    </div>

                    {{-- Timeout Scraping --}}
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">⏳ Timeout Scraping (detik)</label>
                        <input type="number" name="scraping_timeout"
                               class="h-11 text-base w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#68B92E] focus:border-transparent transition"
                               value="{{ old('scraping_timeout', $settings['scraping_timeout'] ?? 30) }}" min="10" max="300">
                        <p class="text-xs text-gray-500 mt-1">Batas waktu maksimal scraping (10-300 detik)</p>
                    </div>

                    {{-- API Key --}}
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">🔑 BPS API Key</label>
                        <div class="relative">
                            <input type="password" id="bps_api_key" name="bps_api_key"
                                   class="h-11 text-base w-full border border-gray-300 rounded-lg px-4 py-2 pr-12 focus:ring-2 focus:ring-[#68B92E] focus:border-transparent transition"
                                   placeholder="••••••••••••••••" value="{{ old('bps_api_key', $settings['bps_api_key'] ?? '') }}">
                            <button type="button" onclick="togglePassword()"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                👁️
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">API Key untuk akses data BPS (opsional)</p>
                    </div>

                    {{-- Base URL BPS --}}
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">🌐 Base URL BPS</label>
                        <input type="url" name="bps_base_url"
                               class="h-11 text-base w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#68B92E] focus:border-transparent transition"
                               value="{{ old('bps_base_url', $settings['bps_base_url'] ?? 'https://kebumenkab.bps.go.id') }}"
                               placeholder="https://kebumenkab.bps.go.id">
                        <p class="text-xs text-gray-500 mt-1">URL dasar situs BPS yang akan di-scrape</p>
                    </div>
                </div>

                {{-- Status Sinkronisasi Terakhir --}}
                <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-700">📊 Status Sinkronisasi Terakhir</p>
                            <p class="text-xs text-gray-500 mt-1">
                                Terakhir: {{ $settings['last_sync'] ?? 'Belum pernah' }}
                            </p>
                        </div>
                        @if (Route::has('admin.sync.manual'))
                            <form method="POST" action="{{ route('admin.sync.manual') }}">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-[#68B92E] text-white rounded-lg hover:bg-[#5AA025] transition text-sm font-semibold">
                                    🔄 Sinkron Sekarang
                                </button>
                            </form>
                        @else
                            <button type="button" disabled class="px-4 py-2 bg-gray-300 text-white rounded-lg cursor-not-allowed text-sm font-semibold">
                                🔄 Sinkron Sekarang
                            </button>
                        @endif
                    </div>
                </div>
            </section>

            {{-- Notifikasi & Email --}}
            <section class="bg-white rounded-xl shadow-lg p-8 border-t-4 border-[#EB891C]">
                <div class="flex items-center mb-6">
                    <div class="bg-[#EB891C] text-white rounded-full w-10 h-10 flex items-center justify-center mr-3">
                        <i data-lucide="mail" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-bold text-[#EB891C]">Notifikasi & Email</h3>
                </div>
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-semibold mb-2 text-gray-700">📧 Email Admin</label>
                            <input type="email" name="admin_email"
                                   class="h-11 text-base w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#EB891C] focus:border-transparent transition"
                                   value="{{ old('admin_email', $settings['admin_email'] ?? '') }}" 
                                   placeholder="admin@example.com">
                            <p class="text-xs text-gray-500 mt-1">Email untuk menerima notifikasi sistem</p>
                        </div>

                        <div>
                            <label class="block font-semibold mb-2 text-gray-700">👤 Nama Pengirim (opsional)</label>
                            <input type="text" name="mail_from_name"
                                   class="h-11 text-base w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#EB891C] focus:border-transparent transition"
                                   value="{{ old('mail_from_name', $settings['mail_from_name'] ?? '') }}" 
                                   placeholder="Contoh: BPS Kebumen">
                            <p class="text-xs text-gray-500 mt-1">Ditampilkan sebagai nama pengirim email</p>
                        </div>
                    </div>

                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                        <label for="email_notifications" class="flex items-center cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" id="email_notifications" name="email_notifications" value="1" class="sr-only" {{ !empty($settings['email_notifications']) ? 'checked' : '' }}>
                                <div class="block bg-gray-300 w-14 h-8 rounded-full"></div>
                                <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition shadow-md"></div>
                            </div>
                            <div class="ml-4">
                                <div class="font-semibold text-gray-800 flex items-center gap-2">
                                    <i data-lucide="bell" class="w-4 h-4 text-[#EB891C]"></i>
                                    Notifikasi Email
                                </div>
                                <p class="text-xs text-gray-600 mt-0.5">Kirim email saat terjadi update atau error scraping</p>
                            </div>
                        </label>
                    </div>
                </div>
            </section>

            {{-- Keamanan Akun --}}
            <section class="bg-white rounded-xl shadow-lg p-8 border-t-4 border-red-500">
                <div class="flex items-center mb-6">
                    <div class="bg-red-500 text-white rounded-full w-10 h-10 flex items-center justify-center mr-3">
                        <i data-lucide="shield" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-bold text-red-500">Keamanan Akun</h3>
                </div>
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-semibold mb-2 text-gray-700">🔒 Password Baru</label>
                            <input type="password" name="password"
                                   class="h-11 text-base w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                                   placeholder="Password baru (opsional)">
                            <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter, kombinasikan huruf & angka</p>
                        </div>
                        <div>
                            <label class="block font-semibold mb-2 text-gray-700">✅ Konfirmasi Password</label>
                            <input type="password" name="password_confirmation"
                                   class="h-11 text-base w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                                   placeholder="Ulangi password baru">
                            <p class="text-xs text-gray-500 mt-1">Harus sama dengan password baru</p>
                        </div>
                    </div>

                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i data-lucide="info" class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0"></i>
                            <p class="text-xs text-gray-700 ml-3">
                                <strong>Tips:</strong> Gunakan password unik dan aktifkan logout berkala untuk keamanan tambahan
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Tombol Simpan (fixed + lebar konsisten) --}}
            <div class="fixed bottom-6 left-1/2 -translate-x-1/2 w-[calc(100%-2rem)] max-w-5xl z-40 bg-white p-4 rounded-xl shadow-2xl border border-gray-200">
                <div class="flex items-center gap-4">
                    <button type="submit" class="flex-1 md:flex-none px-8 py-3 text-base font-bold text-white bg-gradient-to-r from-[#0093DD] to-[#0070C0] rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
                        💾 Simpan Semua Pengaturan
                    </button>
                    <button type="reset" class="px-6 py-3 text-base font-semibold text-gray-700 bg-gray-200 rounded-full hover:bg-gray-300 transition">
                        🔄 Reset
                    </button>
                </div>
            </div>

            {{-- Spacer agar konten tidak tertutup tombol fixed --}}
            <div class="h-28"></div>
        </form>
    </div>

    {{-- CSS & JavaScript --}}
    <style>
        .dot {
            transform: translateX(0);
            transition: all 0.3s ease;
        }
        input:checked ~ .dot {
            transform: translateX(100%);
        }
        input:checked ~ .block {
            background-color: #68B92E;
        }
    </style>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
        function togglePassword() {
            const input = document.getElementById('bps_api_key');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</x-app-layout>
