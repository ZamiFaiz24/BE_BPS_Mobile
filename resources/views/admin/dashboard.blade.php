<x-app-layout>
    <div class="bg-gray-100 min-h-screen">
        <x-slot name="header">
            <div class="flex items-center gap-3">
                <div class="w-1 h-8 bg-gradient-to-b from-[#0093DD] to-[#0070AA] rounded-full"></div>
                <h2 class="font-semibold text-xl text-[#0093DD] leading-tight">
                    {{ __('Manajemen Dataset BPS') }}
                </h2>
            </div>    
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                {{-- Notifications --}}
                @if (session('status'))
                    <div x-data="{ show: true }" x-show="show"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         x-init="setTimeout(() => show = false, 5000)"
                         class="mb-6 p-4 bg-green-50 text-green-700 border-l-4 border-green-500 rounded-r-lg shadow-md flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">{{ session('status') }}</span>
                        </div>
                        <button @click="show = false" class="text-green-600 hover:text-green-800 transition flex-shrink-0">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                            </svg>
                        </button>
                    </div>
                @endif

                @if (session('error'))
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
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-red-600 hover:text-red-800 transition flex-shrink-0">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                            </svg>
                        </button>
                    </div>
                @endif

                {{-- Dynamic Sync Notifications --}}
                <div id="sync-notif"></div>
                
                {{-- Welcome Banner (Hanya muncul sekali per session) --}}
                @auth
                    <div x-data="{ 
                            show: false,
                            init() {
                                // Cek apakah banner sudah pernah ditampilkan di session ini
                                const bannerShown = sessionStorage.getItem('welcomeBannerShown');
                                if (!bannerShown) {
                                    this.show = true;
                                    sessionStorage.setItem('welcomeBannerShown', 'true');
                                    // Auto-hide setelah 5 detik
                                    setTimeout(() => this.show = false, 5000);
                                }
                            }
                         }" 
                         x-show="show"
                         x-cloak
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="mb-6 p-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-[#0093DD] rounded-r-lg shadow-md flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-[#0093DD] text-white shadow-lg flex-shrink-0">
                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                {{-- ROLE: SUPER ADMIN --}}
                                @if(auth()->user()->role === 'Super Admin')
                                    <p class="font-bold text-gray-900 text-base">
                                        Selamat datang kembali, {{ auth()->user()->name }}! 👋
                                    </p>
                                    <p class="text-gray-600 text-sm mt-1 leading-relaxed">
                                        Anda memegang <strong>kendali penuh</strong> atas sistem. Kelola pengguna, konfigurasi sinkronisasi data, serta validasi seluruh konten dan dataset statistik.
                                    </p>

                                {{-- ROLE: OPERATOR --}}
                                @elseif(auth()->user()->role === 'Operator')
                                    <p class="font-bold text-gray-900 text-base">
                                        Halo, {{ auth()->user()->name }}! Siap bekerja? 🚀
                                    </p>
                                    <p class="text-gray-600 text-sm mt-1 leading-relaxed">
                                        Fokus utama Anda adalah <strong>manajemen data harian</strong>. Silakan perbarui dataset statistik, publikasikan berita terbaru, dan kelola infografis.
                                    </p>

                                {{-- ROLE: LAINNYA / VIEWER --}}
                                @else
                                    <p class="font-bold text-gray-900 text-base">
                                        Selamat datang di Portal Admin, {{ auth()->user()->name }}.
                                    </p>
                                    <p class="text-gray-600 text-sm mt-1 leading-relaxed">
                                        Anda masuk dengan akses <strong>terbatas (View Only)</strong>. Anda dapat meninjau data yang tersedia. Hubungi Super Admin jika memerlukan akses edit.
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Tombol Tutup --}}
                        <button @click="show = false" class="text-gray-400 hover:text-gray-600 transition flex-shrink-0 focus:outline-none">
                            <span class="sr-only">Tutup</span>
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                            </svg>
                        </button>
                    </div>
                @endauth

                {{-- Statistics Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    {{-- Dataset Count --}}
                    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0 flex items-center justify-center h-14 w-14 rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 text-white shadow-md">
                                <x-heroicon-o-circle-stack class="w-8 h-8" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-500 mb-1">Dataset Tersimpan</p>
                                <p class="text-3xl font-bold text-gray-900">{{ number_format($datasetCount ?? 0) }}</p>
                                <p class="text-xs text-gray-400 mt-1">Total dataset BPS Kebumen</p>
                            </div>
                        </div>
                    </div>

                    {{-- Data Update --}}
                    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0 flex items-center justify-center h-14 w-14 rounded-xl bg-gradient-to-br from-green-500 to-green-600 text-white shadow-md">
                                <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-500 mb-1">Update Data</p>
                                @if(isset($lastSyncAddedCount) && $lastSyncAddedCount > 0)
                                    <p class="text-3xl font-bold text-green-600 flex items-center gap-1">
                                        <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd" />
                                        </svg>
                                        +{{ number_format($lastSyncAddedCount) }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">Total: {{ number_format($valueCount ?? 0) }} baris data</p>
                                @else
                                    <p class="text-3xl font-bold text-gray-900">{{ number_format($valueCount ?? 0) }}</p>
                                    <p class="text-xs text-gray-400 mt-1">Belum ada update terbaru</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Last Sync --}}
                    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 flex items-center justify-center h-14 w-14 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-md">
                                <x-heroicon-o-cloud-arrow-down class="w-8 h-8" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-500 mb-1">Sinkronisasi Terakhir</p>
                                <p class="text-xl font-bold text-gray-900 mb-1">{{ $lastSync ?? 'Belum pernah sinkronisasi' }}</p>
                                <p class="text-xs text-gray-400 mb-3">Perbarui data dari sumber BPS</p>
                                
                                @can('run sync')
                                    <button id="sync-btn" type="button"
                                        class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg shadow hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                        <x-heroicon-s-arrow-path class="w-4 h-4 mr-2" />
                                        Sinkronisasi Sekarang
                                    </button>
                                    <div id="sync-progress" class="mt-2 text-xs text-gray-600 hidden"></div>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Dataset Table Section --}}
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    {{-- Toolbar --}}
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex flex-wrap items-center gap-2">
                                {{-- Filter Button --}}
                                <button type="button" id="open-filter-btn"
                                    class="inline-flex items-center px-4 py-2 rounded-lg font-semibold shadow-sm text-white bg-[#0093DD] hover:bg-[#0070C0] focus:ring-2 focus:ring-[#0093DD] focus:ring-offset-2 transition">
                                    <x-heroicon-o-adjustments-horizontal class="w-5 h-5 mr-2" />
                                    Filter
                                </button>
                                
                                {{-- Sort Dropdown --}}
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" type="button"
                                        class="inline-flex items-center px-4 py-2 rounded-lg font-semibold shadow-sm text-white bg-green-600 hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4" />
                                        </svg>
                                        Urutkan
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-cloak
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute left-0 mt-2 w-64 bg-white rounded-lg shadow-xl z-20 border border-gray-200">
                                    <div class="py-1">
                                        <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Nama Dataset</div>
                                        <a href="?{{ http_build_query(array_merge(request()->except(['sort', 'order']), ['sort' => 'dataset_name', 'order' => 'asc'])) }}" 
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            A ke Z
                                        </a>
                                        <a href="?{{ http_build_query(array_merge(request()->except(['sort', 'order']), ['sort' => 'dataset_name', 'order' => 'desc'])) }}"
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Z ke A
                                        </a>
                                        
                                        <div class="border-t border-gray-200 my-1"></div>
                                        <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Subjek</div>
                                        <a href="?{{ http_build_query(array_merge(request()->except(['sort', 'order']), ['sort' => 'subject', 'order' => 'asc'])) }}"
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            A ke Z
                                        </a>
                                        <a href="?{{ http_build_query(array_merge(request()->except(['sort', 'order']), ['sort' => 'subject', 'order' => 'desc'])) }}"
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Z ke A
                                        </a>
                                        
                                        <div class="border-t border-gray-200 my-1"></div>
                                        <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Terakhir Diperbarui</div>
                                        <a href="?{{ http_build_query(array_merge(request()->except(['sort', 'order']), ['sort' => 'last_update', 'order' => 'desc'])) }}"
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Terbaru Dahulu
                                        </a>
                                        <a href="?{{ http_build_query(array_merge(request()->except(['sort', 'order']), ['sort' => 'last_update', 'order' => 'asc'])) }}"
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Terlama Dahulu
                                        </a>
                                        
                                        <div class="border-t border-gray-200 my-1"></div>
                                        <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Ditambahkan ke Sistem</div>
                                        <a href="?{{ http_build_query(array_merge(request()->except(['sort', 'order']), ['sort' => 'created_at', 'order' => 'desc'])) }}"
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Terbaru Dahulu
                                        </a>
                                        <a href="?{{ http_build_query(array_merge(request()->except(['sort', 'order']), ['sort' => 'created_at', 'order' => 'asc'])) }}"
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Terlama Dahulu
                                        </a>
                                    </div>
                                </div>
                            </div>

                                {{-- Reset Button --}}
                                @if(request('category') || request('subject') || request('sort') || request('q'))
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="inline-flex items-center px-4 py-2 text-white rounded-lg bg-orange-500 hover:bg-orange-600 font-semibold shadow-sm focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition">
                                        <x-heroicon-o-x-mark class="w-5 h-5 mr-2" />
                                        Reset Filter
                                    </a>
                                @endif
                            </div>

                            {{-- Search Form --}}
                            <form method="GET" action="{{ route('admin.dashboard') }}" class="relative">
                                <input type="text" id="search-input" name="q" value="{{ request('q') }}" 
                                    placeholder="Cari dataset..." 
                                    class="w-full md:w-72 pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD] transition">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                                </span>
                            </form>
                        </div>
                    </div>

                    {{-- INFO FILTER AKTIF --}}
                    @php
                        $subjectRaw = request('subject');
                        $categoryRaw = request('category');
                        $qRaw = request('q');

                        $subjectDisplay = is_array($subjectRaw) ? implode(', ', array_filter($subjectRaw)) : ($subjectRaw ?? '');
                        
                        $categoryDisplay = '';
                        if ($categoryRaw) {
                            $catIds = is_array($categoryRaw) ? $categoryRaw : [$categoryRaw];
                            $catNames = array_map(fn($id) => \App\Models\BpsDataset::CATEGORIES[$id] ?? 'Kode ' . $id, $catIds);
                            $categoryDisplay = implode(', ', $catNames);
                        }

                        $qDisplay = is_array($qRaw) ? implode(', ', array_filter($qRaw)) : ($qRaw ?? '');
                        
                        $hasActiveFilters = $categoryDisplay || $subjectDisplay || $qDisplay;
                    @endphp

                    @if($hasActiveFilters)
                        <div class="px-6 pt-4">
                            <div class="flex items-center flex-wrap gap-2 p-3 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg shadow-sm">
                                <svg class="w-5 h-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                <span class="text-sm font-semibold text-blue-700">Filter Aktif:</span>

                                @if($subjectDisplay)
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium bg-white text-gray-700 border border-gray-300 shadow-sm">
                                        <span class="text-gray-500">Subjek:</span>
                                        <span class="font-semibold">{{ $subjectDisplay }}</span>
                                    </span>
                                @endif

                                @if($categoryDisplay)
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium bg-white text-gray-700 border border-gray-300 shadow-sm">
                                        <span class="text-gray-500">Kategori:</span>
                                        <span class="font-semibold">{{ $categoryDisplay }}</span>
                                    </span>
                                @endif

                                @if($qDisplay)
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium bg-white text-gray-700 border border-gray-300 shadow-sm">
                                        <span class="text-gray-500">Pencarian:</span>
                                        <span class="font-semibold">"{{ $qDisplay }}"</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Filter Modal --}}
                    <x-modal-filter :categories="$categories" />

                    {{-- Dataset Table --}}
                    <div class="overflow-x-auto">
                        @include('admin.datasets.partials.table', ['datasets' => $datasets])
                    </div>

                    {{-- Pagination --}}
                    @include('admin.datasets.partials.pagination', ['datasets' => $datasets])
                </div>
            </div>
        </div>
    </div>

    {{-- Alpine.js --}}
    <script src="//unpkg.com/alpinejs" defer></script>

    {{-- JavaScript Functions --}}
    <script>
        // Utility: Debounce function
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        // Confirm Delete
        function confirmDelete(event) {
            event.preventDefault();
            if (confirm('Yakin ingin menghapus dataset ini? Semua data terkait juga akan dihapus!')) {
                event.target.submit();
            }
            return false;
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Filter Modal
            const openBtn = document.getElementById('open-filter-btn');
            const modal = document.getElementById('filter-modal');
            const closeBtn = document.getElementById('close-filter-btn');

            if (openBtn && modal) {
                openBtn.addEventListener('click', () => modal.classList.remove('hidden'));
            }
            if (closeBtn && modal) {
                closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
            }
            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) modal.classList.add('hidden');
                });
            }

            // Search with Debounce
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                searchInput.addEventListener('input', debounce(function() {
                    const container = document.getElementById('dataset-container');
                    if (!container) return;

                    const currentUrlParams = new URLSearchParams(window.location.search);
                    currentUrlParams.set('q', searchInput.value);

                    container.innerHTML = '<div class="text-center p-10 text-gray-500"><svg class="animate-spin h-8 w-8 mx-auto mb-3 text-[#0093DD]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Mencari dataset...</div>';
                    
                    fetch(`{{ route('admin.datasets.ajax-filter') }}?${currentUrlParams.toString()}`)
                        .then(response => response.text())
                        .then(html => {
                            container.innerHTML = html;
                            window.history.pushState({}, '', `{{ route('admin.dashboard') }}?${currentUrlParams.toString()}`);
                        })
                        .catch(error => {
                            container.innerHTML = '<div class="text-center p-10 text-red-500">Terjadi kesalahan saat mencari.</div>';
                            console.error('Search error:', error);
                        });
                }, 400));
            }

            // Insight Select Banner (if exists)
            const banner = document.getElementById('reminder-banner');
            const selects = document.querySelectorAll('.insight-select');
            if (banner && selects.length > 0) {
                let bannerIsVisible = false;
                selects.forEach(select => {
                    select.addEventListener('change', function () {
                        if (!bannerIsVisible) {
                            banner.classList.remove('hidden');
                            bannerIsVisible = true;
                        }
                    });
                });
            }
        });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const syncBtn = document.getElementById('sync-btn');
        const syncNotif = document.getElementById('sync-notif');
        const syncProgress = document.getElementById('sync-progress');
        let pollInterval = null;

        if (syncBtn) {
            syncBtn.addEventListener('click', function (e) {
                e.preventDefault();
                
                // Disable button
                syncBtn.innerHTML = '<svg class="animate-spin w-5 h-5 mr-2 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memulai...';
                syncBtn.disabled = true;

                // Trigger sync
                fetch('{{ route('admin.sync.manual') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Tampilkan notifikasi sukses trigger
                        syncNotif.innerHTML = `
                            <div class="mb-6 p-4 bg-blue-100 text-blue-800 border-l-4 border-blue-500 rounded-r-lg">
                                ${data.message}
                            </div>
                        `;
                        
                        // Mulai polling status
                        startPolling(data.log_id, data.check_url);
                    } else {
                        showError(data.message || 'Gagal memulai sinkronisasi');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('Terjadi kesalahan saat memulai sinkronisasi');
                });
            });
        }

        function startPolling(logId, checkUrl) {
            syncProgress.classList.remove('hidden');
            syncProgress.textContent = 'Sinkronisasi sedang berjalan...';

            pollInterval = setInterval(() => {
                fetch(checkUrl)
                    .then(response => response.json())
                    .then(data => {
                        // Update progress
                        syncProgress.textContent = `Progress: ${data.progress} dataset diproses...`;

                        // Jika selesai
                        if (!data.is_running) {
                            clearInterval(pollInterval);
                            
                            if (data.status === 'sukses') {
                                syncNotif.innerHTML = `
                                    <div class="mb-6 p-4 bg-green-100 text-green-800 border-l-4 border-green-500 rounded-r-lg">
                                        ✅ ${data.summary_message}
                                    </div>
                                `;
                                
                                // Reload setelah 2 detik untuk update statistik
                                setTimeout(() => window.location.reload(), 2000);
                            } else {
                                showError(data.summary_message || 'Sinkronisasi gagal');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Polling error:', error);
                        clearInterval(pollInterval);
                        showError('Gagal memeriksa status sinkronisasi');
                    });
            }, 3000); // Check every 3 seconds
        }

        function showError(message) {
            // Reset button dengan SVG icon yang benar
            syncBtn.innerHTML = '<svg class="w-5 h-5 mr-2 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z" clip-rule="evenodd" /></svg> Sinkronisasi';
            syncBtn.disabled = false;
            syncProgress.classList.add('hidden');
            
            syncNotif.innerHTML = `
                <div class="mb-6 p-4 bg-red-100 text-red-800 border-l-4 border-red-500 rounded-r-lg">
                    ${message}
                </div>
            `;
            
            // Auto-hide error after 5 seconds
            setTimeout(() => {
                syncNotif.innerHTML = '';
            }, 5000);
        }
    });
    </script>
</x-app-layout>