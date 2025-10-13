{{-- resources/views/components/modal-filter.blade.php --}}
@props(['categories', 'subjects'])

<div id="filter-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-40 backdrop-blur-sm hidden">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-[#0093DD]/10 relative overflow-hidden animate-fade-in">
        {{-- Header Modal --}}
        <div class="flex items-center gap-4 px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-[#0093DD]/10 to-white">
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-[#0093DD]/10">
                <svg class="w-7 h-7 text-[#0093DD]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707l-7 7V19a1 1 0 01-2 0v-5.293l-7-7A1 1 0 013 6V4z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <h5 class="text-2xl font-bold text-[#0093DD] tracking-tight">Filter Dataset</h5>
                <p class="text-sm text-gray-500 mt-1">Pilih kategori dan subject untuk menampilkan data yang relevan.</p>
            </div>
            <button type="button" id="close-filter-btn"
                class="absolute top-4 right-4 text-gray-400 hover:text-[#EB891C] bg-gray-100 hover:bg-gray-200 rounded-full p-2 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>

        {{-- Body Modal --}}
        <form method="GET" action="{{ route('admin.dashboard') }}">
            <div class="px-8 py-8 space-y-8">
                <div>
                    <label for="category-select" class="block mb-2 text-base font-semibold text-[#0093DD]">Kategori</label>
                    <select name="category" id="category-select" class="w-full border border-[#0093DD] rounded-lg px-4 py-2 shadow-sm focus:ring-[#0093DD] focus:border-[#0093DD] text-[#0093DD] bg-white">
                        <option value="">-- Semua Kategori --</option>
                        @foreach($categories as $catId)
                            <option value="{{ $catId }}" {{ request('category') == $catId ? 'selected' : '' }}>
                                {{ \App\Models\BpsDataset::CATEGORIES[$catId] ?? 'Kategori ' . $catId }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="subject-select" class="block mb-2 text-base font-semibold text-[#68B92E]">Subject</label>
                    <select name="subject" id="subject-select" class="w-full border border-[#68B92E] rounded-lg px-4 py-2 shadow-sm focus:ring-[#68B92E] focus:border-[#68B92E] text-[#68B92E] bg-white">
                        <option value="">-- Semua Subject --</option>
                        @foreach($subjects as $subj)
                            <option value="{{ $subj }}" {{ request('subject') == $subj ? 'selected' : '' }}>{{ $subj }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Footer Modal --}}
            <div class="flex items-center justify-end gap-3 px-8 py-6 bg-gray-50 rounded-b-2xl border-t border-gray-100">
                <button type="submit" class="px-5 py-2 text-base font-semibold text-white bg-[#0093DD] rounded-full shadow-lg hover:bg-[#0070C0] transition">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(40px);}
    to { opacity: 1; transform: translateY(0);}
}
.animate-fade-in {
    animation: fade-in 0.3s cubic-bezier(.4,2,.6,1) both;
}
</style>