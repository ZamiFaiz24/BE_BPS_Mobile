{{-- resources/views/admin/contents/partials/table.blade.php --}}
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-[#0093DD]/20">
        <thead style="background-color: #0093DD;">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Judul</th>
                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Tipe</th>
                {{-- <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Kategori</th> --}}
                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Tanggal</th>
                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Gambar</th>
                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-[#0093DD]/10">
            @php
                // Logic sorting sederhana untuk tampilan (jika belum handle di controller)
                $items = $contents->items(); // Gunakan items() untuk pagination
            @endphp

            @forelse ($items as $content)
                <tr>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($content->title, 60) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @php
                            $typeConfig = match($content->type) {
                                'news' => ['label' => 'Berita', 'class' => 'bg-[#0093DD] bg-opacity-10 text-[#0093DD]'],
                                'press_release' => ['label' => 'Siaran Pers', 'class' => 'bg-[#68B92E] bg-opacity-10 text-[#68B92E]'],
                                'publication' => ['label' => 'Publikasi', 'class' => 'bg-[#EB891C] bg-opacity-10 text-[#EB891C]'],
                                'infographic' => ['label' => 'Infografik', 'class' => 'bg-purple-100 text-purple-800'],
                                default => ['label' => 'Lainnya', 'class' => 'bg-gray-100 text-gray-800']
                            };
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $typeConfig['class'] }}">
                            {{ $typeConfig['label'] }}
                        </span>
                    </td>
                    {{-- <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                        {{ $content->category ?? '-' }}
                    </td> --}}
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                        @php
                            try {
                                if ($content->publish_date) {
                                    // Coba format tanggalnya
                                    echo \Carbon\Carbon::parse($content->publish_date)
                                        ->locale('id')
                                        ->translatedFormat('d M Y');
                                } else {
                                    echo '-';
                                }
                            } catch (\Exception $e) {
                                // Jika gagal (karena format teks), tampilkan teks aslinya
                                echo $content->publish_date;
                            }
                        @endphp
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if ($content->image_url)
                            <img src="{{ $content->image_url }}" alt="thumb" class="h-10 w-16 object-cover rounded border border-[#0093DD]/30 mx-auto">
                        @else
                            <span class="text-gray-400 text-xs">N/A</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex items-center justify-center gap-2">
                            @if($content->link)
                                <a href="{{ $content->link }}" target="_blank" class="text-[#0093DD] hover:text-[#68B92E] inline-flex items-center justify-center h-9 w-9 rounded-md hover:bg-gray-50 transition" title="Lihat">
                                    <x-heroicon-o-eye class="w-5 h-5" />
                                </a>
                            @endif
                            <a href="{{ route('admin.contents.edit', array_merge(['content' => $content->id, 'type' => $content->type], request()->only(['type', 'category', 'q', 'sort', 'page', 'per_page']))) }}" 
                            class="text-[#EB891C] hover:text-[#D97706] inline-flex items-center justify-center h-9 w-9 rounded-md hover:bg-orange-50 transition" 
                            title="Edit">
                                <x-heroicon-o-pencil class="w-5 h-5" />
                            </a>
                            <form action="{{ route('admin.contents.destroy', $content->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus konten ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[#EB891C] hover:text-red-700 inline-flex items-center justify-center h-9 w-9 rounded-md hover:bg-gray-50 transition" title="Hapus">
                                    <x-heroicon-o-trash class="w-5 h-5" />
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data konten.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>