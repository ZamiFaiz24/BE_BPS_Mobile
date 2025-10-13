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
            <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Subject</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Source</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Tipe Insight</th>
            <th class="px-6 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider">Aksi</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-100">
        @forelse ($datasets as $dataset)
            @php
                // Pastikan $dataset->category adalah integer 1,2,3
                $subjectBg = $categoryColors[$dataset->category] ?? '#F3F4F6'; // fallback abu-abu
                $subjectText = '#fff';
            @endphp
            <tr style="transition: background 0.2s;" onmouseover="this.style.background='#68B92E1A'" onmouseout="this.style.background='white'">
                <td class="px-6 py-4 text-sm font-medium text-gray-900 max-w-xs break-words">{{ $dataset->dataset_name }}</td>
                <td class="px-6 py-4 text-sm font-semibold"
                    style="color: {{ $categoryColors[$dataset->category] ?? '#333' }};">
                    {{ $dataset->subject }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-700">{{ $dataset->source }}</td>
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
                           title="Edit Tipe Insight"
                           aria-label="Edit Tipe Insight">
                            <x-heroicon-o-pencil class="w-5 h-5" />
                        </a>
                        <a href="{{ route('admin.datasets.show', $dataset) }}"
                           class="group p-2 rounded-md bg-white border border-[#0093DD] text-[#0093DD] hover:bg-[#0093DD] hover:text-white transition"
                           title="Lihat detail dataset"
                           aria-label="Lihat detail dataset">
                            <x-heroicon-o-eye class="w-5 h-5" />
                        </a>
                        <form action="{{ route('admin.datasets.destroy', $dataset) }}"
                              method="POST"
                              class="inline"
                              onsubmit="return confirmDelete(event)">
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="group p-2 rounded-md bg-white border border-[#68B92E] text-[#68B92E] hover:bg-[#68B92E] hover:text-white transition"
                                title="Hapus dataset ini"
                                aria-label="Hapus dataset ini"
                            >
                                <x-heroicon-o-trash class="w-5 h-5" />
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-6 py-4 text-sm text-gray-500 text-center">Belum ada dataset.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="flex justify-start mb-2">
    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center gap-2 bg-[#0093DD1A] px-4 py-2 rounded shadow-sm">
        <label for="per_page" class="text-sm text-[#0093DD] mr-2">Tampilkan</label>
        <select name="per_page" id="per_page"
            onchange="this.form.submit()"
            class="border border-[#0093DD] bg-white rounded px-2 py-1 text-sm min-w-[70px] pl-2 focus:ring-[#0093DD] focus:border-[#0093DD] transition">
            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
        </select>
        <span class="text-sm text-[#0093DD] ml-2">data per halaman</span>

        <input type="hidden" name="q" value="{{ request('q') }}">
        @if(request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
        @endif
        @if(is_array(request('subject')))
            @foreach(request('subject') as $subj)
                <input type="hidden" name="subject[]" value="{{ $subj }}">
            @endforeach
        @endif
    </form>
</div>

{{-- <div class="mt-4">
    {{ $datasets->links('vendor.pagination.tailwind') }}
</div> --}}

{{-- Alpine.js filterModal --}}
<script>
function filterModal() {
    return {
        open: false,
        categories: @json($categories ?? []),
        selectedCategory: @json(request('category')) || null,
        selectedSubject: @json((array) request('subject')) || [],

        resetSelections() {
            this.selectedCategory = null;
            this.selectedSubject = [];
        }
    }
}
</script>