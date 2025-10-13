<x-app-layout>
    <div class="bg-gray-100 min-h-screen" x-data="filterModal()">
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Admin Dashboard') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                {{-- NOTIFIKASI --}}
                @if (session('status'))
                    <div class="mb-6 p-4 bg-green-100 text-green-800 border-l-4 border-green-500 rounded-r-lg">
                        {{ session('status') }}
                    </div>
                @endif
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
                    <div class="p-6 sm:px-10 border-b border-gray-200">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800">Manajemen Dataset BPS</h3>
                            </div>
                            {{-- Tombol Sinkronisasi Data (Biru) --}}
                            <a href="{{ route('admin.sync.all') }}"
                                class="mt-4 md:mt-0 inline-flex items-center px-4 py-2"
                                style="background-color: #0093DD; color: #fff;">
                                <x-heroicon-o-arrow-path class="w-4 h-4 mr-2" />
                                Sinkronisasi Data
                            </a>
                        </div>
                    </div>

                    {{-- FILTER & SEARCH + MODAL dibungkus x-data --}}
                    <div x-data="filterModal()" class="relative">
                        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex flex-wrap gap-2 items-center w-full md:w-auto">
                                {{-- Tombol Filter (Biru) --}}
                                <button type="button"
                                    class="inline-flex items-center px-4 py-2 text-white rounded-md text-sm font-medium transition"
                                    style="background-color: #0093DD;"
                                    @click="open = true">
                                    <x-heroicon-o-adjustments-horizontal class="w-5 h-5 mr-2" />
                                    Filter
                                </button>
                                {{-- Tombol Reset Filter (Oranye) --}}
                                @if(request('category') || request('subject'))
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium transition"
                                        style="background-color: #EB891C; color: #fff;">
                                        <x-heroicon-o-x-mark class="w-5 h-5 mr-2" />
                                        Reset Filter
                                    </a>
                                @endif
                                {{-- Search --}}
                                <div class="relative w-full md:w-64">
                                    <input type="text" name="q" id="search-dataset" value="{{ request('q') }}"
                                        placeholder="Cari Nama Dataset..."
                                        class="border border-gray-300 bg-gray-50 rounded pl-9 pr-3 py-2 text-sm w-full focus:ring-blue-500 focus:border-blue-500 transition"
                                        autocomplete="off" />
                                    <span class="absolute left-2 top-2.5 text-gray-400 pointer-events-none">
                                        <x-heroicon-o-magnifying-glass class="w-4 h-4" />
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Filter Tailwind + Alpine.js --}}
                        <div id="filterModal"
                             x-show="open"
                             x-cloak
                             class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
                            <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
                                <form method="GET" action="{{ route('admin.dashboard') }}">
                                    <div class="flex justify-between items-center border-b px-6 py-4">
                                        <h5 class="text-lg font-semibold">Filter Dataset</h5>
                                        <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600">
                                            <!-- svg close -->
                                        </button>
                                    </div>
                                    <div class="px-6 py-4">
                                        {{-- Kategori --}}
                                        <div class="mb-4">
                                            <label class="block mb-1 font-medium">Kategori</label>
                                            <template x-for="(subjects, cat) in categories" :key="cat">
                                                <div class="flex items-center mb-1">
                                                    <input type="radio" :id="'cat-'+cat" name="category" :value="cat"
                                                        x-model="selectedCategory"
                                                        class="form-radio text-blue-600 focus:ring-blue-500" />
                                                    <label :for="'cat-'+cat" class="ml-2" x-text="cat"></label>
                                                </div>
                                            </template>
                                        </div>
                                        {{-- Subject --}}
                                        <div class="mb-4" x-show="selectedCategory">
                                            <label class="block mb-1 font-medium">Subject</label>
                                            <template x-if="selectedCategory">
                                                <template x-for="subj in categories[selectedCategory]" :key="subj">
                                                    <div class="flex items-center mb-1">
                                                        <input type="radio" :id="'subj-'+subj" name="subject" :value="subj"
                                                            x-model="selectedSubject"
                                                            class="form-radio text-blue-600 focus:ring-blue-500" />
                                                        <label :for="'subj-'+subj" class="ml-2" x-text="subj"></label>
                                                    </div>
                                                </template>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="flex justify-end border-t px-6 py-4 gap-2">
                                        <button type="button" @click="
                                            selectedCategory = '';
                                            selectedSubject = '';
                                            $nextTick(() => { $el.closest('form').submit(); });
                                        "
                                        class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300">Reset</button>
                                        <button type="button" @click="open = false" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-700">Batal</button>
                                        {{-- Tombol Terapkan di Modal (Hijau) --}}
                                        <button type="submit"
                                            class="px-4 py-2 rounded"
                                            style="background-color: #68B92E; color: #fff;">
                                            Terapkan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- TABEL DATASET --}}
                    <div class="overflow-x-auto rounded-lg shadow">
                        @include('admin.datasets.partials.table', ['datasets' => $datasets])
                    </div>

                    <div class="mt-4">
                        {{ $datasets->links('vendor.pagination.tailwind') }}
                    </div>
                </div>

                {{-- BANNER PENGINGAT YANG TERSEMBUNYI --}}
                <div id="reminder-banner"
                    class="hidden fixed bottom-0 left-0 w-full bg-yellow-100 p-4 border-t-2 border-yellow-400">
                    <div class="max-w-7xl mx-auto text-center">
                        <span class="text-yellow-800 font-medium">
                            <x-heroicon-o-exclamation-triangle class="h-5 w-5 inline-block mr-1" />
                            Anda memiliki perubahan yang belum disimpan. Jangan lupa klik ikon centang (✓) untuk
                            menyimpannya.
                        </span>
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
            return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search-dataset');
            const subjectSelect = document.getElementById('subject');
            const perPageSelect = document.getElementById('per_page');
            const tableContainer = document.querySelector('.overflow-x-auto');

            function fetchTable() {
                const q = searchInput ? searchInput.value : '';
                const subject = subjectSelect ? subjectSelect.value : '';
                const per_page = perPageSelect ? perPageSelect.value : 10;

                fetch(`{{ route('admin.datasets.ajax-search') }}?q=${encodeURIComponent(q)}&subject=${encodeURIComponent(subject)}&per_page=${per_page}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        tableContainer.innerHTML = html;
                    });
            }

            if (searchInput) {
                searchInput.addEventListener('input', debounce(fetchTable, 400));
            }
            if (subjectSelect) {
                subjectSelect.addEventListener('change', fetchTable);
            }
            if (perPageSelect) {
                perPageSelect.addEventListener('change', fetchTable);
            }
        });
    </script>

    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
    function filterModal() {
        return {
            open: false,
            categories: @json(
                \App\Models\BpsDataset::all()
                    ->groupBy('category')
                    ->map(fn($g) => $g->pluck('subject')->unique()->values())
            ),
            selectedCategory: '{{ request('category') }}',
            selectedSubject: '{{ request('subject') }}',
            resetFilter() {
                this.selectedCategory = '';
                this.selectedSubject = '';
            }
        }
    }
    </script>

    <x-modal_fillter :categories="$categories" />
</x-app-layout>