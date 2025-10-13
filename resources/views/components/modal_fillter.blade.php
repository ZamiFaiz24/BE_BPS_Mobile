{{-- filepath: d:\Aplikasi\xampp\htdocs\Laravel\be_bps_mobile\resources\views\components\modal_fillter.blade.php --}}
<div x-data="filterModal()" class="relative">
    {{-- Tombol Filter --}}
    <button type="button"
        class="inline-flex items-center px-4 py-2 text-white rounded-md text-sm font-medium transition"
        style="background-color: #0093DD;"
        @click="open = true">
        <x-heroicon-o-adjustments-horizontal class="w-5 h-5 mr-2" />
        Filter
    </button>
    {{-- Tombol Reset Filter (hanya tampil jika filter aktif) --}}
    @if(request('category') || request('subject'))
        <a href="{{ route('admin.dashboard') }}"
            class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium transition"
            style="background-color: #EB891C; color: #fff;">
            <x-heroicon-o-x-mark class="w-5 h-5 mr-2" />
            Reset Filter
        </a>
    @endif

    {{-- Modal Filter --}}
    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
            <form method="GET" action="{{ route('admin.dashboard') }}">
                <div class="flex justify-between items-center border-b px-6 py-4">
                    <h5 class="text-lg font-semibold">Filter Dataset</h5>
                    <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="px-6 py-4">
                    {{-- Kategori --}}
                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Kategori</label>
                        <template x-for="(subjects, cat) in categories" :key="cat">
                            <div class="flex items-center mb-1">
                                <input type="radio" :id="'cat-'+cat" name="category" :value="cat"
                                    x-model="selectedCategory"
                                    class="form-radio text-blue-600 focus:ring-blue-500" />
                                <label :for="'cat-'+cat" class="ml-2" x-text="cat"></label>
                            </div>
                        </template>
                    </div>
                    {{-- Subject --}}
                    <div class="mb-4" x-show="selectedCategory">
                        <label class="block mb-1 font-medium">Subject</label>
                        <template x-if="selectedCategory">
                            <template x-for="subj in categories[selectedCategory]" :key="subj">
                                <div class="flex items-center mb-1">
                                    <input type="radio" :id="'subj-'+subj" name="subject" :value="subj"
                                        x-model="selectedSubject"
                                        class="form-radio text-blue-600 focus:ring-blue-500" />
                                    <label :for="'subj-'+subj" class="ml-2" x-text="subj"></label>
                                </div>
                            </template>
                        </template>
                    </div>
                </div>
                <div class="flex justify-end border-t px-6 py-4 gap-2">
                    <button type="button" @click="resetFilter" class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300">Reset</button>
                    <button type="button" @click="open = false" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-700">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded" style="background-color: #68B92E; color: #fff;">Terapkan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Alpine.js filterModal --}}
    <script>
    function filterModal() {
        return {
            open: false,
            categories: @json($categories),
            selectedCategory: '{{ request('category') }}',
            selectedSubject: '{{ request('subject') }}',
            resetFilter() {
                this.selectedCategory = '';
                this.selectedSubject = '';
            }
        }
    }
    </script>
</div>