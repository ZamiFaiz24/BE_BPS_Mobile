<x-app-layout>
    {{-- Kita tambahkan background abu-abu muda di sini untuk seluruh halaman --}}
    <div class="bg-gray-100 min-h-screen">
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
                    {{-- Card 1: Dataset Dilacak --}}
                    <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 text-white rounded-full p-3">
                            <x-heroicon-o-circle-stack class="w-6 h-6" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Dataset Tersimpan</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $datasetCount }}</p>
                        </div>
                    </div>
                    {{-- Card 2: Total Baris Data --}}
                    <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
                        <div class="flex-shrink-0 bg-green-500 text-white rounded-full p-3">
                            <x-heroicon-o-document-text class="w-6 h-6" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Total Baris Data</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($valueCount) }}</p>
                        </div>
                    </div>
                    {{-- Card 3: Sinkronisasi Terakhir --}}
                    <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 text-white rounded-full p-3">
                            <x-heroicon-o-arrow-path class="w-6 h-6" />
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
                            <a href="{{ route('admin.fetch.bps.get') }}" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-lg">
                                <x-heroicon-o-arrow-path class="w-4 h-4 mr-2"/>
                                Sinkronisasi Data
                            </a>
                        </div>
                    </div>

                    {{-- TABEL DATASET --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Dataset</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe Insight</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($datasets as $dataset)
                                    <tr class="align-top">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $dataset->id }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-normal max-w-sm">{{ $dataset->dataset_name }}</td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <form id="form-{{ $dataset->id }}" action="{{ route('admin.datasets.update_insight', $dataset) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <select name="insight_type" class="insight-select block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                                    <option value="default" @selected($dataset->insight_type == 'default')>Default</option>
                                                    <option value="percent_lower_is_better" @selected($dataset->insight_type == 'percent_lower_is_better')>Persen (Turun=Baik)</option>
                                                    <option value="percent_higher_is_better" @selected($dataset->insight_type == 'percent_higher_is_better')>Persen (Naik=Baik)</option>
                                                    <option value="number_lower_is_better" @selected($dataset->insight_type == 'number_lower_is_better')>Angka (Turun=Baik)</option>
                                                    <option value="number_higher_is_better" @selected($dataset->insight_type == 'number_higher_is_better')>Angka (Naik=Baik)</option>
                                                </select>
                                            </form>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <button type="submit" form="form-{{ $dataset->id }}" class="p-2 rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" title="Simpan perubahan baris ini">
                                                    <x-heroicon-s-check
                                                    class="w-5 h-5" />
                                                </button>
                                                <a href="{{ route('admin.datasets.show', $dataset) }}" class="p-2 rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" title="Lihat Detail Data">
                                                    <x-heroicon-o-eye class="w-5 h-5" />
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-sm text-gray-500 text-center">Belum ada dataset.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- BANNER PENGINGAT YANG TERSEMBUNYI --}}
                <div id="reminder-banner" class="hidden fixed bottom-0 left-0 w-full bg-yellow-100 p-4 border-t-2 border-yellow-400">
                    <div class="max-w-7xl mx-auto text-center">
                        <span class="text-yellow-800 font-medium">
                            <x-heroicon-o-exclamation-triangle class="h-5 w-5 inline-block mr-1"/>
                            Anda memiliki perubahan yang belum disimpan. Jangan lupa klik ikon centang (✓) untuk menyimpannya.
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
</x-app-layout>