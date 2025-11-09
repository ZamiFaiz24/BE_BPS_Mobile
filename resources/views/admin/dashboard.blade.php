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

                    {{-- Card 2: Total Baris Data (Hijau) --}}
                    <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
                        {{-- Container Ikon Disederhanakan --}}
                        <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-lg bg-green-600 text-white">
                            {{-- Menggunakan warna Tailwind yg mirip #68B92E --}}
                            <x-heroicon-o-document-text class="w-7 h-7" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Total Baris Data</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($valueCount) }}</p>

                            {{-- Server-side fallback: jika controller menyediakan jumlah yang ditambahkan saat sinkron terakhir --}}
                            @if(isset($lastSyncAddedCount) && $lastSyncAddedCount)
                                <p class="text-xs text-green-700 mt-1">+{{ number_format($lastSyncAddedCount) }} ditambahkan saat sinkron terakhir</p>
                            @endif

                            {{-- Element untuk update client-side (localStorage) --}}
                            <div id="last-added-info" class="text-xs text-green-700 mt-1 hidden"></div>
                        </div>
                    </div>

                    {{-- Card 3: Sinkronisasi Terakhir + Tombol --}}
                    <div class="bg-white p-6 rounded-lg shadow-md flex items-start">
                        {{-- Container Ikon Disederhanakan --}}
                        <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-lg bg-blue-600 text-white">
                            {{-- Menggunakan warna Tailwind yg mirip #0093DD --}}
                            {{-- Menggunakan ikon Heroicon 'cloud-arrow-down' agar konsisten --}}
                            <x-heroicon-o-cloud-arrow-down class="w-7 h-7" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Sinkronisasi Terakhir</p>
                            {{-- Ukuran font dibuat 'xl' agar tidak terlalu mendominasi tombol --}}
                            <p class="text-xl font-bold text-gray-900">{{ $lastSync }}</p> 
                            <p class="text-xs text-gray-400 mt-1">Perbarui data jika ada perubahan dari sumber.</p>
                            
                            {{-- Tombol dibuat 'rounded-md' agar konsisten dgn elemen UI lain (jika ada) --}}
                            <button id="sync-btn"
                                class="mt-4 inline-flex items-center px-4 py-2 text-white bg-green-600 rounded-md font-semibold shadow-sm hover:bg-green-700 transition text-sm">
                                {{-- Menggunakan ikon Heroicon 'arrow-path' (refresh) atau 'clock' (seperti di gambar) --}}
                                <x-heroicon-s-arrow-path class="w-5 h-5 mr-2" />
                                Sinkronisasi
                            </button>
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
                            {{-- Tombol Reset --}}
                            @if(request('category') || request('subject'))
                                <a href="{{ route('admin.dashboard') }}"
                                    class="inline-flex items-center px-4 py-2 text-[#EB891C] border-2 border-[#EB891C] rounded-full bg-white font-semibold shadow-sm hover:bg-[#EB891C] hover:text-white transition">
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

                    {{-- 2. KONTEN DINAMIS --}}
                    <div id="dataset-container">
                        @include('admin.datasets.partials.table-and-pagination', ['datasets' => $datasets])
                    </div>

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
        if (syncBtn) {
            syncBtn.addEventListener('click', function (e) {
                e.preventDefault();
                syncBtn.innerHTML = '<span class="animate-spin mr-2">&#8635;</span> Sinkronisasi...';
                syncBtn.disabled = true;

                fetch('{{ route('admin.sync.all') }}', {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                })
                .then(response => response.json())
                .then data => {
                    // Jika backend mengembalikan jumlah yang ditambahkan, simpan ke localStorage
                    if (data.added_count !== undefined) {
                        localStorage.setItem('lastAddedCount', data.added_count);
                    }
                    if (data.last_sync_time !== undefined) {
                        localStorage.setItem('lastSyncTime', data.last_sync_time);
                    }
                    // Simpan pesan sukses juga
                    localStorage.setItem('syncSuccess', data.message || 'Sinkronisasi berhasil!');
                    window.location.reload();
                })
                .catch(() => {
                    localStorage.setItem('syncError', 'Gagal sinkronisasi!');
                    window.location.reload();
                });
            });
        }

        // Tampilkan notifikasi jika ada pesan sukses
        if (localStorage.getItem('syncSuccess')) {
            const notif = document.getElementById('sync-notif');
            notif.innerHTML =
                '<div id="notif-banner" class="mb-6 p-4 bg-green-100 text-green-800 border-l-4 border-green-500 rounded-r-lg transition-opacity duration-500">' +
                localStorage.getItem('syncSuccess') +
                '</div>';
            localStorage.removeItem('syncSuccess');

            // Jika ada lastAddedCount, tampilkan sementara di Card 2
            const added = localStorage.getItem('lastAddedCount');
            const lastSyncTime = localStorage.getItem('lastSyncTime');
            if (added) {
                const el = document.getElementById('last-added-info');
                if (el) {
                    // tampilkan format yang singkat
                    el.textContent = `+${Number(added).toLocaleString()} ditambahkan${ lastSyncTime ? ' pada ' + lastSyncTime : ' saat sinkron terakhir' }`;
                    el.classList.remove('hidden');
                    // hapus setelah beberapa detik agar tidak permanen (server-side tetap menjadi sumber kebenaran)
                    setTimeout(() => { el.classList.add('hidden'); el.textContent = ''; }, 8000);
                }
                // lalu hapus item agar tidak tampil next reload
                localStorage.removeItem('lastAddedCount');
                localStorage.removeItem('lastSyncTime');
            }

            // Hilangkan notifikasi setelah 4 detik
            setTimeout(() => {
                const banner = document.getElementById('notif-banner');
                if (banner) {
                    banner.style.opacity = 0;
                    setTimeout(() => banner.remove(), 500);
                }
            }, 4000);
        }
        if (localStorage.getItem('syncError')) {
            alert(localStorage.getItem('syncError'));
            localStorage.removeItem('syncError');
        }
    });
    </script>
</x-app-layout>