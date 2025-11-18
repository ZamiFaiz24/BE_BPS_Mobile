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

                {{-- NOTIFIKASI --}}
                @if (session('status'))
                    <div class="mb-6 p-4 bg-green-100 text-green-800 border-l-4 border-green-500 rounded-r-lg">
                        {{ session('status') }}
                    </div>
                @endif
                <div id="sync-notif"></div>

                @if (session('error'))
                    <div class="mb-6 p-4 bg-red-100 text-red-800 border-l-4 border-red-500 rounded-r-lg">
                        {{ session('error') }}
                    </div>
                @endif
                
                {{-- BAGIAN 1: KARTU STATISTIK UTAMA --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                    {{-- Card 1: Dataset Tersimpan (Oranye) --}}
                    <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
                        {{-- Container Ikon Disederhanakan --}}
                        <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-lg bg-orange-500 text-white">
                            {{-- Menggunakan warna Tailwind yg mirip #EB891C --}}
                            <x-heroicon-o-circle-stack class="w-7 h-7" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Dataset Tersimpan</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $datasetCount }}</p>
                            <p class="text-xs text-gray-400 mt-1">Total dataset yang dikelola dari sumber BPS Kebumen.</p>
                        </div>
                    </div>

                    {{-- Card 2: Update Data (Pertumbuhan) --}}
                    <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
                        <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-lg bg-green-600 text-white">
                            <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Update Data</p>
                            @if(isset($lastSyncAddedCount) && $lastSyncAddedCount > 0)
                                <p class="text-2xl font-bold text-green-600 flex items-center gap-1">
                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd" />
                                    </svg>
                                    +{{ number_format($lastSyncAddedCount) }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">Total saat ini: {{ number_format($valueCount) }} baris</p>
                            @else
                                <p class="text-2xl font-bold text-gray-900">{{ number_format($valueCount) }}</p>
                                <p class="text-xs text-gray-400 mt-1">Belum ada update terbaru</p>
                            @endif
                        </div>
                    </div>

                    {{-- Card 3: Sinkronisasi Terakhir + Tombol --}}
                    <div class="bg-white p-6 rounded-lg shadow-md flex items-start">
                        <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-lg bg-blue-600 text-white">
                            <x-heroicon-o-cloud-arrow-down class="w-7 h-7" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Sinkronisasi Terakhir</p>
                            <p class="text-xl font-bold text-gray-900">{{ $lastSync }}</p> 
                            <p class="text-xs text-gray-400 mt-1">Perbarui data jika ada perubahan dari sumber.</p>
                            
                            @can('run sync')
                                {{-- GANTI FORM DENGAN BUTTON --}}
                                <button id="sync-btn" type="button"
                                    class="mt-4 inline-flex items-center px-4 py-2 text-white bg-green-600 rounded-md font-semibold shadow-sm hover:bg-green-700 transition text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                    <x-heroicon-s-arrow-path class="w-5 h-5 mr-2" />
                                    Sinkronisasi
                                </button>
                                <div id="sync-progress" class="mt-2 text-xs text-gray-600 hidden"></div>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex flex-wrap items-center gap-2">
                            {{-- Tombol Filter --}}
                            <button type="button" id="open-filter-btn"
                                class="inline-flex items-center px-4 py-2 rounded-full font-semibold shadow-sm text-white bg-[#0093DD] hover:bg-[#0070C0] transition">
                                <x-heroicon-o-adjustments-horizontal class="w-5 h-5 mr-2" />
                                Filter
                            </button>
                            
                            {{-- Dropdown Sorting --}}
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" type="button"
                                    class="inline-flex items-center px-4 py-2 rounded-full font-semibold shadow-sm text-white bg-green-600 border border-gray-300 hover:bg-green-800 transition">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4" />
                                    </svg>
                                    Urutkan
                                </button>
                                <div x-show="open" @click.away="open = false" x-cloak
                                    class="absolute left-0 mt-2 w-64 bg-white rounded-md shadow-lg z-10 border border-gray-200">
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

                            {{-- Tombol Reset --}}
                            @if(request('category') || request('subject') || request('sort') || request('q'))
                                <a href="{{ route('admin.dashboard') }}"
                                    class="inline-flex items-center px-4 py-2 text-white border-2 border-gray-300 rounded-full bg-[#EB891C] font-semibold shadow-sm hover:bg-[#EB891C] transition">
                                    <x-heroicon-o-x-mark class="w-5 h-5 mr-2" />
                                    Reset
                                </a>
                            @endif
                        </div>
                        {{-- Form Pencarian Sederhana --}}
                        <form method="GET" action="{{ route('admin.dashboard') }}" class="relative mt-4 md:mt-0">
                            <input type="text" id="search-input" name="q" value="{{ request('q') }}" placeholder="Cari Dataset..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-full focus:ring-[#0093DD] focus:border-[#0093DD] w-64">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><x-heroicon-o-magnifying-glass class="w-5 h-5" /></span>
                        </form>
                    </div>

                    {{-- INFO FILTER AKTIF --}}
                    @php
                        $subjectRaw = request('subject');
                        $categoryRaw = request('category');
                        $qRaw = request('q');

                        $subjectDisplay = is_array($subjectRaw) ? implode(', ', array_filter($subjectRaw)) : ($subjectRaw ?? '');
                        $categoryDisplay = is_array($categoryRaw) ? implode(', ', array_filter($categoryRaw)) : ($categoryRaw ?? '');
                        $qDisplay = is_array($qRaw) ? implode(', ', array_filter($qRaw)) : ($qRaw ?? '');
                    @endphp
                    <div class="px-6 pt-4">
                        @if($categoryDisplay || $subjectDisplay || $qDisplay)
                            <div id="active-filters">
                                <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                    <p class="text-sm text-gray-700">
                                        <span class="font-semibold">Filter aktif:</span>
                                        @if($subjectDisplay)
                                            <span class="inline-block bg-[#0093DD] text-white px-2 py-1 rounded text-xs ml-2">
                                                Subjek: {{ $subjectDisplay }}
                                            </span>
                                        @endif
                                        @if($categoryDisplay)
                                            <span class="inline-block bg-[#68B92E] text-white px-2 py-1 rounded text-xs ml-2">
                                                Kategori: {{ $categoryDisplay }}
                                            </span>
                                        @endif
                                        @if($qDisplay)
                                            <span class="inline-block bg-[#EB891C] text-white px-2 py-1 rounded text-xs ml-2">
                                                Cari: {{ $qDisplay }}
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @else
                            <div id="active-filters" class="hidden"></div>
                        @endif
                    </div>

                    {{-- PANGGIL KOMPONEN MODAL DI SINI --}}
                    <x-modal-filter :categories="$categories" />

                    <div class="mt-4 overflow-x-auto">
                    @include('admin.datasets.partials.table', ['datasets' => $datasets])
                    </div>

                    @include('admin.datasets.partials.pagination', ['datasets' => $datasets])
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT UNTUK MEMUNCULKAN BANNER PENGINGAT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const banner = document.getElementById('reminder-banner');
            const selects = document.querySelectorAll('.insight-select');
            let bannerIsVisible = false;

            selects.forEach(select => {
                select.addEventListener('change', function () {
                    // Hanya tampilkan banner jika belum terlihat
                    if (!bannerIsVisible) {
                        banner.classList.remove('hidden');
                        bannerIsVisible = true;
                    }
                });
            });
        });
    </script>

    <script>
        function confirmDelete(event) {
            event.preventDefault();
            if (confirm('Yakin ingin menghapus dataset ini? Semua data terkait juga akan dihapus!')) {
                event.target.submit();
            }
            return false;
        }
    </script>

    <script>
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const activeFiltersEl = document.getElementById('active-filters');

            function escapeHtml(str) {
                return String(str).replace(/[&<>"']/g, function (s) {
                    switch (s) {
                        case '&': return '&amp;';
                        case '<': return '&lt;';
                        case '>': return '&gt;';
                        case '"': return '&quot;';
                        case "'": return '&#39;';
                        default: return s;
                    }
                });
            }

            function renderActiveFilters(params) {
                if (!activeFiltersEl) return;

                const collect = (name) => params.getAll(name);
                const subjectVals = collect('subject[]').concat(collect('subject'));
                const categoryVals = collect('category[]').concat(collect('category'));

                const subject = subjectVals.filter(v => v && v.trim() !== '').join(', ');
                const category = categoryVals.filter(v => v && v.trim() !== '').join(', ');
                const q = (params.get('q') || '').trim();

                const hasAny = subject || category || q;

                if (!hasAny) {
                    activeFiltersEl.classList.add('hidden');
                    activeFiltersEl.innerHTML = '';
                    return;
                }

                activeFiltersEl.classList.remove('hidden');
                activeFiltersEl.innerHTML = `
                    <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-gray-700">
                            <span class="font-semibold">Filter aktif:</span>
                            ${subject ? `<span class="inline-block bg-[#0093DD] text-white px-2 py-1 rounded text-xs ml-2">Subjek: ${escapeHtml(subject)}</span>` : ``}
                            ${category ? `<span class="inline-block bg-[#68B92E] text-white px-2 py-1 rounded text-xs ml-2">Kategori: ${escapeHtml(category)}</span>` : ``}
                            ${q ? `<span class="inline-block bg-[#EB891C] text-white px-2 py-1 rounded text-xs ml-2">Cari: ${escapeHtml(q)}</span>` : ``}
                        </p>
                    </div>
                `;
            }

            function performSearch() {
                const container = document.getElementById('dataset-container');
                const currentUrlParams = new URLSearchParams(window.location.search);
                currentUrlParams.set('q', searchInput.value);

                container.innerHTML = '<div class="text-center p-10">Mencari...</div>';
                fetch(`{{ route('admin.datasets.ajax-filter') }}?${currentUrlParams.toString()}`)
                    .then(response => response.text())
                    .then(html => {
                        container.innerHTML = html;
                        window.history.pushState({}, '', `{{ route('admin.dashboard') }}?${currentUrlParams.toString()}`);
                        renderActiveFilters(currentUrlParams);
                    });
            }

            searchInput.addEventListener('input', debounce(performSearch, 400));
            renderActiveFilters(new URLSearchParams(window.location.search));
            window.addEventListener('popstate', () => {
                renderActiveFilters(new URLSearchParams(window.location.search));
            });
        });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const openBtn = document.getElementById('open-filter-btn');
        const modal = document.getElementById('filter-modal');
        const closeBtn = document.getElementById('close-filter-btn');

        if (openBtn && modal) {
            openBtn.addEventListener('click', function () {
                modal.classList.remove('hidden');
            });
        }
        if (closeBtn && modal) {
            closeBtn.addEventListener('click', function () {
                modal.classList.add('hidden');
            });
        }
        // Optional: klik di luar modal untuk menutup
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
    </script>

    <script src="//unpkg.com/alpinejs" defer></script>

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