{{-- resources/views/admin/datasets/partials/table-and-pagination.blade.php --}}

<div class="overflow-x-auto">
    @include('admin.datasets.partials.table', ['datasets' => $datasets])
</div>

<div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        {{-- Info --}}
        <div class="w-full">
            <div class="text-sm text-gray-600 mb-2 sm:mb-0">
                Menampilkan
                <span class="font-semibold text-[#0093DD]">{{ $datasets->firstItem() ?? 0 }}</span>
                sampai
                <span class="font-semibold text-[#0093DD]">{{ $datasets->lastItem() ?? 0 }}</span>
                dari
                <span class="font-semibold text-[#0093DD]">{{ $datasets->total() }}</span>
                data
            </div>
            <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-2 mt-2">
                <label for="per_page" class="text-sm text-gray-600">Tampilkan</label>
                <div class="relative">
                    <select name="per_page" id="per_page"
                        onchange="this.form.submit()"
                        class="appearance-none border border-gray-300 bg-white rounded-md px-3 py-1.5 text-sm focus:ring-[#0093DD] focus:border-[#0093DD] transition pr-8">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>
                <span class="text-sm text-gray-600">per halaman</span>
                {{-- Preserve all filters/search/sort --}}
                @foreach(request()->except(['per_page', 'page']) as $key => $val)
                    @if(is_array($val))
                        @foreach($val as $v)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endif
                @endforeach
            </form>
        </div>

        {{-- Pagination Links --}}
        <div class="flex items-center gap-1 flex-wrap">
            {{-- Previous Button --}}
            @if ($datasets->onFirstPage())
                <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 rounded-md cursor-not-allowed">
                    &larr; Sebelumnya
                </span>
            @else
                <a href="{{ $datasets->previousPageUrl() }}"
                   class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">
                    &larr; Sebelumnya
                </a>
            @endif

            {{-- Page Numbers (max 5 pages shown, ellipsis if needed) --}}
            @php
                $total = $datasets->lastPage();
                $current = $datasets->currentPage();
                $start = max(1, $current - 2);
                $end = min($total, $current + 2);
                if ($start > 1) echo '<span class="px-2">...</span>';
            @endphp
            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $current)
                    <span class="px-3 py-2 text-sm font-semibold text-white bg-[#0093DD] border border-[#0093DD] rounded-md">
                        {{ $i }}
                    </span>
                @else
                    <a href="{{ $datasets->url($i) }}"
                       class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">
                        {{ $i }}
                    </a>
                @endif
            @endfor
            @if ($end < $total) <span class="px-2">...</span> @endif

            {{-- Next Button --}}
            @if ($datasets->hasMorePages())
                <a href="{{ $datasets->nextPageUrl() }}"
                   class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">
                    Selanjutnya &rarr;
                </a>
            @else
                <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 rounded-md cursor-not-allowed">
                    Selanjutnya &rarr;
                </span>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Script untuk 'Per Page'
    function handlePerPageChange(selectElement) {
        const perPage = selectElement.value;
        const currentUrl = new URL(window.location.href);

        // 1. Set parameter 'per_page' baru
        currentUrl.searchParams.set('per_page', perPage);
        
        // 2. Reset ke halaman 1 (PENTING!)
        currentUrl.searchParams.set('page', '1'); 

        // 3. Redirect ke URL baru
        window.location.href = currentUrl.toString();
    }

    // Script untuk 'Filter Modal' (Pindahkan ke sini)
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
@endpush