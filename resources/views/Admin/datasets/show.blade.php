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
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-[#0093DD] rounded-md font-semibold text-xs text-[#0093DD] uppercase tracking-widest hover:bg-[#0093DD] hover:text-white transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-[#0093DD]">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Label Variabel</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Label Turunan</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Tahun</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Nilai</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Waktu Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($values as $value)
                                <tr class="{{ $loop->odd ? 'bg-white' : 'bg-[#F5FAFF]' }} hover:bg-[#E6F3FB] transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $value->vervar_label }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $value->turvar_label }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $value->year }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-block px-3 py-1 rounded-full bg-[#0093DD]/10 text-[#0093DD] font-bold text-sm">
                                            {{ $value->value }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                            <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        {{ $value->updated_at->translatedFormat('d M Y, H:i') }}
                                    </td>
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