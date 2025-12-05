<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-1 h-8 bg-gradient-to-b from-[#0093DD] to-[#0070AA] rounded-full"></div>
            <h2 class="font-semibold text-xl text-[#0093DD] leading-tight">
                {{ __('Edit Konten') }}
            </h2>
        </div>    
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Menampilkan Error Validasi --}}
            @if ($errors->any())
                <div x-data="{ show: true }" x-show="show"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     x-init="setTimeout(() => show = false, 5000)"
                     class="mb-6 p-4 bg-red-50 text-red-700 border-l-4 border-red-500 rounded-r-lg shadow-md flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-600 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9 6a1 1 0 012 0v4a1 1 0 01-2 0V6zm1 8a1.25 1.25 0 110-2.5 1.25 1.25 0 010 2.5z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <strong class="font-bold">Ada kesalahan!</strong>
                            <ul class="mt-1 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button @click="show = false" class="text-red-600 hover:text-red-800 transition flex-shrink-0">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                        </svg>
                    </button>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('admin.contents.update', $content->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- 
                            PENTING: Input Hidden untuk TYPE 
                            Ini agar Controller tahu tabel mana yang harus di-update (news/publication/dll)
                        --}}
                        <input type="hidden" name="type" value="{{ $type }}">

                        {{-- Hidden inputs untuk preserve filter & pagination --}}
                        @foreach(['type' => 'filter_type', 'category' => 'filter_category', 'q' => 'filter_q', 'sort' => 'filter_sort', 'page' => 'return_page', 'per_page' => 'return_per_page'] as $key => $name)
                            @if(request($key))
                                <input type="hidden" name="{{ $name }}" value="{{ request($key) }}">
                            @endif
                        @endforeach

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

                        {{-- 6. Image Upload / URL dengan Tab Switcher --}}
                        <div class="mb-6" x-data="{ imageSource: 'url' }">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar / Cover / Thumbnail</label>
                            
                            {{-- Tab Buttons --}}
                            <div class="flex gap-2 mb-3">
                                <button type="button" 
                                    @click="imageSource = 'url'"
                                    :class="imageSource === 'url' ? 'bg-[#0093DD] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                    class="flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-sm transition">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M12.232 4.232a2.5 2.5 0 013.536 3.536l-1.225 1.224a.75.75 0 001.061 1.06l1.224-1.224a4 4 0 00-5.656-5.656l-3 3a4 4 0 00.225 5.865.75.75 0 00.977-1.138 2.5 2.5 0 01-.142-3.667l3-3z"/>
                                        <path d="M11.603 7.963a.75.75 0 00-.977 1.138 2.5 2.5 0 01.142 3.667l-3 3a2.5 2.5 0 01-3.536-3.536l1.225-1.224a.75.75 0 00-1.061-1.06l-1.224 1.224a4 4 0 105.656 5.656l3-3a4 4 0 00-.225-5.865z"/>
                                    </svg>
                                    URL Gambar
                                </button>
                                <button type="button" 
                                    @click="imageSource = 'upload'"
                                    :class="imageSource === 'upload' ? 'bg-[#0093DD] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                    class="flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-sm transition">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.25 13.25a.75.75 0 001.5 0V4.636l2.955 3.129a.75.75 0 001.09-1.03l-4.25-4.5a.75.75 0 00-1.09 0l-4.25 4.5a.75.75 0 101.09 1.03L9.25 4.636v8.614z"/>
                                        <path d="M3.5 12.75a.75.75 0 00-1.5 0v2.5A2.75 2.75 0 004.75 18h10.5A2.75 2.75 0 0018 15.25v-2.5a.75.75 0 00-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5z"/>
                                    </svg>
                                    Upload Gambar
                                </button>
                            </div>

                            {{-- URL Input --}}
                            <div x-show="imageSource === 'url'" x-transition class="flex gap-4 items-start">
                                <div class="flex-1">
                                    <input type="text" name="image_url" id="image_url" 
                                        value="{{ old('image_url', $content->image_url) }}" 
                                        placeholder="https://example.com/gambar.jpg"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD]">
                                    <p class="text-xs text-gray-500 mt-1">Masukkan link gambar langsung dari website.</p>
                                </div>
                                
                                {{-- Preview Gambar Existing --}}
                                @if($content->image_url)
                                    <div class="flex-shrink-0 border p-1 rounded bg-gray-50">
                                        <img src="{{ $content->image_url }}" alt="Preview" class="h-16 w-16 object-cover rounded">
                                    </div>
                                @endif
                            </div>

                            {{-- File Upload --}}
                            <div x-show="imageSource === 'upload'" x-transition x-data="{ fileName: '', preview: '{{ $content->image_url ?? '' }}' }">
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-[#0093DD] transition">
                                    <input type="file" name="image_file" id="image_file" accept="image/*"
                                        @change="
                                            fileName = $event.target.files[0]?.name || '';
                                            if ($event.target.files[0]) {
                                                const reader = new FileReader();
                                                reader.onload = (e) => preview = e.target.result;
                                                reader.readAsDataURL($event.target.files[0]);
                                            }
                                        "
                                        class="hidden">
                                    
                                    {{-- Preview Area --}}
                                    <div x-show="preview" class="mb-4">
                                        <img :src="preview" alt="Preview" class="max-h-48 mx-auto rounded-lg shadow-md">
                                    </div>

                                    <label for="image_file" class="cursor-pointer">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                            </svg>
                                            <p class="text-sm font-medium text-gray-700">
                                                <span class="text-[#0093DD]">Klik untuk upload</span> atau drag & drop
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">PNG, JPG, JPEG, GIF hingga 2MB</p>
                                        </div>
                                    </label>
                                    
                                    <p x-show="fileName" x-text="'File dipilih: ' + fileName" class="text-sm text-green-600 font-medium mt-3"></p>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Upload gambar baru akan menggantikan gambar lama. File akan disimpan di server.</p>
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
                        
                        <div class="mb-4">
                            <label for="pdf_url" class="block text-sm font-medium text-gray-700 mb-1">Link PDF Publikasi</label>
                            <input type="url" name="pdf_url" id="pdf_url" value="{{ old('pdf_url', $content->pdf_url ?? '') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD] transition"
                                placeholder="https://example.com/file.pdf">
                            <p class="text-xs text-gray-500 mt-1">Link direct ke file PDF publikasi (opsional).</p>
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