<x-app-layout>

    {{-- ====================== HEADER ====================== --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Dataset: {{ $dataset->dataset_name }}
        </h2>
    </x-slot>


    {{-- ====================== WRAPPER ====================== --}}
    <div class="max-w-3xl mx-auto mt-8">

        {{-- Tombol kembali --}}
        <div class="mb-6">
            <a href="{{ route('admin.dashboard', request()->only(['category','subject','q','sort','order','page','per_page'])) }}"
               class="inline-flex items-center px-4 py-2 bg-white border border-[#0093DD] rounded-md font-semibold text-xs text-[#0093DD] uppercase tracking-widest hover:bg-[#0093DD] hover:text-white transition duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Kembali
            </a>
        </div>


        {{-- ====================== CARD FORM ====================== --}}
        <div class="bg-white rounded shadow-sm overflow-hidden">

            <div class="bg-[#0093DD] px-6 py-4 border-b border-[#0077B6]">
                <h3 class="text-white font-semibold">Form Edit Dataset</h3>
            </div>


            <form method="POST" action="{{ route('admin.datasets.update', $dataset) }}" class="p-6">
                @csrf
                @method('PATCH')


                {{-- Hidden input menjaga filter tetap aktif setelah update --}}
                {{-- PERBAIKAN DI SINI: Menggunakan blok @php penuh agar aman --}}
                @foreach(['category','subject','q','sort','order','page','per_page'] as $param)
                    @php
                        $val = request($param);
                    @endphp

                    @if(is_array($val))
                        @foreach($val as $v)
                            <input type="hidden" name="{{ $param }}[]" value="{{ $v }}">
                        @endforeach
                    @elseif(!is_null($val) && $val !== '')
                        <input type="hidden" name="{{ $param }}" value="{{ $val }}">
                    @endif
                @endforeach



                {{-- ====================== FORM FIELDS ====================== --}}
                <div class="grid grid-cols-1 gap-6">


                    {{-- Nama Dataset --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Dataset</label>
                        <input type="text" name="dataset_name"
                            value="{{ old('dataset_name', $dataset->dataset_name) }}"
                            placeholder="Masukkan nama dataset"
                            class="w-full border rounded px-3 py-2 text-gray-900 focus:ring-2 focus:ring-[#0093DD] @error('dataset_name') border-red-500 ring-1 ring-red-300 @enderror">
                        @error('dataset_name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>



                    {{-- Tipe Insight --}}
                    {{-- PERBAIKAN DI SINI: Menggunakan syntax standar agar kompatibel semua versi --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Insight</label>
                        <select name="insight_type"
                            class="w-full border rounded px-3 py-2 bg-white text-gray-900 focus:ring-2 focus:ring-[#0093DD] @error('insight_type') border-red-500 ring-1 ring-red-300 @enderror">
                            <option value="default" {{ $dataset->insight_type == 'default' ? 'selected' : '' }}>Default</option>
                            <option value="percent_lower_is_better" {{ $dataset->insight_type == 'percent_lower_is_better' ? 'selected' : '' }}>Persen (Turun=Baik)</option>
                            <option value="percent_higher_is_better" {{ $dataset->insight_type == 'percent_higher_is_better' ? 'selected' : '' }}>Persen (Naik=Baik)</option>
                            <option value="number_lower_is_better" {{ $dataset->insight_type == 'number_lower_is_better' ? 'selected' : '' }}>Angka (Turun=Baik)</option>
                            <option value="number_higher_is_better" {{ $dataset->insight_type == 'number_higher_is_better' ? 'selected' : '' }}>Angka (Naik=Baik)</option>
                        </select>
                        @error('insight_type') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>


                    {{-- Mengambil config dataset --}}
                    @php
                        $configService = app(\App\Services\DatasetConfigService::class);
                        $allConfigs = $configService->getAllDatasets();
                        // Menggunakan closure standar function($cfg) agar aman di PHP versi lama
                        $configDataset = collect($allConfigs)->first(function($cfg) use ($dataset) {
                            return ($cfg['variable_id'] ?? null) == $dataset->dataset_code;
                        });
                        $configId = $configDataset['id'] ?? null;
                        $isEnabled = $configDataset['enabled'] ?? true;
                    @endphp

                </div>

                {{-- ====================== STATUS ENABLE + SYNC ====================== --}}
                @if($configId)

                <div class="border-t pt-6 mt-6">
                    <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                        <div>
                            <label class="font-medium text-gray-700">Status Dataset</label>
                            <p class="text-xs text-gray-500">Aktifkan / nonaktifkan sinkronisasi dataset.</p>
                        </div>

                        <label class="relative inline-flex items-center cursor-pointer">
                            {{-- INPUT CHECKBOX (Tetap hidden/sr-only) --}}
                            <input type="checkbox" id="enableToggle" class="sr-only peer" {{ $isEnabled ? 'checked' : '' }}>

                            {{-- GAMBAR SWITCH (Background & Knob) --}}
                            {{-- Saya ganti arbitrary values [2px] dengan kelas standar top-0.5 agar lebih aman --}}
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer 
                                        peer-checked:after:translate-x-full peer-checked:after:border-white 
                                        after:content-[''] after:absolute after:top-0.5 after:left-[2px] 
                                        after:bg-white after:border-gray-300 after:border after:rounded-full 
                                        after:h-5 after:w-5 after:transition-all 
                                        peer-checked:bg-[#68B92E]"></div>
                            
                            {{-- LABEL TEXT --}}
                            <span class="ml-3 text-sm font-medium text-gray-900" id="toggleLabel">
                                {{ $isEnabled ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </label>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <label class="font-medium text-gray-700 mb-3 block">Konfigurasi Tahun Data</label>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Tahun Mulai</label>
                            <input type="number" id="tahunMulai" min="1900" max="2100"
                                value="{{ $configDataset['tahun_mulai'] ?? 2020 }}"
                                class="w-full border rounded px-3 py-2 text-gray-900 focus:ring-2 focus:ring-[#0093DD]">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Tahun Akhir</label>
                            <input type="number" id="tahunAkhir" min="1900" max="2100"
                                value="{{ $configDataset['tahun_akhir'] ?? date('Y') }}"
                                class="w-full border rounded px-3 py-2 text-gray-900 focus:ring-2 focus:ring-[#0093DD]">
                        </div>
                    </div>
                    <button type="button" id="saveConfigBtn"
                        class="inline-flex items-center px-4 py-2 bg-[#0093DD] text-white rounded-md text-sm font-semibold hover:bg-[#0077B6] transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span id="saveConfigText">Simpan Konfigurasi</span>
                    </button>
                    <div id="configMessage" class="mt-2 text-sm hidden"></div>
                </div>


                <div class="border-t pt-6">
                    <label class="font-medium text-gray-700 mb-2">Sinkronisasi Manual</label>
                    <p class="text-xs text-gray-500 mb-3">Jalankan sync langsung tanpa menunggu cron.</p>

                    <button type="button" id="syncButton"
                        class="inline-flex items-center px-4 py-2 bg-[#68B92E] text-white rounded-md text-sm font-semibold hover:bg-[#5aa125] transition disabled:bg-gray-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span id="syncButtonText">Sync Dataset Sekarang</span>
                    </button>

                    <div id="syncMessage" class="mt-2 text-sm hidden"></div>
                </div>

                @endif


                {{-- ====================== ACTION BUTTON ====================== --}}
                <div class="mt-6 flex justify-end gap-2">
                    <a href="{{ route('admin.dashboard', request()->only(['category','subject','q','sort','order','page','per_page'])) }}"
                       class="px-4 py-2 bg-white border rounded-md text-sm hover:bg-gray-50">Batal</a>

                    <button type="submit"
                       class="px-4 py-2 bg-[#0093DD] text-white rounded-md text-sm font-semibold hover:bg-[#0077B6]">
                        Simpan
                    </button>
                </div>

            </form>
        </div> {{-- end card --}}
    </div> {{-- end wrapper --}}



    {{-- ====================== SCRIPT ====================== --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const configId = '{{ $configId ?? '' }}';
            if (!configId) return;

            const toggleCheckbox = document.getElementById('enableToggle');
            const toggleLabel    = document.getElementById('toggleLabel');
            const syncButton     = document.getElementById('syncButton');
            const syncButtonText = document.getElementById('syncButtonText');
            const syncMessage    = document.getElementById('syncMessage');
            
            const saveConfigBtn  = document.getElementById('saveConfigBtn');
            const saveConfigText = document.getElementById('saveConfigText');
            const configMessage  = document.getElementById('configMessage');
            const tahunMulai     = document.getElementById('tahunMulai');
            const tahunAkhir     = document.getElementById('tahunAkhir');


            // Save Config
            if (saveConfigBtn) {
                saveConfigBtn.addEventListener('click', function() {
                    const mulai = parseInt(tahunMulai.value);
                    const akhir = parseInt(tahunAkhir.value);
                    
                    if (mulai > akhir) {
                        showConfigMessage('Tahun mulai tidak boleh lebih besar dari tahun akhir', 'error');
                        return;
                    }

                    saveConfigBtn.disabled = true;
                    saveConfigText.textContent = 'Menyimpan...';

                    fetch(`/admin/datasets/${configId}/update-config`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            tahun_mulai: mulai,
                            tahun_akhir: akhir
                        })
                    })
                    .then(r => {
                        if (!r.ok) throw new Error(`HTTP ${r.status}`);
                        return r.json();
                    })
                    .then(d => {
                        showConfigMessage(d.message || 'Konfigurasi berhasil disimpan', d.success ? 'success' : 'error');
                    })
                    .catch((err) => showConfigMessage('Terjadi kesalahan: ' + err.message, 'error'))
                    .finally(() => {
                        saveConfigBtn.disabled = false;
                        saveConfigText.textContent = 'Simpan Konfigurasi';
                    });
                });
            }


            // Toggle Enable
            if (toggleCheckbox) {
                toggleCheckbox.addEventListener('change', function() {
                    const enabled = this.checked;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    
                    if (!csrfToken) {
                        showMessage('CSRF token tidak ditemukan','error');
                        this.checked = !enabled;
                        return;
                    }

                    fetch(`/admin/datasets/${configId}/toggle`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken.content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ enabled })
                    })
                    .then(r => {
                        if (!r.ok) throw new Error(`HTTP ${r.status}`);
                        return r.json();
                    })
                    .then(d => {
                        if (d.success) {
                            toggleLabel.textContent = enabled ? 'Aktif':'Nonaktif';
                            showMessage('Status berhasil diubah','success');
                        } else {
                            this.checked = !enabled;
                            showMessage(d.message || 'Gagal mengubah status','error');
                        }
                    })
                    .catch((error) => {
                        this.checked = !enabled;
                        showMessage('Terjadi kesalahan: ' + error.message,'error');
                    });
                });
            }


            // Manual Sync
            if (syncButton) {
                syncButton.addEventListener('click', () => {

                    if (!toggleCheckbox.checked)
                        return showMessage('Aktifkan dataset terlebih dahulu','error');

                    syncButton.disabled = true;
                    syncButtonText.textContent = 'Memproses...';

                    fetch(`/admin/datasets/${configId}/sync`, {
                        method: 'POST',
                        headers:{
                            'Content-Type':'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(r=>{
                        if(!r.ok) throw new Error(`HTTP ${r.status}`);
                        return r.json();
                    })
                    .then(d=>{
                        showMessage(d.message || 'Sinkronisasi berhasil dimulai', d.success?'success':'error');
                    })
                    .catch((err)=> showMessage('Terjadi kesalahan: ' + err.message,'error'))
                    .finally(()=>{
                        syncButton.disabled = false;
                        syncButtonText.textContent = 'Sync Dataset Sekarang';
                    });
                });
            }


            function showMessage(msg,type){
                if(!syncMessage) return;
                syncMessage.textContent = msg;
                syncMessage.className = `mt-2 text-sm ${type=='success'?'text-green-600':'text-red-600'}`;
                syncMessage.classList.remove('hidden');
                setTimeout(()=>syncMessage.classList.add('hidden'),3500);
            }

            function showConfigMessage(msg,type){
                if(!configMessage) return;
                configMessage.textContent = msg;
                configMessage.className = `mt-2 text-sm ${type=='success'?'text-green-600':'text-red-600'}`;
                configMessage.classList.remove('hidden');
                setTimeout(()=>configMessage.classList.add('hidden'),3500);
            }
        });
    </script>
    @endpush

</x-app-layout>