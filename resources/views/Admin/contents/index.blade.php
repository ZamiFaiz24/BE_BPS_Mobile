<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#0093DD] leading-tight">
            {{ __('Manajemen Konten') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Tombol untuk menambah konten baru --}}
                    <div class="mb-4">
                        <a href="{{ route('admin.contents.create') }}" class="bg-[#0093DD] hover:bg-[#0070C0] text-white font-bold py-2 px-4 rounded shadow transition">
                            + Tambah Konten Baru
                        </a>
                    </div>

                    {{-- Menampilkan pesan sukses jika ada --}}
                    @if (session('success'))
                        <div class="bg-[#68B92E] bg-opacity-10 border border-[#68B92E] text-[#68B92E] px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    {{-- Form Filter --}}
                    <form method="GET" action="{{ route('admin.contents.index') }}" class="mb-4 flex flex-wrap gap-2 items-center" id="filterForm">
                        <select name="type" class="border border-[#0093DD] rounded px-2 py-1 text-[#0093DD] focus:ring-[#0093DD] focus:border-[#0093DD]"
                            onchange="document.getElementById('filterForm').submit()">
                            <option value="">Semua Kategori</option>
                            <option value="publikasi" {{ request('type') == 'publikasi' ? 'selected' : '' }}>Publikasi</option>
                            <option value="berita" {{ request('type') == 'berita' ? 'selected' : '' }}>Berita</option>
                            <option value="infografik" {{ request('type') == 'infografik' ? 'selected' : '' }}>Infografik</option>
                        </select>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul..." class="border border-[#0093DD] rounded px-2 py-1 focus:ring-[#0093DD] focus:border-[#0093DD]" />
                        <button type="submit" class="bg-[#0093DD] hover:bg-[#0070C0] text-white px-3 py-1 rounded shadow transition">Search</button>
                        @if(request('type') || request('q'))
                            <a href="{{ route('admin.contents.index') }}" class="ml-2 text-[#EB891C] underline hover:text-[#0093DD]">Reset</a>
                        @endif
                    </form>

                    {{-- Tabel untuk menampilkan data --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-[#0093DD]/20">
                            <thead style="background-color: #0093DD;">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Judul</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Tipe</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Tanggal Terbit</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Gambar</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-[#0093DD]/10">
                                @forelse ($contents as $content)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $content->title }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $typeClass = match($content->type) {
                                                    'berita' => 'bg-[#0093DD] bg-opacity-10 text-[#0093DD]',
                                                    'publikasi' => 'bg-[#68B92E] bg-opacity-10 text-[#68B92E]',
                                                    'infografik' => 'bg-[#EB891C] bg-opacity-10 text-[#EB891C]',
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
                                                <img src="{{ asset('storage/' . $content->image_url) }}" alt="#" class="h-10 w-16 object-cover rounded border border-[#0093DD]/30">
                                            @else
                                                <span class="text-gray-400 text-xs">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.contents.edit', $content->id) }}" class="text-[#0093DD] hover:text-[#68B92E] font-semibold">Edit</a>
                                            <form action="{{ route('admin.contents.destroy', $content->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus konten ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-[#EB891C] hover:text-red-700 ml-4 font-semibold">Hapus</button>
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