<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-1 h-8 bg-gradient-to-b from-[#0093DD] to-[#0070AA] rounded-full"></div>
                <h2 class="font-semibold text-xl text-[#0093DD] leading-tight">
                    {{ __('Manajemen Konten') }}
                </h2>
            </div>
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
                        <div id="success-alert" 
                            class="bg-[#68B92E] bg-opacity-10 border-l-4 border-[#68B92E] text-[#68B92E] px-4 py-3 rounded-r relative mb-4 shadow-md flex items-center justify-between transition-all duration-500 ease-in-out" 
                            role="alert"
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 transform translate-y-0"
                            x-transition:leave-end="opacity-0 transform -translate-y-2"
                            x-init="setTimeout(() => show = false, 5000)">
                            <div class="flex items-center gap-3">
                                <svg class="w-6 h-6 text-[#68B92E]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium">{{ session('success') }}</span>
                            </div>
                            <button @click="show = false" class="text-[#68B92E] hover:text-[#4E8C1A] transition">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                                </svg>
                            </button>
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

                    {{-- Form Filter (toolbar rapi) --}}
                    <form method="GET" action="{{ route('admin.contents.index') }}" class="mb-6" id="filterForm">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            {{-- Left controls --}}
                            <div class="flex flex-1 flex-wrap items-center gap-3">
                                {{-- Filter Tipe --}}
                                <div>
                                    <label for="type" class="block text-xs font-medium text-gray-600 mb-1">Tipe</label>
                                    <select name="type" id="type"
                                        class="w-44 md:w-48 border border-[#0093DD] rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-[#0093DD] focus:border-transparent transition">
                                        <option value="">Semua</option>
                                        <option value="news" {{ request('type') == 'news' ? 'selected' : '' }}>Berita</option>
                                        <option value="press_release" {{ request('type') == 'press_release' ? 'selected' : '' }}>Siaran Pers</option>
                                        <option value="publication" {{ request('type') == 'publication' ? 'selected' : '' }}>Publikasi</option>
                                        <option value="infographic" {{ request('type') == 'infographic' ? 'selected' : '' }}>Infografik</option>
                                    </select>
                                </div>

                                {{-- Pencarian Judul --}}
                                <div class="min-w-[220px] md:min-w-[260px]">
                                    <label for="q" class="block text-xs font-medium text-gray-600 mb-1">Cari Judul</label>
                                    <input type="text" name="q" id="q" value="{{ request('q') }}"
                                        placeholder="Ketik untuk mencari..."
                                        class="w-full border border-[#0093DD] rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-[#0093DD] focus:border-transparent transition" />
                                </div>

                                {{-- Urutkan --}}
                                <div>
                                    <label for="sort" class="block text-xs font-medium text-gray-600 mb-1">Urutkan</label>
                                    <select name="sort" id="sort"
                                        class="w-44 md:w-48 border border-[#0093DD] rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-[#0093DD] focus:border-transparent transition">
                                        <option value="publish_date_desc" {{ request('sort', 'publish_date_desc') == 'publish_date_desc' ? 'selected' : '' }}>
                                            Tanggal Rilis Terbaru
                                        </option>
                                        <option value="publish_date_asc" {{ request('sort') == 'publish_date_asc' ? 'selected' : '' }}>
                                            Tanggal Rilis Terlama
                                        </option>
                                    </select>
                                </div>

                                {{-- Reset (opsional) --}}
                                @if(request('type') || request('q'))
                                    <a href="{{ route('admin.contents.index') }}"
                                       class="inline-flex items-center text-xs md:text-sm px-3 py-2 text-[#EB891C] border border-[#EB891C] rounded-lg bg-white font-semibold shadow-sm hover:bg-[#EB891C] hover:text-white transition">
                                        Reset
                                    </a>
                                @endif
                            </div>

                            {{-- Right: Tambah Konten --}}
                            <div class="flex-shrink-0">
                                <a href="{{ route('admin.contents.create', request()->only(['type', 'category', 'q', 'sort', 'page', 'per_page'])) }}"
                                   class="inline-flex items-center gap-2 bg-[#68B92E] hover:bg-[#4E8C1A] text-white font-semibold px-4 py-2 rounded-lg shadow-sm transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Tambah Konten
                                </a>
                            </div>
                        </div>

                        {{-- Info Filter Aktif --}}
                        @if(request('type') || request('q'))
                            <div class="mt-4 flex items-center flex-wrap gap-2 p-3 bg-white border border-gray-200 rounded-lg shadow-sm">
                                <span class="text-sm font-medium text-gray-500 mr-1">Filter aktif:</span>

                                {{-- BADGE TIPE --}}
                                @if(request('type'))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-300">
                                        <span class="mr-1 text-gray-400">Tipe:</span>
                                        {{ ucfirst(str_replace('_', ' ', request('type'))) }}
                                    </span>
                                @endif

                                {{-- BADGE PENCARIAN --}}
                                @if(request('q'))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-300">
                                        <span class="mr-1 text-gray-400">Cari:</span>
                                        "{{ request('q') }}"
                                    </span>
                                @endif
                            </div>
                        @endif
                    </form>

                    {{-- Tabel untuk menampilkan data --}}
                    <div id="content-container">
    
                        {{-- 1. Panggil Tabel (Cukup satu baris ini saja) --}}
                        <div class="mt-4 overflow-x-auto">
                            @include('admin.contents.partials.table', ['contents' => $contents])
                        </div>

                        {{-- 2. Panggil Pagination --}}
                        @include('admin.contents.partials.pagination', ['contents' => $contents])

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT: Filter & Search Instan + AJAX Pagination (tanpa kategori) --}}
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

            if (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    performFilter(true);
                });
            }

            if (typeSelect) typeSelect.addEventListener('change', () => performFilter(true));
            if (sortSelect) sortSelect.addEventListener('change', () => performFilter(true));
            if (qInput) qInput.addEventListener('input', debouncedFilter);

            bindPagination();

            window.addEventListener('popstate', () => {
                performFilter(false, window.location.href);
            });
        });
    </script>
</x-app-layout>