<x-app-layout>
    <div class="bg-gray-100 min-h-screen">
        <x-slot name="header">
            <div class="flex items-center gap-3">
                <div class="w-1 h-8 bg-gradient-to-b from-[#0093DD] to-[#0070AA] rounded-full"></div>
                <h2 class="font-semibold text-xl text-[#0093DD] leading-tight">
                    {{ __('Manajemen Dataset BPS') }}
                </h2>
            </div>    
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                {{-- Status Messages --}}
                @if (session('status'))
                    <div x-data="{ show: true }" x-show="show"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         x-init="setTimeout(() => show = false, 5000)"
                         class="mb-6 p-4 bg-green-50 text-green-700 border-l-4 border-green-500 rounded-r-lg shadow-md flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">{{ session('status') }}</span>
                        </div>
                        <button @click="show = false" class="text-green-600 hover:text-green-800 transition flex-shrink-0">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                            </svg>
                        </button>
                    </div>
                @endif

                {{-- Dataset Table --}}
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Nama Dataset</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Variable ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Satuan</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tahun</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="datasets-tbody" class="divide-y divide-gray-200">
                                {{-- Akan diisi via JavaScript --}}
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Load datasets on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadDatasets();
        });

        function loadDatasets() {
            fetch('{{ route("admin.datasets.management.list") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderDatasets(data.data);
                    } else {
                        console.error('Failed to load datasets:', data.message);
                    }
                })
                .catch(error => console.error('Error loading datasets:', error));
        }

        function renderDatasets(datasets) {
            const tbody = document.getElementById('datasets-tbody');
            tbody.innerHTML = '';

            datasets.forEach(dataset => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50 transition';
                
                const statusBadge = dataset.enabled 
                    ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>'
                    : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Nonaktif</span>';

                row.innerHTML = `
                    <td class="px-6 py-4">${statusBadge}</td>
                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">${dataset.name}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">${dataset.variable_id}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">${dataset.unit || '-'}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">${dataset.tahun_mulai}-${dataset.tahun_akhir}</td>
                    <td class="px-6 py-4 text-sm">
                        <div class="flex items-center gap-2">
                            <button 
                                onclick="toggleDataset('${dataset.id}', ${!dataset.enabled})"
                                class="inline-flex items-center px-3 py-1.5 rounded text-xs font-medium transition ${
                                    dataset.enabled 
                                    ? 'bg-red-100 text-red-700 hover:bg-red-200'
                                    : 'bg-green-100 text-green-700 hover:bg-green-200'
                                }">
                                ${dataset.enabled ? '❌ Disable' : '✅ Enable'}
                            </button>
                            ${dataset.enabled 
                                ? `<button 
                                    onclick="syncDataset('${dataset.id}', '${dataset.name}')"
                                    class="inline-flex items-center px-3 py-1.5 rounded text-xs font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 transition">
                                    🔄 Sync
                                </button>`
                                : ''
                            }
                        </div>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function toggleDataset(datasetId, enabled) {
            if (!confirm(`Apakah Anda yakin ingin ${enabled ? 'mengaktifkan' : 'menonaktifkan'} dataset ini?`)) {
                return;
            }

            const formData = new FormData();
            formData.append('enabled', enabled ? 1 : 0);
            formData.append('_token', '{{ csrf_token() }}');

            fetch(`{{ route('admin.datasets.toggle', '') }}/${datasetId}`, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    loadDatasets();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            });
        }

        function syncDataset(datasetId, datasetName) {
            if (!confirm(`Sinkronisasi dataset "${datasetName}"?\n\nIni akan mengambil data terbaru dari BPS API.`)) {
                return;
            }

            const btn = event.target;
            btn.disabled = true;
            btn.innerHTML = '⏳ Syncing...';

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');

            fetch(`{{ route('admin.datasets.sync', '') }}/${datasetId}`, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                } else {
                    alert('Error: ' + data.message);
                }
                btn.disabled = false;
                btn.innerHTML = '🔄 Sync';
                loadDatasets();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
                btn.disabled = false;
                btn.innerHTML = '🔄 Sync';
            });
        }
    </script>
</x-app-layout>
