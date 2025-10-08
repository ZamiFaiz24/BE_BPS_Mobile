<x-app-layout>
    <x-slot name="header">
        {{-- Judul halaman dinamis --}}
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Data: {{ $dataset->dataset_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                {{-- Tombol untuk kembali ke dashboard --}}
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    &larr; Kembali ke Dashboard
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Label Variabel</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Label Turunan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Update</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($values as $value)
                                <tr>
                                    {{-- Menampilkan label jika ada, agar lebih informatif --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $value->vervar_label }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $value->turvar_label }}</td>
                                    
                                    {{-- Ganti $value->tahun menjadi $value->year --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $value->year }}</td>
                                    
                                    {{-- Ganti $value->nilai menjadi $value->value --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-semibold">{{ $value->value }}</td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $value->updated_at->translatedFormat('d M Y, H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Belum ada data nilai untuk dataset ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>