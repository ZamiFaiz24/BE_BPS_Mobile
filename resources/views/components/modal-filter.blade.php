@props(['categories'])

<div id="filter-modal" class="fixed inset-0 z-50 items-center justify-center p-4 bg-black bg-opacity-40 backdrop-blur-sm hidden">
    <div class="w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-[#0093DD]/10 relative overflow-hidden animate-fade-in">
        {{-- Header Modal --}}
        <div class="flex items-center gap-4 px-8 py-6 border-b-2 border-[#0093DD]/30 bg-gradient-to-r from-[#0093DD]/30 via-[#0093DD]/10 to-white shadow-sm relative">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-[#0093DD]/10 shadow">
                <svg class="w-7 h-7 text-[#0093DD]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707l-7 7V19a1 1 0 01-2 0v-5.293l-7-7A1 1 0 013 6V4z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <h5 class="text-2xl font-bold text-[#0093DD] tracking-tight">Filter Dataset</h5>
                <p class="text-sm text-gray-500 mt-1">Pilih kategori dan subject untuk menampilkan data yang relevan.</p>
            </div>
            <button type="button" id="close-filter-btn"
                class="absolute top-4 right-4 text-gray-400 hover:text-[#EB891C] bg-gray-100 hover:bg-gray-200 rounded-full p-2 shadow transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>

        {{-- Body Modal --}}
        <form method="GET" action="{{ route('admin.dashboard') }}">
            <div class="px-8 py-8 space-y-8 max-h-[60vh] overflow-y-auto">
                {{-- Kategori (Radio Button) --}}
                <div>
                    <label class="block mb-3 text-base font-semibold text-[#0093DD]">Pilih Satu Kategori</label>
                    <div class="flex flex-wrap gap-3" id="category-radio-group">
                        @foreach($categories as $cat)
                            @php
                                // Tentukan warna berdasarkan ID kategori
                                $colorClass = match($cat['id']) {
                                    1 => 'border-[#0093DD]/30 bg-[#F5FAFF] text-[#0093DD] hover:border-[#0093DD] hover:bg-[#E6F3FB] focus-within:ring-[#0093DD]',
                                    2 => 'border-[#EB891C]/30 bg-[#FFF8F2] text-[#EB891C] hover:border-[#EB891C] hover:bg-[#FDF1E3] focus-within:ring-[#EB891C]',
                                    3 => 'border-[#68B92E]/30 bg-[#F7FAF3] text-[#68B92E] hover:border-[#68B92E] hover:bg-[#EAF7E3] focus-within:ring-[#68B92E]',
                                    default => 'border-gray-300 bg-white text-gray-600 hover:border-gray-400 hover:bg-gray-50 focus-within:ring-gray-400'
                                };
                            @endphp
                            <label class="flex items-center gap-3 px-4 py-2 rounded-xl border cursor-pointer shadow-sm transition {{ $colorClass }}">
                                <input type="radio" name="category" value="{{ $cat['id'] }}" class="form-radio focus:ring-0" {{ request('category') == $cat['id'] ? 'checked' : '' }}>
                                <span class="font-medium">{{ $cat['name'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                
                {{-- Subject (Checkbox) - Akan diisi oleh JavaScript --}}
                <div>
                    <label class="block mb-3 text-base font-semibold text-[#68B92E]">Pilih Subject (Bisa lebih dari satu)</label>
                    <div class="flex flex-wrap gap-3" id="subject-checkbox-group">
                        <p class="text-sm text-gray-400 italic">Pilih kategori terlebih dahulu.</p>
                    </div>
                </div>
            </div>

            {{-- Footer Modal --}}
            <div class="flex items-center justify-end gap-3 px-8 py-6 bg-gradient-to-r from-white to-[#F5FAFF] rounded-b-3xl border-t border-gray-100">
                <a href="{{ route('admin.dashboard') }}" class="px-5 py-2 text-base font-semibold text-[#EB891C] bg-white border border-[#EB891C] rounded-full shadow-sm hover:bg-[#EB891C] hover:text-white transition">
                    Reset
                </a>
                <button type="submit" class="px-5 py-2 text-base font-semibold text-white bg-[#0093DD] rounded-full shadow-lg hover:bg-[#0070C0] transition">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes fade-in { from { opacity: 0; transform: translateY(20px);} to { opacity: 1; transform: translateY(0);}}
.animate-fade-in { animation: fade-in 0.3s cubic-bezier(.4,2,.6,1) both; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const categoriesData = @json($categories);
    const subjectGroup = document.getElementById('subject-checkbox-group');
    const categoryRadios = document.querySelectorAll('#category-radio-group input[type="radio"]');
    const previouslySelectedCategory = '{{ request('category') }}';
    const previouslySelectedSubjects = @json((array) request('subject', []));

    function renderSubjects(categoryId) {
        subjectGroup.innerHTML = '';
        const category = categoriesData.find(c => String(c.id) === String(categoryId));
        if (!category || category.subjects.length === 0) {
            subjectGroup.innerHTML = '<p class="text-sm text-gray-400 italic">Tidak ada subject untuk kategori ini.</p>';
            return;
        }
        category.subjects.forEach(subjectName => {
            const isChecked = previouslySelectedSubjects.includes(subjectName) ? 'checked' : '';
            subjectGroup.innerHTML += `
                <label class="flex items-center gap-3 px-4 py-2 rounded-xl border border-gray-200 bg-white cursor-pointer shadow-sm transition">
                    <input type="checkbox" name="subject[]" value="${subjectName}" ${isChecked} class="form-checkbox">
                    <span class="font-medium">${subjectName}</span>
                </label>
            `;
        });
    }

    categoryRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            renderSubjects(this.value);
        });
    });

    if (previouslySelectedCategory) {
        renderSubjects(previouslySelectedCategory);
    }

    const modal = document.getElementById('filter-modal');
    const openBtn = document.getElementById('open-filter-btn');
    const closeBtn = document.getElementById('close-filter-btn');

    if (openBtn) {
        openBtn.addEventListener('click', function() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    }
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
    }
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    });
});
</script>