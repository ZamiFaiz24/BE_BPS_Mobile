@props(['categories'])

<div id="filter-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40">
    <div class="w-full max-w-xl bg-white rounded-2xl shadow-md border border-gray-100 relative overflow-hidden">
        {{-- Header Modal (lebih sederhana) --}}
        <div class="flex items-start justify-between px-6 py-5 border-b">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-8 h-8 rounded-md bg-[#0093DD]/10 text-[#0093DD]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 4h18M7 8h10M10 12h4m-6 4h8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div>
                    <h5 class="text-lg font-semibold text-gray-800">Filter Dataset</h5>
                    <p class="text-sm text-gray-500">Pilih kategori dan subject.</p>
                </div>
            </div>
            <button type="button" id="close-filter-btn"
                class="text-gray-400 hover:text-gray-600 rounded-md p-2 hover:bg-gray-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>

        {{-- Body Modal --}}
        <form method="GET" action="{{ route('admin.dashboard') }}">
            <div class="px-6 py-5 space-y-6 max-h-[65vh] overflow-y-auto">
                {{-- Kategori (Radio Button) --}}
                <div>
                    <label class="block mb-3 text-sm font-semibold text-gray-700">Pilih Satu Kategori</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2" id="category-radio-group">
                        @foreach($categories as $cat)
                            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 cursor-pointer hover:border-[#0093DD] hover:bg-[#0093DD]/5 transition">
                                <input type="radio" name="category" value="{{ $cat['id'] }}" class="form-radio text-[#0093DD] focus:ring-[#0093DD]" {{ request('category') == $cat['id'] ? 'checked' : '' }}>
                                <span class="text-sm font-medium text-gray-700">{{ $cat['name'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                
                {{-- Subject (Checkbox) - Diisi oleh JavaScript --}}
                <div>
                    <label class="block mb-3 text-sm font-semibold text-gray-700">Pilih Subject (Bisa lebih dari satu)</label>
                    <div class="grid grid-cols-2 gap-2" id="subject-checkbox-group">
                        <p class="text-sm text-gray-400 italic col-span-2">Pilih kategori terlebih dahulu.</p>
                    </div>
                </div>
            </div>

            {{-- Footer Modal (minimalis) --}}
            <div class="flex items-center justify-end gap-3 px-6 py-5 border-t bg-gray-50/50">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-sm font-medium text-[#0093DD] hover:underline">
                    Reset
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-[#0093DD] rounded-md hover:bg-[#0080C0] transition">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>
</div>

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
        if (!category || !category.subjects || category.subjects.length === 0) {
            subjectGroup.innerHTML = '<p class="text-sm text-gray-400 italic col-span-2">Tidak ada subject untuk kategori ini.</p>';
            return;
        }
        category.subjects.forEach(subjectName => {
            const isChecked = previouslySelectedSubjects.includes(subjectName) ? 'checked' : '';
            subjectGroup.innerHTML += `
                <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 cursor-pointer hover:border-[#0093DD] hover:bg-[#0093DD]/5 transition">
                    <input type="checkbox" name="subject[]" value="${subjectName}" ${isChecked} class="form-checkbox text-[#0093DD] focus:ring-[#0093DD]">
                    <span class="text-sm text-gray-700">${subjectName}</span>
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