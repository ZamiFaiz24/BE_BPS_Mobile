@props(['categories'])

<div id="filter-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40">
    <div class="w-full max-w-xl bg-white rounded-2xl shadow-md border border-gray-100 relative overflow-hidden">
        {{-- Header Modal --}}
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
                        
                        {{-- MODIFIKASI: Menggunakan 'peer-checked' untuk status aktif --}}
                        @foreach($categories as $cat)
                            <label class="flex items-start gap-3 p-4 rounded-lg border border-gray-200 cursor-pointer hover:border-[#0093DD] hover:bg-[#0093DD]/5 transition
                                        peer-checked:border-[#0093DD] peer-checked:bg-[#0093DD]/5 peer-checked:ring-1 peer-checked:ring-[#0093DD]/30">
                                
                                <input type="radio" name="category" value="{{ $cat['id'] }}" 
                                       class="form-radio text-[#0093DD] focus:ring-[#0093DD] mt-1 peer" 
                                       {{ request('category') == $cat['id'] ? 'checked' : '' }}>
                                
                                <span class="text-sm font-semibold text-gray-700">{{ $cat['name'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                
                {{-- Subject (Checkbox) - Diisi oleh JavaScript --}}
                <div>
                    <label class="block mb-3 text-sm font-semibold text-gray-700">Pilih Subject (Bisa lebih dari satu)</label>
                    
                    {{-- MODIFIKASI: max-h-64, overflow-y-auto, pr-2, dan grid-cols-1 --}}
                    <div class="grid grid-cols-1 gap-2 max-h-64 overflow-y-auto pr-2" id="subject-checkbox-group">
                        <p class="text-sm text-gray-400 italic col-span-2">Pilih kategori terlebih dahulu.</p>
                    </div>
                </div>
            </div>

            {{-- Footer Modal --}}
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
            
            // MODIFIKASI: Template JS diperbarui untuk 'peer-checked'
            const subjectHtml = `
                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 cursor-pointer hover:border-[#0093DD] hover:bg-[#0093DD]/5 transition
                            peer-checked:border-[#0093DD] peer-checked:bg-[#0093DD]/5">
                    
                    <input type="checkbox" name="subject[]" value="${subjectName}" ${isChecked} 
                           class="form-checkbox text-[#0093DD] focus:ring-[#0093DD] rounded peer">
                           
                    <span class="text-sm font-medium text-gray-700">${subjectName}</span>
                </label>
            `;
            subjectGroup.innerHTML += subjectHtml;
        });
    }

    categoryRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Saat ganti kategori, reset subject yang lama
            previouslySelectedSubjects.length = 0; 
            renderSubjects(this.value);
        });
    });

    // Otomatis muat subject jika ada kategori yang sudah terpilih saat halaman dimuat
    if (previouslySelectedCategory) {
        renderSubjects(previouslySelectedCategory);
    }

    // --- Logika Buka/Tutup Modal (Tanpa Perubahan) ---
    const modal = document.getElementById('filter-modal');
    const openBtn = document.getElementById('open-filter-btn'); // Pastikan Anda punya tombol ini di halaman utama
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
        // Tutup modal jika klik di area luar (background)
        if (e.target === modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    });
});
</script>