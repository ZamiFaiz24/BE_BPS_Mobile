{{-- resources/views/admin/contents/partials/pagination.blade.php --}}

<div class="mt-4 px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6 rounded-b-lg">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        
        {{-- Info & Per Page Selector --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
            {{-- Info Card dengan Icon --}}
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg shadow-sm">
                <div class="text-sm">
                    <span class="text-gray-600">Menampilkan</span>
                    <span class="font-bold text-[#0093DD] mx-1">{{ $contents->firstItem() ?? 0 }} - {{ $contents->lastItem() ?? 0 }}</span>
                    <span class="text-gray-600">dari</span>
                    <span class="font-bold text-gray-900 mx-1">{{ number_format($contents->total()) }}</span>
                    <span class="text-gray-600">data</span>
                </div>
            </div>

            {{-- Dropdown Per Page dengan Icon --}}
            <div class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm hover:border-[#0093DD] transition">
                <span class="text-sm text-gray-600 font-medium">Tampilkan</span>
                <select name="per_page" id="per_page_content"
                    onchange="handlePerPageChange(this)"
                    class="border-0 bg-transparent text-sm font-semibold text-[#0093DD] focus:ring-0 focus:outline-none cursor-pointer pr-8">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
                <span class="text-sm text-gray-600 font-medium">data</span>
            </div>
        </div>

        {{-- Tombol Next/Prev Manual --}}
        <div class="flex items-center gap-1">
            {{-- Previous --}}
            @if ($contents->onFirstPage())
                <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 rounded-md cursor-not-allowed">&larr; Sebelumnya</span>
            @else
                <a href="{{ $contents->previousPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">&larr; Sebelumnya</a>
            @endif

            {{-- Page Numbers Loop (Simple Version) --}}
            @foreach ($contents->getUrlRange(max(1, $contents->currentPage() - 2), min($contents->lastPage(), $contents->currentPage() + 2)) as $page => $url)
                @if ($page == $contents->currentPage())
                    <span class="px-3 py-2 text-sm font-semibold text-white bg-[#0093DD] border border-[#0093DD] rounded-md">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Next --}}
            @if ($contents->hasMorePages())
                <a href="{{ $contents->nextPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Selanjutnya &rarr;</a>
            @else
                <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 rounded-md cursor-not-allowed">Selanjutnya &rarr;</span>
            @endif
        </div>
    </div>
</div>

{{-- Script Navigasi --}}
@push('scripts')
<script>
    function handlePerPageChange(selectElement) {
        const perPage = selectElement.value;
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('per_page', perPage);
        currentUrl.searchParams.set('page', '1'); 
        window.location.href = currentUrl.toString();
    }
</script>
@endpush