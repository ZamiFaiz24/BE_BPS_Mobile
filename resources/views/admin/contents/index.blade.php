<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-1 h-8 bg-gradient-to-b from-[#0093DD] to-[#0070AA] rounded-full"></div>
            <h2 class="font-semibold text-xl text-[#0093DD] leading-tight">
                {{ __('Manajemen Konten') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- BAGIAN: KARTU STATISTIK KONTEN (di atas filter & tabel) --}}
            @php
                // Fallback: hitung dari masing-masing model jika $typeCounts belum disediakan controller
                $typeCounts = $typeCounts ?? [
                    'publication'   => \App\Models\Publication::count(),
                    'infographic'   => \App\Models\Infographic::count(),
                    'press_release' => \App\Models\PressRelease::count(),
                    'news'          => \App\Models\News::count(),
                ];
                $cards = [
                    ['key' => 'publication', 'label' => 'Publikasi', 'color' => '#EB891C', 'icon' => 'document-text'],
                    ['key' => 'infographic', 'label' => 'Infografik', 'color' => '#7C3AED', 'icon' => 'chart-bar'],
                    ['key' => 'press_release', 'label' => 'Siaran Pers', 'color' => '#68B92E', 'icon' => 'megaphone'],
                    ['key' => 'news', 'label' => 'Berita', 'color' => '#0093DD', 'icon' => 'newspaper'],
                ];
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                @foreach($cards as $c)
                    <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
                        <div class="flex-shrink-0 rounded-full p-3" style="background-color: {{ $c['color'] }};">
                            @switch($c['icon'])
                                @case('document-text')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16h8M8 12h8m-6 8h6a2 2 0 0 0 2-2V8.828a2 2 0 0 0-.586-1.414l-3.828-3.828A2 2 0 0 0 13.172 3H8a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2Z"/>
                                    </svg>
                                    @break
                                @case('chart-bar')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13h4v8H3v-8Zm7-6h4v14h-4V7Zm7 3h4v11h-4V10Z"/>
                                    </svg>
                                    @break
                                @case('megaphone')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 11 6.225-2.49A1 1 0 0 0 22 7.578V5.422a1 1 0 0 0-.775-.932L15 2M15 11v9a1 1 0 0 1-1.447.894L9 19H7a4 4 0 0 1-4-4v-1a4 4 0 0 1 4-4h2l4.553-1.106A1 1 0 0 1 15 11Zm0-9v9"/>
                                    </svg>
                                    @break
                                @case('newspaper')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5h12a2 2 0 0 1 2 2v11a1 1 0 0 0 1 1h1M4 5a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h13M8 10h6M8 14h6M6 10h.01M6 14h.01"/>
                                    </svg>
                                    @break
                            @endswitch
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">{{ $c['label'] }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $typeCounts[$c['key']] ?? 0 }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Menampilkan pesan sukses jika ada --}}
                    @if (session('success'))
                        <div class="bg-[#68B92E] bg-opacity-10 border border-[#68B92E] text-[#68B92E] px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    {{-- Menampilkan pesan error jika ada --}}
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form Filter --}}
                    <form method="GET" action="{{ route('admin.contents.index') }}" class="mb-6" id="filterForm">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            
                            {{-- Filter Tipe --}}
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Konten</label>
                                <select name="type" id="type" 
                                    class="w-full border border-[#0093DD] rounded-lg px-4 py-2 text-gray-700 focus:ring-2 focus:ring-[#0093DD] focus:border-transparent transition">
                                    <option value="">Semua Tipe</option>
                                    <option value="news" {{ request('type') == 'news' ? 'selected' : '' }}>Berita</option>
                                    <option value="press_release" {{ request('type') == 'press_release' ? 'selected' : '' }}>Siaran Pers</option>
                                    <option value="publication" {{ request('type') == 'publication' ? 'selected' : '' }}>Publikasi</option>
                                    <option value="infographic" {{ request('type') == 'infographic' ? 'selected' : '' }}>Infografik</option>
                                </select>
                            </div>

                            {{-- Filter Kategori --}}
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                                <input type="text" name="category" id="category" value="{{ request('category') }}" 
                                    placeholder="Cari kategori..." 
                                    class="w-full border border-[#0093DD] rounded-lg px-4 py-2 text-gray-700 focus:ring-2 focus:ring-[#0093DD] focus:border-transparent transition" />
                            </div>

                            {{-- Pencarian Judul --}}
                            <div>
                                <label for="q" class="block text-sm font-medium text-gray-700 mb-2">Cari Judul</label>
                                <input type="text" name="q" id="q" value="{{ request('q') }}" 
                                    placeholder="Cari judul konten..." 
                                    class="w-full border border-[#0093DD] rounded-lg px-4 py-2 text-gray-700 focus:ring-2 focus:ring-[#0093DD] focus:border-transparent transition" />
                            </div>

                            {{-- Urutkan --}}
                            <div>
                                <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                                <select name="sort" id="sort"
                                    class="w-full border border-[#0093DD] rounded-lg px-4 py-2 text-gray-700 focus:ring-2 focus:ring-[#0093DD] focus:border-transparent transition">
                                    <option value="publish_date_desc" {{ request('sort', 'publish_date_desc') == 'publish_date_desc' ? 'selected' : '' }}>
                                        Tanggal Rilis Terbaru
                                    </option>
                                    <option value="publish_date_asc" {{ request('sort') == 'publish_date_asc' ? 'selected' : '' }}>
                                        Tanggal Rilis Terlama
                                    </option>
                                </select>
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex gap-3 mt-4">
                            <button type="submit" 
                                class="inline-flex items-center gap-2 bg-[#0093DD] hover:bg-[#0070C0] text-white font-semibold px-6 py-2 rounded-lg shadow-md transition transform hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 5.5 5.5a7.5 7.5 0 0 0 11.15 11.15Z"/>
                                </svg>
                                Cari
                            </button>
                            @if(request('type') || request('q') || request('category'))
                                <a href="{{ route('admin.contents.index') }}" 
                                   class="inline-flex items-center gap-2 bg-[#EB891C] hover:bg-[#D67A15] text-white font-semibold px-6 py-2 rounded-lg shadow-md transition transform hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.5 12a7.5 7.5 0 0 1 12.62-5.3M19.5 12a7.5 7.5 0 0 1-12.62 5.3M12 7.5v4.875c0 .207.124.395.316.474l3.369 1.404"/>
                                    </svg>
                                    Reset Filter
                                </a>
                            @endif
                        </div>

                        {{-- Info Filter Aktif --}}
                        @if(request('type') || request('q') || request('category'))
                            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-sm text-gray-700">
                                    <span class="font-semibold">Filter aktif:</span>
                                    @if(request('type'))
                                        <span class="inline-block bg-[#0093DD] text-white px-2 py-1 rounded text-xs ml-2">
                                            Tipe: {{ ucfirst(str_replace('_', ' ', request('type'))) }}
                                        </span>
                                    @endif
                                    @if(request('category'))
                                        <span class="inline-block bg-[#68B92E] text-white px-2 py-1 rounded text-xs ml-2">
                                            Kategori: {{ request('category') }}
                                        </span>
                                    @endif
                                    @if(request('q'))
                                        <span class="inline-block bg-[#EB891C] text-white px-2 py-1 rounded text-xs ml-2">
                                            Judul: {{ request('q') }}
                                        </span>
                                    @endif
                                </p>
                            </div>
                        @endif
                    </form>

                    {{-- Tabel untuk menampilkan data --}}
                    <div id="content-container">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-[#0093DD]/20">
                                <thead style="background-color: #0093DD;">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Judul</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Tipe</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Kategori</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Tanggal</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Gambar</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-[#0093DD]/10">
                                    @php
                                        // Fallback sorting on current page (controller orderBy is recommended for global ordering)
                                        $items = $contents->getCollection();
                                        $items = $items->sortByDesc(function($c) {
                                            try { return $c->publish_date ? \Carbon\Carbon::parse($c->publish_date)->timestamp : 0; }
                                            catch (\Exception $e) { return 0; }
                                        });
                                        if (request('sort') === 'publish_date_asc') {
                                            $items = $items->reverse();
                                        }
                                    @endphp

                                    @forelse ($items as $content)
                                        <tr>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ Str::limit($content->title, 60) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @php
                                                    $typeConfig = match($content->type) {
                                                        'news' => ['label' => 'Berita', 'class' => 'bg-[#0093DD] bg-opacity-10 text-[#0093DD]'],
                                                        'press_release' => ['label' => 'Siaran Pers', 'class' => 'bg-[#68B92E] bg-opacity-10 text-[#68B92E]'],
                                                        'publication' => ['label' => 'Publikasi', 'class' => 'bg-[#EB891C] bg-opacity-10 text-[#EB891C]'],
                                                        'infographic' => ['label' => 'Infografik', 'class' => 'bg-purple-100 text-purple-800'],
                                                        default => ['label' => 'Lainnya', 'class' => 'bg-gray-100 text-gray-800']
                                                    };
                                                @endphp
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $typeConfig['class'] }}">
                                                    {{ $typeConfig['label'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                                {{ $content->category ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                                @php
                                                    try {
                                                        if ($content->publish_date) {
                                                            // Coba parse tanggal
                                                            $date = \Carbon\Carbon::parse($content->publish_date);
                                                            echo $date->translatedFormat('d M Y');
                                                        } else {
                                                            echo '-';
                                                        }
                                                    } catch (\Exception $e) {
                                                        // Jika gagal, tampilkan apa adanya
                                                        echo $content->publish_date ?? '-';
                                                    }
                                                @endphp
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @if ($content->image_url)
                                                    <img src="{{ $content->image_url }}" alt="thumb" class="h-10 w-16 object-cover rounded border border-[#0093DD]/30 mx-auto">
                                                @else
                                                    <span class="text-gray-400 text-xs">N/A</span>
                                                @endif
                                            </td>

                                            {{-- AKSI: ikon saja --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <div class="flex items-center justify-center gap-2">
                                                    @if($content->link)
                                                        <a href="{{ $content->link }}" target="_blank"
                                                           class="text-[#0093DD] hover:text-[#68B92E] inline-flex items-center justify-center h-9 w-9 rounded-md hover:bg-gray-50 transition"
                                                           title="Lihat" aria-label="Lihat">
                                                            <!-- Ganti ke ikon 'open in new' -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                                 viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                            </svg>
                                                        </a>
                                                    @endif
                                                    <form action="{{ route('admin.contents.destroy', $content->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus konten ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="type" value="{{ $content->type }}">
                                                        <button type="submit"
                                                                class="text-[#EB891C] hover:text-red-700 inline-flex items-center justify-center h-9 w-9 rounded-md hover:bg-gray-50 transition"
                                                                title="Hapus" aria-label="Hapus">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                                 viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                      d="M6 7h12M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2m1 0v12a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V7m3 4v6m4-6v6"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                                Belum ada data konten.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-4">
                            {{ $contents->appends(request()->query())->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT: Filter & Search Instan + AJAX Pagination --}}
    <script>
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('filterForm');
            const typeSelect = document.getElementById('type');
            const categoryInput = document.getElementById('category');
            const qInput = document.getElementById('q');
            const sortSelect = document.getElementById('sort');
            const container = document.getElementById('content-container');
            const baseUrl = "{{ route('admin.contents.index') }}";

            function setOrDelete(params, key, value) {
                if (value !== undefined && value !== null && String(value).trim() !== '') {
                    params.set(key, value);
                } else {
                    params.delete(key);
                }
            }

            function buildParams() {
                const params = new URLSearchParams(window.location.search);
                if (typeSelect) setOrDelete(params, 'type', typeSelect.value);
                if (categoryInput) setOrDelete(params, 'category', categoryInput.value);
                if (qInput) setOrDelete(params, 'q', qInput.value);
                if (sortSelect) setOrDelete(params, 'sort', sortSelect.value);
                return params;
            }

            function bindPagination() {
                const links = container.querySelectorAll('nav[role="navigation"] a, .pagination a, .pagination-links a');
                links.forEach(a => {
                    a.addEventListener('click', function (e) {
                        e.preventDefault();
                        const href = this.getAttribute('href');
                        if (href) performFilter(true, href);
                    });
                });
            }

            function performFilter(push = true, urlOverride = null) {
                const url = urlOverride || (baseUrl + '?' + buildParams().toString());
                container.innerHTML = '<div class="text-center p-10">Memuat...</div>';
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } })
                    .then(res => res.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newContainer = doc.querySelector('#content-container');
                        if (newContainer) {
                            container.innerHTML = newContainer.innerHTML;
                        } else {
                            // Fallback: tampilkan HTML apa adanya
                            container.innerHTML = html;
                        }
                        bindPagination();
                        if (push) window.history.pushState({}, '', url);
                    })
                    .catch(() => {
                        container.innerHTML = '<div class="text-center p-10 text-red-600">Gagal memuat data.</div>';
                    });
            }

            const debouncedFilter = debounce(() => performFilter(true), 400);

            // Cegah submit form default (biar tidak reload halaman)
            if (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    performFilter(true);
                });
            }

            // Trigger otomatis saat filter berubah
            if (typeSelect) typeSelect.addEventListener('change', () => performFilter(true));
            if (sortSelect) sortSelect.addEventListener('change', () => performFilter(true));
            if (qInput) qInput.addEventListener('input', debouncedFilter);
            if (categoryInput) categoryInput.addEventListener('input', debouncedFilter);

            // Intercept pagination on initial load
            bindPagination();

            // Handle back/forward browser buttons
            window.addEventListener('popstate', () => {
                performFilter(false, window.location.href);
            });
        });
    </script>
</x-app-layout>