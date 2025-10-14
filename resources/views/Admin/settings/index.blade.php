<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#0093DD] leading-tight">
            Pengaturan Aplikasi
        </h2>
    </x-slot>

    <div class="py-8 px-0 sm:px-6 lg:px-8 bg-gray-100 min-h-screen">
        @if (session('status'))
            <div class="mb-6 p-4 bg-green-100 text-green-800 border-l-4 border-green-500 rounded-r-lg max-w-5xl mx-auto">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 text-red-800 border-l-4 border-red-500 rounded-r-lg max-w-5xl mx-auto">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-12 max-w-5xl mx-auto">
            @csrf

            {{-- Pengaturan Umum --}}
            <section class="bg-white rounded-xl shadow p-8 border border-[#0093DD]/10">
                <h3 class="text-xl font-bold text-[#0093DD] mb-6">Pengaturan Umum</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Judul Dashboard --}}
                    <div>
                        <label class="block font-semibold mb-2 text-[#0093DD]">Judul Dashboard</label>
                        <input type="text" name="site_name" class="w-full border border-[#0093DD] rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD]" value="{{ old('site_name', $settings['site_name'] ?? '') }}" required>
                    </div>

                    {{-- Mode Perbaikan --}}
                    <div>
                        <label class="block font-semibold mb-2 text-[#0093DD]">Mode Perbaikan</label>
                        <label for="maintenance_mode" class="flex items-center cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1" class="sr-only" {{ !empty($settings['maintenance_mode']) ? 'checked' : '' }}>
                                <div class="block bg-gray-200 w-14 h-8 rounded-full"></div>
                                <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"></div>
                            </div>
                            <div class="ml-3 text-gray-700">
                                Aktifkan Mode Perbaikan
                            </div>
                        </label>
                    </div>

                    {{-- Logo Dashboard --}}
                    <div>
                        <label class="block font-semibold mb-2 text-[#0093DD]">Logo Dashboard</label>
                        @if(!empty($settings['site_logo']))
                            <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo" class="h-12 mb-2 bg-gray-100 p-1 rounded-md">
                        @endif
                        <input type="file" name="site_logo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#0093DD]/10 file:text-[#0093DD] hover:file:bg-[#0093DD]/20">
                    </div>

                    {{-- Favicon --}}
                    <div>
                        <label class="block font-semibold mb-2 text-[#0093DD]">Favicon</label>
                        @if(!empty($settings['site_favicon']))
                            <img src="{{ asset('storage/' . $settings['site_favicon']) }}" alt="Favicon" class="h-8 mb-2">
                        @endif
                        <input type="file" name="site_favicon" accept="image/png, image/x-icon" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#0093DD]/10 file:text-[#0093DD] hover:file:bg-[#0093DD]/20">
                    </div>
                </div>
            </section>

            {{-- Pengaturan Sinkronisasi & API --}}
            <section class="bg-white rounded-xl shadow p-8 border border-[#0093DD]/10">
                <h3 class="text-xl font-bold text-[#0093DD] mb-6">Sinkronisasi & API</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Jadwal Sinkronisasi --}}
                    <div>
                        <label for="sync_schedule" class="block font-semibold mb-2 text-[#0093DD]">Jadwal Sinkronisasi Otomatis</label>
                        <select name="sync_schedule" id="sync_schedule" class="w-full border border-[#0093DD] rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD]">
                            <option value="disabled" {{ ($settings['sync_schedule'] ?? '') == 'disabled' ? 'selected' : '' }}>Nonaktif</option>
                            <option value="daily" {{ ($settings['sync_schedule'] ?? '') == 'daily' ? 'selected' : '' }}>Setiap Hari (Malam)</option>
                            <option value="weekly" {{ ($settings['sync_schedule'] ?? '') == 'weekly' ? 'selected' : '' }}>Setiap Minggu</option>
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Atur jadwal sinkronisasi otomatis data.</p>
                    </div>

                    {{-- API Key --}}
                    <div>
                        <label class="block font-semibold mb-2 text-[#0093DD]">BPS API Key</label>
                        <input type="password" name="bps_api_key" class="w-full border border-[#0093DD] rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD]" placeholder="••••••••••••••••">
                        <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengubah API Key.</p>
                    </div>
                </div>
            </section>

            {{-- Keamanan Akun --}}
            <section class="bg-white rounded-xl shadow p-8 border border-[#EB891C]/10">
                <h3 class="text-xl font-bold text-[#EB891C] mb-6">Keamanan Akun</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block font-semibold mb-2 text-[#EB891C]">Password Baru</label>
                        <input type="password" name="password" class="w-full border border-[#EB891C] rounded-lg px-3 py-2 focus:ring-[#EB891C] focus:border-[#EB891C]" placeholder="Password baru (opsional)">
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-[#EB891C]">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="w-full border border-[#EB891C] rounded-lg px-3 py-2 focus:ring-[#EB891C] focus:border-[#EB891C]" placeholder="Ulangi password baru">
                    </div>
                </div>
            </section>

            <div class="flex items-center gap-3 mt-8">
                <button type="submit" class="px-6 py-2 text-base font-semibold text-white bg-[#0093DD] rounded-full shadow-lg hover:bg-[#0070C0] transition">
                    Simpan Semua Pengaturan
                </button>
            </div>
        </form>
    </div>

    {{-- CSS untuk toggle switch --}}
    <style>
        .dot {
            transform: translateX(0);
        }
        input:checked ~ .dot {
            transform: translateX(100%);
        }
        input:checked ~ .block {
            background-color: #0093DD;
        }
    </style>
</x-app-layout>
