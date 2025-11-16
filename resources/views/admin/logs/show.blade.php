<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-1 h-8 bg-gradient-to-b from-[#0093DD] to-[#0070AA] rounded-full"></div>
                <h2 class="font-semibold text-xl text-[#0093DD] leading-tight">
                    Detail Log Sinkronisasi #{{ $log->id }}
                </h2>
            </div>
            <a href="{{ route('admin.logs.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-[#0093DD] rounded-md font-semibold text-xs text-[#0093DD] uppercase tracking-widest hover:bg-[#0093DD] hover:text-white transition">
                &larr; Kembali ke Daftar Log
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
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
                            </note>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Rincian Proses -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Rincian Proses ({{ $log->details->count() }} item)</h3>
                    <div class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Dataset</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tindakan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesan</th>
                                T</tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($log->details as $detail)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $detail->dataset_title }}
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