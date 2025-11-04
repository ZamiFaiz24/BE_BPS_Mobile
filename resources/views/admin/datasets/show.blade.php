<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Data: {{ $dataset->dataset_name }}
        </h2>
    </x-slot> --}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-[#0093DD] rounded-md font-semibold text-xs text-[#0093DD] uppercase tracking-widest hover:bg-[#0093DD] hover:text-white transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Kembali
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{-- Header Card --}}
                <div class="bg-[#0093DD] px-6 py-4 border-b border-[#0077B6]">
                    <h3 class="text-lg font-semibold text-white">Tabel Data {{ $dataset->dataset_name }}</h3>
                </div>

                @php
                    // Kunci kemungkinan field "unit" di DB
                    $unitKeys = ['unit', 'satuan', 'unit_name', 'unit_label'];

                    // Ambil unit dari dataset bila ada
                    $datasetUnit = null;
                    foreach ($unitKeys as $k) {
                        if (isset($dataset->$k) && $dataset->$k !== '') { $datasetUnit = $dataset->$k; break; }
                    }

                    // Kumpulkan unit dari nilai
                    $units = $values->map(function ($item) use ($unitKeys) {
                        foreach ($unitKeys as $k) {
                            if (isset($item->$k) && $item->$k !== '') return $item->$k;
                        }
                        return null;
                    })->filter()->unique()->values();

                    // Tentukan apakah cukup tampilkan sekali atau perlu kolom
                    $singleUnit = $datasetUnit ? $datasetUnit : ($units->count() === 1 ? $units->first() : null);
                    $showUnitColumn = !$singleUnit && $units->count() > 0;

                    // Lookup unit per kombinasi vervar|turvar untuk kolom satuan per baris
                    $unitLookup = [];
                    foreach ($values as $item) {
                        $key = ($item->vervar_label ?? '') . '||' . ($item->turvar_label ?? '');
                        foreach ($unitKeys as $k) {
                            if (isset($item->$k) && $item->$k !== '') { $unitLookup[$key] = $item->$k; break; }
                        }
                    }
                @endphp

                @if ($singleUnit)
                    <div class="px-6 py-3 border-b bg-white text-sm text-gray-600">
                        Satuan: <strong class="text-gray-900">{{ $singleUnit }}</strong>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    @if ($values->isEmpty())
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Label Variabel</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Label Turunan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <p class="text-gray-500 font-medium">Belum ada data nilai untuk dataset ini</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        @php
                            $years = $values->pluck('year')->unique()->sort();

                            $groupedData = $values->groupBy('vervar_label')->map(function ($vervarGroup) {
                                return $vervarGroup->groupBy('turvar_label')->map(function ($turvarGroup) {
                                    return $turvarGroup->keyBy('year')->map(function ($item) {
                                        return $item->value;
                                    });
                                });
                            });

                            // Filter hanya turvar yang punya minimal 1 nilai
                            $groupedData = $groupedData->map(function ($turvarGroups) {
                                return $turvarGroups->filter(function ($yearValues) {
                                    return $yearValues->filter()->isNotEmpty();
                                });
                            })->filter(function ($turvarGroups) {
                                return $turvarGroups->isNotEmpty();
                            });
                        @endphp

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                        Label Variabel
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                        Label Turunan
                                    </th>
                                    @if ($showUnitColumn)
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                            Satuan
                                        </th>
                                    @endif
                                    @foreach ($years as $year)
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            {{ $year }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($groupedData as $vervarLabel => $turvarGroups)
                                    @foreach ($turvarGroups as $turvarLabel => $yearValues)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            @if ($loop->first)
                                                <td class="px-6 py-4 text-sm font-medium text-gray-900 border-r border-gray-200" rowspan="{{ $turvarGroups->count() }}">
                                                    {{ $vervarLabel }}
                                                </td>
                                            @endif

                                            <td class="px-6 py-4 text-sm text-gray-600 border-r border-gray-200">
                                                {{ $turvarLabel }}
                                            </td>

                                            @if ($showUnitColumn)
                                                @php
                                                    $key = $vervarLabel . '||' . $turvarLabel;
                                                    $rowUnit = isset($unitLookup[$key]) ? $unitLookup[$key] : null;
                                                @endphp
                                                <td class="px-6 py-4 text-sm text-gray-600 border-r border-gray-200">
                                                    {{ $rowUnit ?? '-' }}
                                                </td>
                                            @endif
                                            
                                            @foreach ($years as $year)
                                                <td class="px-6 py-4 text-center text-sm">
                                                    @if (isset($yearValues[$year]))
                                                        <span class="text-gray-900 font-medium">
                                                            {{ number_format($yearValues[$year], 2, ',', '.') }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-300">—</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                {{-- Footer Info --}}
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                    <div class="flex items-center justify-between text-xs text-gray-600">
                        <span>Total: <strong class="text-gray-900">{{ $values->count() }}</strong> data</span>
                        @php $yearsLocal = isset($years) ? $years : collect(); @endphp
                        @if($yearsLocal->isNotEmpty())
                            <span>Periode: <strong class="text-gray-900">{{ $yearsLocal->first() }}</strong> - <strong class="text-gray-900">{{ $yearsLocal->last() }}</strong></span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>