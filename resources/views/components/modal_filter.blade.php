{{-- filepath: resources/views/components/modal_filter.blade.php (Versi Modern) --}}
<div x-data="filterModal()" class="relative">
    {{-- Tombol Pemicu Filter --}}
    <div class="flex items-center gap-2">
        <button type="button"
            @click="open = true"
            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white transition rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            style="background-color: #0093DD;">
            <x-heroicon-o-adjustments-horizontal class="w-5 h-5 mr-2" />
            Filter
        </button>

        {{-- Tombol Reset Filter (hanya tampil jika filter aktif) --}}
        @if(request('category') || request('subject'))
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition rounded-md shadow-sm hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
                style="background-color: #EB891C;">
                <x-heroicon-o-x-mark class="w-5 h-5 mr-2" />
                Reset Filter
            </a>
        @endif
    </div>

    {{-- Modal Filter --}}
    <div
        x-show="open"
        x-cloak
        @keydown.escape.window="open = false"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50"
    >
        {{-- Panel Modal --}}
        <div
            @click.away="open = false"
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="w-full max-w-lg bg-white rounded-lg shadow-xl"
        >
            <form method="GET" action="{{ route('admin.dashboard') }}">
                {{-- Modal Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h5 class="text-lg font-semibold text-gray-800">Filter Dataset</h5>
                    <button type="button" @click="open = false" class="text-gray-400 rounded-full hover:text-gray-600 hover:bg-gray-100 p-1.5 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 space-y-6">
                    {{-- Kategori --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Kategori</label>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="(subjects, cat) in categories" :key="cat">
                                <label class="relative flex items-center">
                                    <input type="radio" :id="'cat-'+cat" name="category" :value="cat"
                                        x-model="selectedCategory"
                                        class="sr-only peer" />
                                    <span x-text="cat" class="px-3 py-1 text-sm border border-gray-300 rounded-full cursor-pointer peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:bg-gray-50 transition-colors"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    {{-- Subject (muncul jika kategori dipilih) --}}
                    <div x-show="selectedCategory" x-transition>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Subject</label>
                        <div class="flex flex-wrap gap-2">
                            <template x-if="selectedCategory">
                                <template x-for="subj in categories[selectedCategory]" :key="subj">
                                    <label class="relative flex items-center">
                                        <input type="radio" :id="'subj-'+subj" name="subject" :value="subj"
                                            x-model="selectedSubject"
                                            class="sr-only peer" />
                                        <span x-text="subj" class="px-3 py-1 text-sm border border-gray-300 rounded-full cursor-pointer peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:bg-gray-50 transition-colors"></span>
                                    </label>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center justify-end px-6 py-4 space-x-2 bg-gray-50 rounded-b-lg">
                    <button type="button" @click="resetAndSubmit($event)" class="px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">Reset</button>
                    <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white transition border border-transparent rounded-md shadow-sm hover:bg-blue-700" style="background-color: #0093DD;">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Alpine.js filterModal --}}
    <script>
    function filterModal() {
        return {
            open: false,
            categories: @json($categories ?? []), // Gunakan ?? [] untuk keamanan jika variabel tidak ada
            selectedCategory: '{{ request('category') }}' || '',
            selectedSubject: '{{ request('subject') }}' || '',

            resetAndSubmit(event) {
                this.selectedCategory = '';
                this.selectedSubject = '';

                // $nextTick memastikan nilai model di-update sebelum form di-submit
                this.$nextTick(() => {
                    event.target.closest('form').submit();
                });
            }
        }
    }
    </script>
</div>