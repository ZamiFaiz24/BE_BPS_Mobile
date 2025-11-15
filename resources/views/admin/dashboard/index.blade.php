@extends('admin.layout')

@section('content')
<div class="p-6 bg-white rounded-lg shadow-md">
    {{-- Header dengan Tombol Aksi --}}
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Dataset</h1>
        
        {{-- Tombol Sync: Gunakan izin 'run sync' --}}
        @can('run sync')
        <div class="flex gap-3">
            <form method="POST" action="{{ route('admin.sync.all') }}" class="inline">
                @csrf
                <button type="submit" 
                        onclick="return confirm('Yakin ingin sinkronisasi semua dataset?')"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm flex items-center gap-2">
                    <svg class...></svg>
                    Sync Semua
                </button>
            </form>
            
            <form method="POST" action="{{ route('admin.sync.manual') }}" class="inline">
                @csrf
                <button type="submit" 
                        onclick="return confirm('Yakin ingin sinkronisasi manual?')"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow-sm flex items-center gap-2">
                    <svg class...></svg>
                    Sync Manual
                </button>
            </form>
        </div>
        @endcan
    </div>

    {{-- Tabel Dataset --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dataset</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($datasets as $dataset)
                <tr>
                    <td class="px-6 py-4">{{ $dataset->title }}</td>
                    <td class="px-6 py-4">
                        <span class ...>
                            {{ $dataset->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 flex gap-2">
                        
                        {{-- Tombol Lihat (semua role yang punya 'view datasets' bisa) --}}
                        <a href="{{ route('admin.datasets.show', $dataset->id) }}" class="text-blue-600 hover:text-blue-800">
                            👁️ Lihat
                        </a>
                        
                        {{-- Tombol Edit: Gunakan izin 'edit datasets' --}}
                        @can('edit datasets')
                        <a href="{{ route('admin.datasets.edit', $dataset->id) }}" class="text-yellow-600 hover:text-yellow-800">
                            ✏️ Edit
                        </a>
                        @endcan
                        
                        {{-- Tombol Hapus: Gunakan izin 'delete datasets' --}}
                        @can('delete datasets')
                        <form method="POST" action="{{ route('admin.datasets.destroy', $dataset->id) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Yakin hapus dataset ini?')">
                                🗑️ Hapus
                            </button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection