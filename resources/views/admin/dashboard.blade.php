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
                        <div class="flex-shrink-0" style="background-color: #EB891C;">
                            <div class="text-white rounded-full p-3">
                                <x-heroicon-o-circle-stack class="w-6 h-6" />
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Dataset Tersimpan</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $datasetCount }}</p>
                        </div>
                    </div>
                    {{-- Card 2: Total Baris Data (Hijau) --}}
                    <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
                        <div class="flex-shrink-0" style="background-color: #68B92E;">
                            <div class="text-white rounded-full p-3">
                                <x-heroicon-o-document-text class="w-6 h-6" />
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Total Baris Data</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($valueCount) }}</p>
                        </div>
                    </div>
                    {{-- Card 3: Sinkronisasi Terakhir (Biru) --}}
                    <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
                        <div class="flex-shrink-0" style="background-color: #0093DD;">
                            <div class="text-white rounded-full p-3">
                                <x-heroicon-o-arrow-path class="w-6 h-6" />
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Sinkronisasi Terakhir</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $lastSync }}</p>
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
                            {{-- Tombol Sinkronisasi --}}
                            <a href="#" id="sync-btn"
                               class="inline-flex items-center px-4 py-2 text-white bg-[#68B92E] rounded-full font-semibold shadow-sm hover:bg-[#4E8C1A] transition">
                               <x-heroicon-o-arrow-path class="w-5 h-5 mr-2" />
                               Sinkronisasi Data
                            </a>
                        </div>
                        {{-- Form Pencarian Sederhana --}}
                        <form method="GET" action="{{ route('admin.dashboard') }}" class="relative mt-4 md:mt-0">
                            <input type="text" id="search-input" name="q" value="{{ request('q') }}" placeholder="Cari Dataset..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-full focus:ring-[#0093DD] focus:border-[#0093DD] w-64">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><x-heroicon-o-magnifying-glass class="w-5 h-5" /></span>
                        </form>
                    </div>

                    {{-- PANGGIL KOMPONEN MODAL DI SINI --}}
                    <x-modal-filter :categories="$categories" />

                    {{-- 2. KONTENER KONTEN DINAMIS --}}
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
            const searchInput = document.getElementById('search-input'); // Target ID yang baru
            
            function performSearch() {
                const container = document.getElementById('dataset-container');
                const currentUrlParams = new URLSearchParams(window.location.search);
                currentUrlParams.set('q', searchInput.value); // Update parameter 'q'

                container.innerHTML = '<div class="text-center p-10">Mencari...</div>';
                
                // Panggil endpoint AJAX yang sama dengan modal filter
                fetch(`{{ route('admin.datasets.ajax-filter') }}?${currentUrlParams.toString()}`)
                    .then(response => response.text())
                    .then(html => {
                        container.innerHTML = html;
                        window.history.pushState({}, '', `{{ route('admin.dashboard') }}?${currentUrlParams.toString()}`);
                    });
            }

            // Tambahkan event listener dengan debounce
            searchInput.addEventListener('input', debounce(performSearch, 400));
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

                fetch('{{ route('admin.sync.all') }}', { method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'} })
                    .then(response => response.json())
                    .then(data => {
                        // Simpan pesan sukses ke localStorage
                        localStorage.setItem('syncSuccess', data.message || 'Sinkronisasi berhasil!');
                        window.location.reload();
                    })
                    .catch(() => {
                        localStorage.setItem('syncError', 'Gagal sinkronisasi!');
                        window.location.reload();
                    });
            });
        }

        // Tampilkan notifikasi jika ada pesan di localStorage
        if (localStorage.getItem('syncSuccess')) {
            const notif = document.getElementById('sync-notif');
            notif.innerHTML =
                '<div id="notif-banner" class="mb-6 p-4 bg-green-100 text-green-800 border-l-4 border-green-500 rounded-r-lg transition-opacity duration-500">'
                + localStorage.getItem('syncSuccess') +
                '</div>';
            localStorage.removeItem('syncSuccess');
            // Hilangkan notifikasi setelah 4 detik
            setTimeout(() => {
                const banner = document.getElementById('notif-banner');
                if (banner) {
                    banner.style.opacity = 0;
                    setTimeout(() => banner.remove(), 500); // Hapus dari DOM setelah animasi
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