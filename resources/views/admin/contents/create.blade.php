<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Konten Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Wrapper Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Error Validation --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded relative">
                            <strong class="font-bold">Oops! Ada kesalahan input:</strong>
                            <ul class="mt-1 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- 
                        x-data: Menginisialisasi state Alpine.js. 
                        Kita pantau 'type' agar bisa show/hide input tertentu.
                    --}}
                    <form action="{{ route('admin.contents.store') }}" method="POST" enctype="multipart/form-data" 
                          x-data="{ type: '{{ old('type', '') }}' }">
                        @csrf

                        {{-- 1. PILIH TIPE KONTEN (Wajib) --}}
                        <div class="mb-6 border-b pb-6">
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tipe Konten <span class="text-red-500">*</span></label>
                            <select name="type" id="type" x-model="type" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD] transition">
                                <option value="" disabled>-- Pilih Tipe Konten --</option>
                                <option value="news" {{ old('type') == 'news' ? 'selected' : '' }}>Berita</option>
                                <option value="press_release" {{ old('type') == 'press_release' ? 'selected' : '' }}>Siaran Pers</option>
                                <option value="publication" {{ old('type') == 'publication' ? 'selected' : '' }}>Publikasi</option>
                                <option value="infographic" {{ old('type') == 'infographic' ? 'selected' : '' }}>Infografik</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Pilih tipe konten terlebih dahulu untuk mengisi detail.</p>
                        </div>

                        {{-- 
                            WRAPPER INPUT DINAMIS 
                            Hanya muncul jika type sudah dipilih
                        --}}
                        <div x-show="type !== ''" x-transition x-cloak class="space-y-4">
                            
                            {{-- Judul --}}
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-red-500">*</span></label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD]">
                            </div>

                            {{-- Kategori --}}
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                <input type="text" name="category" id="category" value="{{ old('category') }}" placeholder="Contoh: Ekonomi, Sosial..."
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD]">
                            </div>

                            {{-- Tanggal Publish (Mapping ke 'date' atau 'release_date') --}}
                            <div>
                                <label for="publish_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Publish / Rilis</label>
                                <input type="date" name="publish_date" id="publish_date" value="{{ old('publish_date') }}" 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD]">
                            </div>

                            {{-- Link Eksternal / PDF --}}
                            <div>
                                <label for="link" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span x-text="type === 'press_release' ? 'Link PDF / Detail' : 'Link Website / Sumber'"></span>
                                </label>
                                <input type="url" name="link" id="link" value="{{ old('link') }}" placeholder="https://..."
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD]">
                            </div>

                            {{-- Image URL --}}
                            <div>
                                <label for="image_url" class="block text-sm font-medium text-gray-700 mb-1">URL Gambar / Cover / Thumbnail</label>
                                <input type="text" name="image_url" id="image_url" value="{{ old('image_url') }}" placeholder="https://..."
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD]">
                                <p class="text-xs text-gray-500 mt-1">Masukkan link gambar langsung.</p>
                            </div>

                            {{-- 
                                KONDISIONAL: Abstraksi (Hanya untuk Publikasi) 
                            --}}
                            <div x-show="type === 'publication'">
                                <label for="abstract" class="block text-sm font-medium text-gray-700 mb-1">Abstraksi</label>
                                <textarea name="abstract" id="abstract" rows="4" placeholder="Ringkasan publikasi..."
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD]">{{ old('abstract') }}</textarea>
                            </div>

                            {{-- 
                                KONDISIONAL: Deskripsi (Untuk Berita, Siaran Pers, Infografik) 
                            --}}
                            <div x-show="type !== 'publication'">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Isi Konten / Deskripsi</label>
                                <textarea name="description" id="description" rows="6" placeholder="Isi konten lengkap..."
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD]">{{ old('description') }}</textarea>
                            </div>

                            {{-- Tombol Simpan --}}
                            <div class="flex items-center gap-4 pt-4">
                                <button type="submit" class="px-6 py-2 bg-[#0093DD] hover:bg-[#0070C0] text-white font-semibold rounded-lg shadow-sm transition">
                                    Simpan Konten
                                </button>
                                <a href="{{ route('admin.contents.index') }}" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                                    Batal
                                </a>
                                <button type="button"
                                    onclick="window.history.back()"
                                    class="px-6 py-2 bg-gray-100 border border-gray-300 text-gray-600 font-semibold rounded-lg hover:bg-gray-200 transition">
                                    Kembali ke Sebelumnya
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>