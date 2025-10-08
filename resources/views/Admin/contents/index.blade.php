<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Konten') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Tombol untuk menambah konten baru --}}
                    <div class="mb-4">
                        <a href="{{ route('admin.contents.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Tambah Konten Baru
                        </a>
                    </div>

                    {{-- Menampilkan pesan sukses jika ada --}}
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    {{-- Form Filter --}}
                    <form method="GET" action="{{ route('admin.contents.index') }}" class="mb-4 flex flex-wrap gap-2 items-center" id="filterForm">
                        <select name="type" class="border rounded px-2 py-1"
                            onchange="document.getElementById('filterForm').submit()">
                            <option value="">Semua Kategori</option>
                            <option value="publikasi" {{ request('type') == 'publikasi' ? 'selected' : '' }}>Publikasi</option>
                            <option value="berita" {{ request('type') == 'berita' ? 'selected' : '' }}>Berita</option>
                            <option value="infografik" {{ request('type') == 'infografik' ? 'selected' : '' }}>Infografik</option>
                        </select>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul..." class="border rounded px-2 py-1" />
                        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Search</button>
                        @if(request('type') || request('q'))
                            <a href="{{ route('admin.contents.index') }}" class="ml-2 text-gray-600 underline">Reset</a>
                        @endif
                    </form>

                    {{-- Tabel untuk menampilkan data --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Terbit</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($contents as $content)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $content->title }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $typeClass = match($content->type) {
                                                    'berita' => 'bg-blue-100 text-blue-800',
                                                    'publikasi' => 'bg-green-100 text-green-800',
                                                    'infografik' => 'bg-yellow-100 text-yellow-800',
                                                    default => 'bg-gray-100 text-gray-800'
                                                };
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $typeClass }}">
                                                {{ ucfirst($content->type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($content->publish_date)->translatedFormat('d F Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($content->image_url)
                                                <img src="{{ asset('storage/' . $content->image_url) }}" alt="#" class="h-10 w-16 object-cover rounded">
                                            @else
                                                <span class="text-gray-400 text-xs">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.contents.edit', $content->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <form action="{{ route('admin.contents.destroy', $content->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus konten ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            Belum ada data konten.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Link Paginasi --}}
                    <div class="mt-4">
                        {{ $contents->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>