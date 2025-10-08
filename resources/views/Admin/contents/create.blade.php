<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Konten') }}
        </h2>
    </x-slot>

    {{-- 1. Tambahkan state 'uploadMethod' ke Alpine.js --}}
    <div class="max-w-xl mx-auto mt-8 bg-white p-6 rounded shadow" x-data="{ type: '{{ old('type', '') }}', uploadMethod: '{{ old('upload_method', 'url') }}' }">
        <h2 class="text-2xl font-bold mb-4">Tambah Konten Baru</h2>
        
        {{-- Menampilkan error validasi jika ada --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline">Ada beberapa masalah dengan input Anda.</span>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 2. PENTING: Tambahkan enctype="multipart/form-data" untuk upload file --}}
        <form action="{{ route('admin.contents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Bagian Umum (Untuk Semua Tipe) --}}
            <div class="mb-4">
                <label for="type" class="block font-semibold mb-1">Tipe Konten <span class="text-red-500">*</span></label>
                <select name="type" id="type" class="w-full border rounded px-3 py-2" x-model="type" required>
                    <option value="">-- Pilih Tipe --</option>
                    <option value="berita">Berita</option>
                    <option value="publikasi">Publikasi</option>
                    <option value="infografik">Infografik</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="title" class="block font-semibold mb-1">Judul <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" class="w-full border rounded px-3 py-2" value="{{ old('title') }}" required>
            </div>

            {{-- Bagian Dinamis (Sesuai Tipe yang Dipilih) --}}
            
            <div class="mb-4" x-show="type === 'berita' || type === 'publikasi'" x-cloak>
                <label for="description" class="block font-semibold mb-1">Deskripsi / Abstraksi</label>
                <textarea name="description" id="description" rows="4" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
            </div>

            <div class="mb-4" x-show="type === 'berita'" x-cloak>
                <label for="content_body" class="block font-semibold mb-1">Isi Konten Lengkap</label>
                <textarea name="content_body" id="content_body" rows="8" class="w-full border rounded px-3 py-2">{{ old('content_body') }}</textarea>
            </div>

            <div class="mb-4" x-show="type === 'publikasi'" x-cloak>
                <label for="file_url" class="block font-semibold mb-1">URL File (PDF)</label>
                <input type="text" name="file_url" id="file_url" class="w-full border rounded px-3 py-2" value="{{ old('file_url') }}" placeholder="Tempel link unduh publikasi di sini...">
            </div>

            {{-- 3. BAGIAN BARU: OPSI INPUT GAMBAR (Hanya muncul untuk Publikasi & Infografik) --}}
            <div class="mb-4 p-4 border rounded bg-gray-50" x-show="type === 'publikasi' || type === 'infografik'" x-cloak>
                <label class="block font-semibold mb-2">Input Gambar</label>
                
                {{-- Pilihan Metode --}}
                <div class="flex items-center space-x-4 mb-3">
                    <label class="flex items-center">
                        <input type="radio" name="upload_method" value="url" x-model="uploadMethod" class="form-radio">
                        <span class="ml-2 text-sm">Dari URL</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="upload_method" value="upload" x-model="uploadMethod" class="form-radio">
                        <span class="ml-2 text-sm">Upload File</span>
                    </label>
                </div>

                {{-- Input untuk metode URL --}}
                <div x-show="uploadMethod === 'url'" x-cloak>
                    <label for="image_source_url" class="block font-semibold mb-1 text-sm">URL Gambar Sumber</label>
                    <input type="text" name="image_source_url" id="image_source_url" class="w-full border rounded px-3 py-2 text-sm" value="{{ old('image_source_url') }}" placeholder="Tempel link gambar di sini...">
                </div>
    
                {{-- Input untuk metode Upload File --}}
                <div x-show="uploadMethod === 'upload'" x-cloak>
                    <label for="image_upload" class="block font-semibold mb-1 text-sm">Pilih File Gambar</label>
                    <input type="file" name="image_upload" id="image_upload" class="w-full border rounded px-3 py-2 text-sm">
                </div>
            </div>


            {{-- Bagian Opsional (Untuk Semua Tipe) --}}
            <div class="mb-4">
                <label for="publish_date" class="block font-semibold mb-1">Tanggal Publikasi <span class="text-red-500">*</span></label>
                <input type="date" name="publish_date" id="publish_date" class="w-full border rounded px-3 py-2" value="{{ old('publish_date') }}" required>
            </div>
            
            <div class="mb-4">
                <label for="author" class="block font-semibold mb-1">Penulis (opsional)</label>
                <input type="text" name="author" id="author" class="w-full border rounded px-3 py-2" value="{{ old('author') }}">
            </div>

            <div class="mb-4">
                <label for="source_url" class="block font-semibold mb-1">URL Sumber (opsional)</label>
                <input type="text" name="source_url" id="source_url" class="w-full border rounded px-3 py-2" value="{{ old('source_url') }}">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
            <a href="{{ route('admin.contents.index') }}" class="ml-2 text-gray-600 hover:underline">Batal</a>
        </form>
    </div>
</x-app-layout>