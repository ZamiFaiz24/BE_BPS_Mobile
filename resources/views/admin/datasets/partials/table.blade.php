@php
    // Mapping kategori kode ke warna
    $categoryColors = [
        1 => '#0093DD', // biru
        2 => '#EB891C', // oranye
        3 => '#68B92E', // hijau
    ];
@endphp

<table class="min-w-full bg-white divide-y divide-gray-200">
    <thead style="background-color: #0093DD;">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Nama Dataset</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Subjek</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Sumber</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Terakhir Diperbarui</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Ditambahkan</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Tipe Insight</th>
            <th class="px-6 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider">Aksi</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-100">
        @forelse ($datasets as $dataset)
            @php
                $subjectBg = $categoryColors[$dataset->category] ?? '#F3F4F6';
                $subjectText = '#fff';
            @endphp
            <tr style="transition: background 0.2s;" onmouseover="this.style.background='#68B92E1A'" onmouseout="this.style.background='white'">
                <td class="px-6 py-4 text-sm font-medium text-gray-900 max-w-xs break-words">{{ $dataset->dataset_name }}</td>
                <td class="px-6 py-4 text-sm font-semibold"
                    style="color: {{ $categoryColors[$dataset->category] ?? '#333' }};">
                    {{ $dataset->subject }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-700">{{ $dataset->source }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    @if($dataset->last_update)
                        <div>{{ \Carbon\Carbon::parse($dataset->last_update)->format('d M Y') }}</div>
                        <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($dataset->last_update)->diffForHumans() }}</span>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    <div>{{ $dataset->created_at->format('d M Y') }}</div>
                    <span class="text-xs text-gray-400">{{ $dataset->created_at->diffForHumans() }}</span>
                </td>
                <td class="px-6 py-4 text-sm">
                    @php
                        $insightLabels = [
                            'default' => 'Default',
                            'percent_lower_is_better' => 'Persen (Turun=Baik)',
                            'percent_higher_is_better' => 'Persen (Naik=Baik)',
                            'number_lower_is_better' => 'Angka (Turun=Baik)',
                            'number_higher_is_better' => 'Angka (Naik=Baik)',
                        ];
                    @endphp
                    {{ $insightLabels[$dataset->insight_type] ?? $dataset->insight_type }}
                </td>
                <td class="px-6 py-4 text-sm text-center">
                    <div class="flex items-center justify-center gap-1">
                        <a href="{{ route('admin.datasets.edit', $dataset) }}"
                           class="group p-2 rounded-md bg-white border border-[#EB891C] text-[#EB891C] hover:bg-[#EB891C] hover:text-white transition"
                           title="Edit Tipe Insight">
                            <x-heroicon-o-pencil class="w-5 h-5" />
                        </a>
                        <a href="{{ route('admin.datasets.show', $dataset) }}"
                           class="group p-2 rounded-md bg-white border border-[#0093DD] text-[#0093DD] hover:bg-[#0093DD] hover:text-white transition"
                           title="Lihat detail dataset">
                            <x-heroicon-o-eye class="w-5 h-5" />
                        </a>
                        <form action="{{ route('admin.datasets.destroy', $dataset) }}"
                              method="POST"
                              class="inline"
                              onsubmit="return confirmDelete(event)">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="group p-2 rounded-md bg-white border border-[#68B92E] text-[#68B92E] hover:bg-[#68B92E] hover:text-white transition"
                                title="Hapus dataset ini">
                                <x-heroicon-o-trash class="w-5 h-5" />
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-6 py-4 text-sm text-gray-500 text-center">Belum ada dataset.</td>
            </tr>
        @endforelse
    </tbody>
</table>
