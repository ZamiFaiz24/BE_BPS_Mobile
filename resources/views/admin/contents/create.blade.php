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

                        {{-- Hidden inputs untuk preserve filter & pagination --}}
                        @foreach(['type' => 'filter_type', 'category' => 'filter_category', 'q' => 'filter_q', 'sort' => 'filter_sort', 'page' => 'return_page', 'per_page' => 'return_per_page'] as $key => $name)
                            @if(request($key))
                                <input type="hidden" name="{{ $name }}" value="{{ request($key) }}">
                            @endif
                        @endforeach

                        {{-- 0. INPUT URL SCRAPE --}}
                        <div class="mb-6">
                            <label for="scrape_url" class="block text-sm font-medium text-gray-700 mb-1">Ambil Konten dari URL BPS</label>
                            <div class="flex gap-2">
                                <input type="url" id="scrape_url" class="w-full border rounded-lg px-3 py-2" placeholder="https://kebumenkab.bps.go.id/..." />
                                <button type="button" id="btn-scrape" class="px-4 py-2 bg-[#0093DD] text-white rounded-lg">Ambil Konten</button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Masukkan URL detail publikasi/berita/infografik BPS, lalu klik Ambil Konten.</p>
                        </div>

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

                            {{-- Image Upload / URL dengan Tab Switcher --}}
                            <div x-data="{ imageSource: 'url' }">
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
                                <div x-show="imageSource === 'url'" x-transition>
                                    <input type="text" name="image_url" id="image_url" value="{{ old('image_url') }}" placeholder="https://example.com/gambar.jpg"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD]">
                                    <p class="text-xs text-gray-500 mt-1">Masukkan link gambar langsung dari website.</p>
                                </div>

                                {{-- File Upload --}}
                                <div x-show="imageSource === 'upload'" x-transition x-data="{ fileName: '', preview: '' }">
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
                                    <p class="text-xs text-gray-500 mt-1">Upload gambar dari komputer Anda. File akan disimpan di server.</p>
                                </div>
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
                    <script>
                    document.getElementById('btn-scrape').onclick = async function() {
                        // 1. Ambil URL
                        const urlInput = document.getElementById('scrape_url');
                        const url = urlInput.value;
                        
                        if (!url) {
                            alert('Masukkan URL BPS terlebih dahulu!');
                            urlInput.focus();
                            return;
                        }

                        // 2. UI Loading State
                        const btn = this;
                        const originalText = btn.innerText;
                        btn.disabled = true;
                        btn.innerText = 'Sedang Mengambil...';
                        btn.classList.add('opacity-75', 'cursor-not-allowed');

                        try {
                            // 3. Request ke Backend
                            const res = await fetch("{{ route('admin.contents.scrape') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ url })
                            });

                            const result = await res.json();

                            if (result.success && result.data) {
                                const data = result.data;

                                // Auto-fill Title
                                if (data.title && document.getElementById('title')) {
                                    document.getElementById('title').value = data.title;
                                }

                                // Auto-fill Category
                                if (data.category && document.getElementById('category')) {
                                    document.getElementById('category').value = data.category;
                                }

                                // Auto-fill Date (prefer date_iso)
                                if (document.getElementById('publish_date')) {
                                    const dateValue = data.date_iso || data.date || '';
                                    document.getElementById('publish_date').value = dateValue;
                                }

                                // Auto-fill Image URL
                                if (data.image && document.getElementById('image_url')) {
                                    document.getElementById('image_url').value = data.image;
                                }

                                // Auto-fill Link
                                if (data.url && document.getElementById('link')) {
                                    document.getElementById('link').value = data.url;
                                }

                                // Detect & Set Type
                                const typeEl = document.getElementById('type');
                                const typeMapping = {
                                    'pressrelease': 'press_release',
                                    'news': 'news',
                                    'publication': 'publication',
                                    'infographic': 'infographic'
                                };
                                const detectedType = typeMapping[data.type] || '';
                                
                                if (detectedType && typeEl) {
                                    typeEl.value = detectedType;
                                    typeEl.dispatchEvent(new Event('change'));
                                }

                                // Auto-fill Content (wait for Alpine to render conditional fields)
                                setTimeout(() => {
                                    if (detectedType === 'publication') {
                                        const abstractEl = document.getElementById('abstract');
                                        if (abstractEl) abstractEl.value = data.content || data.description || '';
                                    } else {
                                        const descEl = document.getElementById('description');
                                        if (descEl) descEl.value = data.content || data.description || '';
                                    }
                                }, 150);

                                alert('✅ Data berhasil diambil!\nSilakan edit jika perlu, lalu klik Simpan Konten.');

                            } else {
                                alert('❌ Gagal: ' + (result.message || 'Error tidak diketahui'));
                            }

                        } catch (e) {
                            console.error(e);
                            alert('Terjadi kesalahan koneksi: ' + e.message);
                        } finally {
                            // 4. Reset Button
                            btn.disabled = false;
                            btn.innerText = originalText;
                            btn.classList.remove('opacity-75', 'cursor-not-allowed');
                        }
                    };
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>