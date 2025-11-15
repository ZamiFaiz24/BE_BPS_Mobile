<x-app-layout>
    {{-- Header dengan Tombol Aksi --}}
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Dataset</h1>
        
        {{-- Tombol Sync: HANYA Super Admin --}}
        @can('view settings')
        <div class="flex gap-3">
            <form method="POST" action="{{ route('admin.sync.all') }}" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    🔄 Sync Semua
                </button>
            </form>
        </div>
        @endcan
    </div>

    {{-- Tabel Dataset --}}
    <table class="min-w-full">
        <thead>
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($datasets as $dataset)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $dataset->title }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.datasets.show', $dataset) }}" class="text-blue-600 hover:text-blue-800">👁️ Lihat</a>
                        
                        @can('view content')
                        <a href="{{ route('admin.datasets.edit', $dataset) }}" class="text-yellow-600 hover:text-yellow-800">✏️ Edit</a>
                        @endcan
                        
                        {{-- Hapus: HANYA Super Admin --}}
                        @can('view settings')
                        <form method="POST" action="{{ route('admin.datasets.destroy', $dataset) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin hapus?')" class="text-red-600 hover:text-red-800">
                                🗑️ Hapus
                            </button>
                        </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-app-layout>