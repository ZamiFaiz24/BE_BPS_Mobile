<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Dataset: {{ $dataset->dataset_name }}
        </h2>
    </x-slot>
    <div class="max-w-xl mx-auto mt-8 bg-white p-6 rounded shadow">
        <form method="POST" action="{{ route('admin.datasets.update', $dataset) }}">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label class="block font-semibold mb-1">Tipe Insight</label>
                <select name="insight_type" class="w-full border rounded px-3 py-2">
                    <option value="default" @selected($dataset->insight_type == 'default')>Default</option>
                    <option value="percent_lower_is_better" @selected($dataset->insight_type == 'percent_lower_is_better')>Persen (Turun=Baik)</option>
                    <option value="percent_higher_is_better" @selected($dataset->insight_type == 'percent_higher_is_better')>Persen (Naik=Baik)</option>
                    <option value="number_lower_is_better" @selected($dataset->insight_type == 'number_lower_is_better')>Angka (Turun=Baik)</option>
                    <option value="number_higher_is_better" @selected($dataset->insight_type == 'number_higher_is_better')>Angka (Naik=Baik)</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('admin.dashboard') }}" class="ml-2 text-gray-600 underline">Batal</a>
        </form>
    </div>
</x-app-layout>