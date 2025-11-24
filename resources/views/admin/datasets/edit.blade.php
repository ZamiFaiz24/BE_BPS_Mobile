<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Dataset: {{ $dataset->dataset_name }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-8">
        <div class="mb-6">
            <a href="{{ route('admin.dashboard', request()->only(['category','subject','q','sort','order','page','per_page'])) }}" class="inline-flex items-center px-4 py-2 bg-white border border-[#0093DD] rounded-md font-semibold text-xs text-[#0093DD] uppercase tracking-widest hover:bg-[#0093DD] hover:text-white transition duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Kembali
            </a>
        </div>

        <div class="bg-white rounded shadow-sm overflow-hidden">
            <div class="bg-[#0093DD] px-6 py-4 border-b border-[#0077B6]">
                <h3 class="text-white font-semibold">Form Edit Dataset</h3>
            </div>

            <form method="POST" action="{{ route('admin.datasets.update', $dataset) }}" class="p-6">
                @csrf
                @method('PATCH')

                {{-- Hidden inputs to preserve filter & pagination state --}}
                @foreach(['category','subject','q','sort','order','page','per_page'] as $param)
                    @php($val = request($param))
                    @if(is_array($val))
                        @foreach($val as $v)
                            <input type="hidden" name="{{ $param }}[]" value="{{ $v }}">
                        @endforeach
                    @elseif(!is_null($val) && $val !== '')
                        <input type="hidden" name="{{ $param }}" value="{{ $val }}">
                    @endif
                @endforeach

                <div class="grid grid-cols-1 gap-6">
                    {{-- Nama Dataset --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="dataset_name">Nama Dataset</label>
                        <input
                            id="dataset_name"
                            type="text"
                            name="dataset_name"
                            class="w-full border rounded px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD] @error('dataset_name') border-red-500 ring-1 ring-red-300 @enderror"
                            value="{{ old('dataset_name', $dataset->dataset_name) }}"
                            placeholder="Masukkan nama dataset"
                        >
                        @error('dataset_name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Subject (Dropdown jika data tersedia, fallback ke input teks) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="subject">
                            Subject
                        </label>

                        {{-- Cek apakah variabel $subjects ada dan memiliki isi --}}
                        @if(isset($subjects) && count($subjects) > 0)
                            <select
                                id="subject"
                                name="subject_id"
                                class="w-full border rounded px-3 py-2 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD] @error('subject_id') border-red-500 ring-1 ring-red-300 @enderror"
                            >
                                <option value="">— Pilih Subject —</option>
                                @foreach($subjects as $s)
                                    <option value="{{ $s->id }}" {{ old('subject_id', $dataset->subject_id) == $s->id ? 'selected' : '' }}>
                                        {{ $s->name ?? $s->subject_name ?? $s->title ?? ('Subject #' . $s->id) }}
                                    </option>
                                @endforeach
                            </select>
                            
                            <p class="text-xs text-gray-500 mt-1">Pilih subject dari daftar yang tersedia.</p>
                            
                            @error('subject_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror

                        @else
                            {{-- Fallback Input Teks --}}
                            <input
                                id="subject"
                                type="text"
                                name="subject"
                                class="w-full border rounded px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD] @error('subject') border-red-500 ring-1 ring-red-300 @enderror"
                                value="{{ old('subject', $dataset->subject ?? '') }}"
                                placeholder="Masukkan subject data"
                            >
                            
                            <p class="text-xs text-gray-500 mt-1">Dropdown subject tidak tersedia. Menggunakan input teks.</p>
                            
                            @error('subject')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    {{-- Tipe Insight --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Insight</label>
                        <select name="insight_type" class="w-full border rounded px-3 py-2 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD] @error('insight_type') border-red-500 ring-1 ring-red-300 @enderror">
                            <option value="default" @selected($dataset->insight_type == 'default')>Default</option>
                            <option value="percent_lower_is_better" @selected($dataset->insight_type == 'percent_lower_is_better')>Persen (Turun=Baik)</option>
                            <option value="percent_higher_is_better" @selected($dataset->insight_type == 'percent_higher_is_better')>Persen (Naik=Baik)</option>
                            <option value="number_lower_is_better" @selected($dataset->insight_type == 'number_lower_is_better')>Angka (Turun=Baik)</option>
                            <option value="number_higher_is_better" @selected($dataset->insight_type == 'number_higher_is_better')>Angka (Naik=Baik)</option>
                        </select>
                        @error('insight_type')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-2">
                    <a href="{{ route('admin.dashboard', request()->only(['category','subject','q','sort','order','page','per_page'])) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#0093DD] text-white rounded-md text-sm font-semibold hover:bg-[#0077B6]">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>