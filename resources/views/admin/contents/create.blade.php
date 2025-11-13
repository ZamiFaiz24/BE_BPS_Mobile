<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#0093DD] leading-tight">
            {{ __('Manajemen Konten') }}
        </h2>
    </x-slot>

    <div class="max-w-xl mx-auto mt-8">
	{{-- Wrapper card mirip halaman settings --}}
	<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
		<h2 class="text-2xl font-bold mb-4 text-[#0093DD]">Tambah Konten Baru</h2>

		{{-- Errors --}}
		@if ($errors->any())
            <div class="bg-[#EB891C]/10 border border-[#EB891C] text-[#EB891C] px-4 py-3 rounded-lg mb-4" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline">Ada beberapa masalah dengan input Anda.</span>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

		<form action="{{ route('admin.contents.store') }}" method="POST" enctype="multipart/form-data" x-data="{ type: '{{ old('type', '') }}', uploadMethod: '{{ old('upload_method', 'url') }}' }">
			@csrf

			{{-- General info (card) --}}
			<div class="mb-6">
				<div class="mb-3">
					<label class="block text-sm font-medium text-gray-700 mb-1">Tipe Konten <span class="text-red-500">*</span></label>
					<select name="type" id="type" x-model="type"
						class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-[#0093DD]">
						<option value="">-- Pilih Tipe --</option>
						<option value="news" {{ old('type') == 'news' ? 'selected' : '' }}>Berita</option>
						<option value="press_release" {{ old('type') == 'press_release' ? 'selected' : '' }}>Siaran Pers</option>
						<option value="publication" {{ old('type') == 'publication' ? 'selected' : '' }}>Publikasi</option>
						<option value="infographic" {{ old('type') == 'infographic' ? 'selected' : '' }}>Infografik</option>
					</select>
				</div>

				<div>
					<label class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-red-500">*</span></label>
					<input type="text" name="title" value="{{ old('title') }}"
						class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-[#0093DD]" required>
				</div>
			</div>

			{{-- Separator + Type-specific cards --}}
			<div class="space-y-4">

				{{-- NEWS --}}
				<div x-show="type === 'news'" x-cloak class="bg-gray-50 rounded-md p-4 border border-gray-100">
					<p class="text-sm font-medium text-gray-700 mb-3">Berita</p>
					<div class="grid grid-cols-1 gap-3">
						<input-group label="Tanggal" type="date" name="date" value="{{ old('date') }}"></input-group>
						<label class="text-sm text-gray-600">Kategori</label>
						<input type="text" name="category" value="{{ old('category') }}" class="w-full border rounded px-3 py-2">
						<label class="text-sm text-gray-600">Abstrak</label>
						<textarea name="abstract" rows="3" class="w-full border rounded px-3 py-2">{{ old('abstract') }}</textarea>

						<label class="text-sm text-gray-600">Thumbnail (URL atau upload)</label>
						<div class="flex gap-2">
							<input type="text" name="thumbnail" placeholder="URL thumbnail..." value="{{ old('thumbnail') }}" class="flex-1 border rounded px-3 py-2">
							<div class="w-48">
								<label for="thumbnail_file" class="cursor-pointer inline-flex items-center justify-center w-full px-3 py-2 border border-gray-300 bg-white rounded-md text-sm">
									Browse...
								</label>
								<input id="thumbnail_file" type="file" name="thumbnail_file" class="hidden"
									onchange="document.getElementById('thumb_name').textContent = this.files[0] ? this.files[0].name : 'No file selected.'">
								<div id="thumb_name" class="text-xs text-gray-500 mt-1 truncate">No file selected.</div>
							</div>
						</div>

						<label class="text-sm text-gray-600">Link (sumber)</label>
						<input type="url" name="link" value="{{ old('link') }}" class="w-full border rounded px-3 py-2" required>

						<label class="text-sm text-gray-600">Isi (HTML)</label>
						<textarea name="content_html" rows="5" class="w-full border rounded px-3 py-2">{{ old('content_html') }}</textarea>
					</div>
				</div>

				{{-- PRESS RELEASE --}}
				<div x-show="type === 'press_release'" x-cloak class="bg-gray-50 rounded-md p-4 border border-gray-100">
					<p class="text-sm font-medium text-gray-700 mb-3">Siaran Pers</p>
					<div class="grid grid-cols-1 gap-3">
						<input type="date" name="date" value="{{ old('date') }}" class="w-full border rounded px-3 py-2">
						<textarea name="abstract" rows="3" class="w-full border rounded px-3 py-2" placeholder="Abstrak...">{{ old('abstract') }}</textarea>

						<label class="text-sm text-gray-600">Thumbnail</label>
						<div class="flex gap-2">
							<input type="text" name="thumbnail" value="{{ old('thumbnail') }}" class="flex-1 border rounded px-3 py-2" placeholder="URL thumbnail...">
							<input id="pr_thumb_file" type="file" name="thumbnail_file" class="hidden" onchange="document.getElementById('pr_thumb_name').textContent = this.files[0] ? this.files[0].name : 'No file selected.'">
							<label for="pr_thumb_file" class="cursor-pointer inline-flex items-center justify-center px-3 py-2 border border-gray-300 bg-white rounded-md text-sm">Browse...</label>
							<div id="pr_thumb_name" class="text-xs text-gray-500 mt-1 truncate">No file selected.</div>
						</div>

						<label class="text-sm text-gray-600">PDF (URL)</label>
						<input type="text" name="pdf" value="{{ old('pdf') }}" class="w-full border rounded px-3 py-2">
						<input id="pr_pdf_file" type="file" name="pdf_file" class="hidden" onchange="document.getElementById('pr_pdf_name').textContent = this.files[0] ? this.files[0].name : 'No file selected.'">
						<label for="pr_pdf_file" class="cursor-pointer inline-flex items-center mt-1 px-3 py-2 border border-gray-300 bg-white rounded-md text-sm">Upload PDF</label>
						<div id="pr_pdf_name" class="text-xs text-gray-500 mt-1 truncate">No file selected.</div>

						<input type="text" name="category" value="{{ old('category') }}" class="w-full border rounded px-3 py-2" placeholder="Kategori...">
						<input type="url" name="link" value="{{ old('link') }}" class="w-full border rounded px-3 py-2" placeholder="Link unik..." required>
						<textarea name="content_html" rows="5" class="w-full border rounded px-3 py-2">{{ old('content_html') }}</textarea>
					</div>
				</div>

				{{-- PUBLICATION --}}
				<div x-show="type === 'publication'" x-cloak class="bg-gray-50 rounded-md p-4 border border-gray-100">
					<p class="text-sm font-medium text-gray-700 mb-3">Publikasi</p>
					<div class="grid grid-cols-1 gap-3">
						<input type="date" name="date" value="{{ old('date') }}" class="w-full border rounded px-3 py-2">
						<input type="text" name="subject" value="{{ old('subject') }}" class="w-full border rounded px-3 py-2" placeholder="Subject / Kategori">
						<div class="flex gap-2">
							<input type="text" name="cover" value="{{ old('cover') }}" class="flex-1 border rounded px-3 py-2" placeholder="Cover URL...">
							<input id="pub_cover_file" type="file" name="cover_file" class="hidden" onchange="document.getElementById('pub_cover_name').textContent = this.files[0] ? this.files[0].name : 'No file selected.'">
							<label for="pub_cover_file" class="cursor-pointer inline-flex items-center justify-center px-3 py-2 border border-gray-300 bg-white rounded-md text-sm">Browse...</label>
						</div>
						<div id="pub_cover_name" class="text-xs text-gray-500 mt-1 truncate">No file selected.</div>
						<input type="text" name="pdf" value="{{ old('pdf') }}" class="w-full border rounded px-3 py-2" placeholder="PDF URL...">
						<input type="url" name="link" value="{{ old('link') }}" class="w-full border rounded px-3 py-2" placeholder="Link unik..." required>
						<textarea name="abstract" rows="4" class="w-full border rounded px-3 py-2">{{ old('abstract') }}</textarea>
					</div>
				</div>

				{{-- INFOGRAPHIC --}}
				<div x-show="type === 'infographic'" x-cloak class="bg-gray-50 rounded-md p-4 border border-gray-100">
					<p class="text-sm font-medium text-gray-700 mb-3">Infografik</p>
					<div class="grid grid-cols-1 gap-3">
						<input type="date" name="date" value="{{ old('date') }}" class="w-full border rounded px-3 py-2">
						<input type="text" name="category" value="{{ old('category') }}" class="w-full border rounded px-3 py-2" placeholder="Kategori...">
						<div class="flex gap-2">
							<input type="text" name="infographic" value="{{ old('infographic') }}" class="flex-1 border rounded px-3 py-2" placeholder="Image URL...">
							<input id="inf_file" type="file" name="infographic_file" class="hidden" onchange="document.getElementById('inf_name').textContent = this.files[0] ? this.files[0].name : 'No file selected.'">
							<label for="inf_file" class="cursor-pointer inline-flex items-center justify-center px-3 py-2 border border-gray-300 bg-white rounded-md text-sm">Browse...</label>
						</div>
						<div id="inf_name" class="text-xs text-gray-500 mt-1 truncate">No file selected.</div>
						<textarea name="description" rows="3" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
						<input type="url" name="link" value="{{ old('link') }}" class="w-full border rounded px-3 py-2" placeholder="Link unik..." required>
					</div>
				</div>

			</div>

			{{-- Actions --}}
			<div class="mt-6 flex items-center justify-between">
				<a href="{{ route('admin.contents.index') }}" class="px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">Kembali ke Daftar Konten</a>
				<button type="submit" class="px-5 py-2 bg-[#0093DD] text-white rounded-md font-semibold hover:bg-[#0070C0]">Simpan</button>
			</div>
		</form>
	</div>
</div>
</x-app-layout>