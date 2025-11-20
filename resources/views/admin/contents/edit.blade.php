<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Konten') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Menampilkan Error Validasi --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded relative">
                            <strong class="font-bold">Ada kesalahan!</strong>
                            <ul class="mt-1 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.contents.update', $content->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- 
                            PENTING: Input Hidden untuk TYPE 
                            Ini agar Controller tahu tabel mana yang harus di-update (news/publication/dll)
                        --}}
                        <input type="hidden" name="type" value="{{ $type }}">

                        {{-- 1. Tipe Konten (Read Only / Info Saja) --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Konten</label>
                            <input type="text" disabled 
                                value="{{ ucfirst(str_replace('_', ' ', $type)) }}"
                                class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 text-gray-500 cursor-not-allowed">
                            <p class="text-xs text-gray-500 mt-1">Tipe konten tidak dapat diubah.</p>
                        </div>

                        {{-- 2. Judul --}}
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" 
                                value="{{ old('title', $content->title) }}" 
                                required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD] transition">
                        </div>

                        {{-- 3. Kategori --}}
                        <div class="mb-4">
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <input type="text" name="category" id="category" 
                                value="{{ old('category', $content->category) }}" 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD] transition">
                        </div>

                        {{-- 4. Tanggal Publish --}}
                        <div class="mb-4">
                            <label for="publish_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Publish</label>
                            {{-- Kita format ke Y-m-d agar input date HTML5 bisa membacanya --}}
                            @php
                                $dateValue = old('publish_date', $content->publish_date);
                                try {
                                    if($dateValue) {
                                        $dateValue = \Carbon\Carbon::parse($dateValue)->format('Y-m-d');
                                    }
                                } catch(\Exception $e) {
                                    $dateValue = '';
                                }
                            @endphp
                            <input type="date" name="publish_date" id="publish_date" 
                                value="{{ $dateValue }}" 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD] transition">
                        </div>

                        {{-- 5. Link Eksternal --}}
                        <div class="mb-4">
                            <label for="link" class="block text-sm font-medium text-gray-700 mb-1">Link Website / Detail</label>
                            <input type="url" name="link" id="link" 
                                value="{{ old('link', $content->link) }}" 
                                placeholder="https://..."
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD] transition">
                        </div>

                        {{-- 6. Image URL --}}
                        <div class="mb-6">
                            <label for="image_url" class="block text-sm font-medium text-gray-700 mb-1">URL Gambar (Thumbnail/Cover)</label>
                            <div class="flex gap-4 items-start">
                                <div class="flex-1">
                                    <input type="text" name="image_url" id="image_url" 
                                        value="{{ old('image_url', $content->image_url) }}" 
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD] transition mb-2">
                                    <p class="text-xs text-gray-500">Masukkan link gambar langsung.</p>
                                </div>
                                
                                {{-- Preview Gambar Kecil --}}
                                @if($content->image_url)
                                    <div class="flex-shrink-0 border p-1 rounded bg-gray-50">
                                        <img src="{{ $content->image_url }}" alt="Preview" class="h-16 w-16 object-cover rounded">
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- 7. Deskripsi/Abstrak --}}
                        <div class="mb-4">
                            <label for="abstract" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi / Abstrak</label>
                            <textarea name="abstract" id="abstract" rows="4"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD] transition"
                            >{{ old('abstract', $content->abstract) }}</textarea>
                        </div>

                        {{-- 7. ABSTRAKSI (Khusus Publikasi) --}}
                        @if($type === 'publication')
                        <div class="mb-4">
                            <label for="abstract" class="block text-sm font-medium text-gray-700 mb-1">Abstraksi</label>
                            <textarea name="abstract" id="abstract" rows="4"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD] transition"
                                placeholder="Masukkan abstraksi publikasi...">{{ old('abstract', $content->abstract_text) }}</textarea>
                        </div>
                        @endif

                        {{-- 8. DESKRIPSI / ISI KONTEN (Untuk Berita, Siaran Pers, Infografis) --}}
                        @if($type !== 'publication')
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ $type === 'infographic' ? 'Deskripsi Singkat' : 'Isi Konten / Berita' }}
                            </label>
                            <textarea name="description" id="description" rows="6"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD] transition"
                                placeholder="Masukkan isi konten...">{{ old('description', $content->desc_text) }}</textarea>
                        </div>
                        @endif

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center gap-4 border-t pt-4 mt-4">
                            <button type="submit" 
                                class="px-6 py-2 bg-[#0093DD] hover:bg-[#0070C0] text-white font-semibold rounded-lg shadow-sm transition">
                                Simpan Perubahan
                            </button>
                            
                            <a href="{{ route('admin.contents.index') }}" 
                               class="px-6 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                                Batal
                            </a>

                            <button type="button"
                                onclick="window.history.back()"
                                class="px-6 py-2 bg-gray-100 border border-gray-300 text-gray-600 font-semibold rounded-lg hover:bg-gray-200 transition">
                                Kembali ke Sebelumnya
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>