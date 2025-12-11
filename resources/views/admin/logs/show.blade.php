<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Log Sinkronisasi #{{ $log->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Tombol kembali --}}
            <div class="mb-6">
                <a href="{{ route('admin.logs.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-white border border-[#0093DD] rounded-md font-semibold text-xs text-[#0093DD] uppercase tracking-widest hover:bg-[#0093DD] hover:text-white transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Kembali
                </a>
            </div>
            
            <!-- Card Ringkasan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Log #{{ $log->id }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <dt class="text-gray-500 font-medium">Status</dt>
                            <dd class="mt-1 font-semibold">
                                @if ($log->status == 'sukses')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        ✅ Sukses
                                    </span>
                                @elseif ($log->status == 'gagal')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        🚨 Gagal
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        ⏳ Berjalan
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 font-medium">Dijalankan Oleh</dt>
                            <dd class="mt-1 text-gray-900">{{ $log->user->name ?? 'Sistem (Otomatis)' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 font-medium">Durasi</dt>
                            <dd class="mt-1 text-gray-900">
                                @if ($log->finished_at)
                                    {{ \Carbon\Carbon::parse($log->started_at)->diffForHumans(\Carbon\Carbon::parse($log->finished_at), true) }}
                                @else
                                    Masih berjalan...
                                @endif
                            </dd>
                        </div>
                        <div class="md:col-span-3">
                            <dt class="text-gray-500 font-medium">Pesan Ringkasan</dt>
                            <dd class="mt-1 text-gray-900 bg-gray-50 p-3 rounded-md">
                                {{ $log->summary_message ?? '-' }}
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Rincian Proses -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Rincian Proses ({{ $log->details->count() }} item)</h3>
                    <div class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full divide-y divide-[#0093DD]/20">
                            <thead style="background-color: #0093DD;">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Judul Dataset</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Tindakan</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Pesan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-[#0093DD]/10">
                                @forelse ($log->details as $detail)
                                    @php
                                        // Cari dataset berdasarkan nama
                                        $dataset = \App\Models\BpsDataset::where('dataset_name', $detail->dataset_title)->first();
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                            @if($dataset)
                                                <a href="{{ route('admin.datasets.show', $dataset->id) }}" 
                                                   class="text-[#0093DD] hover:text-[#0077B6] hover:underline flex items-center gap-2">
                                                    {{ $detail->dataset_title }}
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                    </svg>
                                                </a>
                                            @else
                                                <span class="text-gray-500">{{ $detail->dataset_title }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if ($detail->action == 'ditambah')
                                                <span class="text-blue-600 font-medium">Ditambah</span>
                                            @elseif ($detail->action == 'diperbarui')
                                                <span class="text-green-600 font-medium">Diperbarui</span>
                                            @elseif ($detail->action == 'gagal')
                                                <span class="text-red-600 font-medium">Gagal</span>
                                            @else
                                                <span class="text-gray-500">{{ $detail->action }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ $detail->message }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Tidak ada rincian yang tercatat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>