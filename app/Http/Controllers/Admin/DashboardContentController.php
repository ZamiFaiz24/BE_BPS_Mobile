<?php

namespace App\Http\Controllers\Admin;

use App\Models\BpsContent; // Pastikan model sesuai dengan tabel
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardContentController extends Controller
{
    /**
     * Menampilkan daftar semua konten. (READ)
     */
    public function index(Request $request)
    {
        $query = \App\Models\BpsContent::query();

        // Filter kategori
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Search judul
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        $contents = $query->latest()->paginate(10)->withQueryString();

        return view('admin.contents.index', compact('contents'));
    }
    /**
     * Menampilkan form untuk membuat konten baru. (CREATE form)
     */
    public function create()
    {
        return view('admin.contents.create');
    }

    /**
     * Menyimpan konten baru ke database. (CREATE logic)
     */
    public function store(Request $request)
    {
        // Tambahkan validasi untuk file upload
        $validatedData = $request->validate([
            'title'            => 'required|string|max:255',
            'type'             => 'required|in:berita,publikasi,infografik',
            'image_source_url' => 'nullable|url|required_if:upload_method,url',
            'image_upload'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // max 2MB
            // ... validasi field lainnya
        ]);

        $imagePath = null;

        // PRIORITAS 1: Cek apakah ada file yang di-upload
        if ($request->hasFile('image_upload')) {
            $file = $request->file('image_upload');
            $newFileName = Str::random(10) . '_' . time() . '.' . $file->getClientOriginalExtension();
            // Simpan file dan dapatkan path-nya
            $imagePath = $file->storeAs('images', $newFileName, 'public');
        }
        // PRIORITAS 2: Jika tidak ada upload, cek apakah ada URL yang diisi
        elseif ($request->filled('image_source_url')) {
            try {
                $response = Http::withHeaders([ /* ... User-Agent ... */])->get($request->input('image_source_url'));

                if ($response->successful()) {
                    $imageContents = $response->body();
                    $newFileName = Str::random(10) . '_' . time() . '.jpg';
                    $imagePath = 'images/' . $newFileName;
                    Storage::disk('public')->put($imagePath, $imageContents);
                } else {
                    return back()->withInput()->withErrors(['image_source_url' => 'URL tidak valid atau diblokir (Status: ' . $response->status() . ').']);
                }
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(['image_source_url' => 'Gagal mengunduh dari URL.']);
            }
        }

        // Simpan path gambar ke array data jika berhasil didapatkan
        if ($imagePath) {
            $validatedData['image_url'] = $imagePath;
        }

        // Hapus field temporary
        unset($validatedData['image_source_url'], $validatedData['image_upload']);

        BpsContent::create($validatedData);

        return redirect()->route('admin.contents.index')->with('success', 'Konten baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu konten. (Opsional untuk admin)
     */
    public function show(BpsContent $content)
    {
        return view('admin.contents.show', compact('content'));
    }

    /**
     * Menampilkan form untuk mengedit konten. (UPDATE form)
     */
    public function edit(BpsContent $content)
    {
        return view('admin.contents.edit', compact('content'));
    }

    /**
     * Mengupdate konten yang ada di database. (UPDATE logic)
     */
    public function update(Request $request, BpsContent $content)
    {
        // Validasi input (sama seperti di store)
        $validatedData = $request->validate([
            'title'         => 'required|string|max:255',
            'content_body'  => 'nullable|string',
            'file_url'      => 'nullable|url',
            'image_url'     => 'nullable|url',
            'author'        => 'nullable|string|max:255',
            'publish_date'  => 'nullable|date',
            'source_url'    => 'nullable|url',
            'description'   => 'nullable|string',
            'type'          => 'required|in:berita,publikasi,infografik',
        ]);

        // Jika upload gambar dari URL eksternal, download dan simpan ke storage, lalu simpan path ke image_url
        if ($request->filled('image_url') && filter_var($request->image_url, FILTER_VALIDATE_URL)) {
            try {
                $imageContents = Http::get($request->image_url)->body();
                $newFileName = Str::random(10) . '_' . time() . '.jpg';
                $path = 'images/' . $newFileName;
                Storage::disk('public')->put($path, $imageContents);
                $validatedData['image_url'] = $path;
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(['image_url' => 'Gagal mengunduh gambar. Pastikan URL valid dan dapat diakses.']);
            }
        }

        $content->update($validatedData);

        return redirect()->route('admin.contents.index')->with('success', 'Konten berhasil diperbarui.');
    }

    /**
     * Menghapus konten dari database. (DELETE)
     */
    public function destroy(BpsContent $content)
    {
        // Hapus file gambar dari storage jika ada
        if ($content->image_url) {
            Storage::disk('public')->delete($content->image_url);
        }

        $content->delete();

        return redirect()->route('admin.contents.index')->with('success', 'Konten berhasil dihapus.');
    }
}
