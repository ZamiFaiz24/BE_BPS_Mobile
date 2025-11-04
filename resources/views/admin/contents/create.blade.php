<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#0093DD] leading-tight">
            {{ __('Manajemen Konten') }}
        </h2>
    </x-slot>

    <div class="max-w-xl mx-auto mt-8 bg-white p-8 rounded-3xl shadow-2xl border border-[#0093DD]/10">
        <h2 class="text-2xl font-bold mb-6 text-[#0093DD]">Tambah Konten Baru</h2>
        
        {{-- Menampilkan error validasi jika ada --}}
        @if ($errors->any())
            <div class="bg-[#EB891C]/10 border border-[#EB891C] text-[#EB891C] px-4 py-3 rounded-lg mb-4" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline">Ada beberapa masalah dengan input Anda.</span>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.contents.store') }}" method="POST" enctype="multipart/form-data" x-data="{ type: '{{ old('type', '') }}', uploadMethod: '{{ old('upload_method', 'url') }}' }">
            @csrf

            {{-- Tipe Konten --}}
            <div class="mb-5">
                <label for="type" class="block font-semibold mb-2 text-[#0093DD]">Tipe Konten <span class="text-red-500">*</span></label>
                <select name="type" id="type" class="w-full border border-[#0093DD] rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD] text-[#0093DD] bg-white" x-model="type" required>
                    <option value="">-- Pilih Tipe --</option>
                    <option value="berita">Berita</option>
                    <option value="publikasi">Publikasi</option>
                    <option value="infografik">Infografik</option>
                </select>
            </div>

            <div class="mb-5">
                <label for="title" class="block font-semibold mb-2 text-[#0093DD]">Judul <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" class="w-full border border-[#0093DD] rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD]" value="{{ old('title') }}" required>
            </div>

            {{-- Deskripsi / Abstraksi --}}
            <div class="mb-5" x-show="type === 'berita' || type === 'publikasi'" x-cloak>
                <label for="description" class="block font-semibold mb-2 text-[#0093DD]">Deskripsi / Abstraksi</label>
                <textarea name="description" id="description" rows="4" class="w-full border border-[#0093DD] rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD]">{{ old('description') }}</textarea>
            </div>

            {{-- Isi Konten Berita --}}
            <div class="mb-5" x-show="type === 'berita'" x-cloak>
                <label for="content_body" class="block font-semibold mb-2 text-[#0093DD]">Isi Konten Lengkap</label>
                <textarea name="content_body" id="content_body" rows="8" class="w-full border border-[#0093DD] rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD]">{{ old('content_body') }}</textarea>
            </div>

            {{-- URL File Publikasi --}}
            <div class="mb-5" x-show="type === 'publikasi'" x-cloak>
                <label for="file_url" class="block font-semibold mb-2 text-[#0093DD]">URL File (PDF)</label>
                <input type="text" name="file_url" id="file_url" class="w-full border border-[#0093DD] rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD]" value="{{ old('file_url') }}" placeholder="Tempel link unduh publikasi di sini...">
            </div>

            {{-- Input Gambar --}}
            <div class="mb-5 p-4 border border-[#68B92E] rounded-xl bg-[#68B92E]/5" x-show="type === 'publikasi' || type === 'infografik'" x-cloak>
                <label class="block font-semibold mb-2 text-[#68B92E]">Input Gambar</label>
                <div class="flex items-center space-x-6 mb-3">
                    <label class="flex items-center">
                        <input type="radio" name="upload_method" value="url" x-model="uploadMethod" class="form-radio text-[#0093DD] focus:ring-[#0093DD]">
                        <span class="ml-2 text-sm text-[#0093DD]">Dari URL</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="upload_method" value="upload" x-model="uploadMethod" class="form-radio text-[#EB891C] focus:ring-[#EB891C]">
                        <span class="ml-2 text-sm text-[#EB891C]">Upload File</span>
                    </label>
                </div>
                <div x-show="uploadMethod === 'url'" x-cloak>
                    <label for="image_source_url" class="block font-semibold mb-1 text-sm text-[#0093DD]">URL Gambar Sumber</label>
                    <input type="text" name="image_source_url" id="image_source_url" class="w-full border border-[#0093DD] rounded-lg px-3 py-2 text-sm focus:ring-[#0093DD] focus:border-[#0093DD]" value="{{ old('image_source_url') }}" placeholder="Tempel link gambar di sini...">
                </div>
                <div x-show="uploadMethod === 'upload'" x-cloak>
                    <label for="image_upload" class="block font-semibold mb-1 text-sm text-[#EB891C]">Pilih File Gambar</label>
                    <input type="file" name="image_upload" id="image_upload" class="w-full border border-[#EB891C] rounded-lg px-3 py-2 text-sm focus:ring-[#EB891C] focus:border-[#EB891C]">
                </div>
            </div>

            <div class="mb-5">
                <label for="publish_date" class="block font-semibold mb-2 text-[#0093DD]">Tanggal Publikasi <span class="text-red-500">*</span></label>
                <input type="date" name="publish_date" id="publish_date" class="w-full border border-[#0093DD] rounded-lg px-3 py-2 focus:ring-[#0093DD] focus:border-[#0093DD]" value="{{ old('publish_date') }}" required>
            </div>
            
            <div class="mb-5">
                <label for="author" class="block font-semibold mb-2 text-[#68B92E]">Penulis (opsional)</label>
                <input type="text" name="author" id="author" class="w-full border border-[#68B92E] rounded-lg px-3 py-2 focus:ring-[#68B92E] focus:border-[#68B92E]" value="{{ old('author') }}">
            </div>

            <div class="mb-8">
                <label for="source_url" class="block font-semibold mb-2 text-[#EB891C]">URL Sumber (opsional)</label>
                <input type="text" name="source_url" id="source_url" class="w-full border border-[#EB891C] rounded-lg px-3 py-2 focus:ring-[#EB891C] focus:border-[#EB891C]" value="{{ old('source_url') }}">
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-5 py-2 text-base font-semibold text-white bg-[#0093DD] rounded-full shadow-lg hover:bg-[#0070C0] transition">
                    Simpan
                </button>
                <a href="{{ route('admin.contents.index') }}" class="px-5 py-2 text-base font-semibold text-[#EB891C] bg-white border border-[#EB891C] rounded-full shadow-sm hover:bg-[#EB891C] hover:text-white transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>