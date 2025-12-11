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
            <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Sumber</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Terakhir Diperbarui</th>
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
                <td class="px-6 py-4 text-sm">
                    @php
                        $configService = app(\App\Services\DatasetConfigService::class);
                        $allConfigs = $configService->getAllDatasets();
                        $configDataset = collect($allConfigs)->first(function($cfg) use ($dataset) {
                            return ($cfg['variable_id'] ?? null) == $dataset->dataset_code;
                        });
                        $isEnabled = $configDataset['enabled'] ?? true;
                    @endphp
                    @if($isEnabled)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            Nonaktif
                        </span>
                    @endif
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
                        <a href="{{ route('admin.datasets.edit', array_merge(['dataset'=>$dataset->id], request()->only(['category','subject','q','sort','order','page','per_page']))) }}"
                           class="group p-2 rounded-md bg-white border border-[#EB891C] text-[#EB891C] hover:bg-[#EB891C] hover:text-white transition"
                           title="Edit Tipe Insight">
                            <x-heroicon-o-pencil class="w-5 h-5" />
                        </a>
                        <a href="{{ route('admin.datasets.show', array_merge(['dataset'=>$dataset->id], request()->only(['category','subject','q','sort','order','page','per_page']))) }}"
                           class="group p-2 rounded-md bg-white border border-[#0093DD] text-[#0093DD] hover:bg-[#0093DD] hover:text-white transition"
                           title="Lihat detail dataset">
                            <x-heroicon-o-eye class="w-5 h-5" />
                        </a>
                        <form action="{{ route('admin.datasets.destroy', $dataset) }}{{ request()->getQueryString() ? '?'.request()->getQueryString() : '' }}"
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
