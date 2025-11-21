<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\News;
use App\Models\PressRelease;
use App\Models\Publication;
use App\Models\Infographic;

class DashboardContentController extends Controller
{
    /**
     * Menampilkan daftar semua konten dari 4 tabel (READ)
     */
    public function index(Request $request)
    {
        // --- 1. QUERY UNION (GABUNGAN 4 TABEL) ---
        $newsQuery = DB::table('news')->select('id', 'title', DB::raw("'news' as type"), 'date as publish_date', 'category', 'thumbnail_url as image_url', 'link', 'created_at');
        $pressQuery = DB::table('press_releases')->select('id', 'title', DB::raw("'press_release' as type"), 'date as publish_date', 'category', 'thumbnail_url as image_url', 'link', 'created_at');
        $publicationQuery = DB::table('publications')->select('id', 'title', DB::raw("'publication' as type"), 'release_date as publish_date', 'category', 'cover_url as image_url', 'link', 'created_at');
        $infographicQuery = DB::table('infographics')->select('id', 'title', DB::raw("'infographic' as type"), 'date as publish_date', 'category', 'image_url', DB::raw("NULL as link"), 'created_at');

        // Union semua query
        $unionQuery = $newsQuery
            ->unionAll($pressQuery)
            ->unionAll($publicationQuery)
            ->unionAll($infographicQuery);

        // Wrap dalam subquery agar bisa difilter & sort
        $query = DB::table(DB::raw("({$unionQuery->toSql()}) as contents"))
            ->mergeBindings($unionQuery);

        // --- 2. FILTERING ---

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Search by title
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', 'like', '%' . $request->category . '%');
        }

        // --- 3. SORTING DINAMIS (PERBAIKAN UTAMA) ---

        // Ambil input sort dari dropdown (default: publish_date_desc)
        $sort = $request->input('sort', 'publish_date_desc');

        // Terapkan logika sorting
        if ($sort == 'publish_date_asc') {
            $query->orderBy('publish_date', 'asc'); // Terlama
        } else {
            $query->orderBy('publish_date', 'desc'); // Terbaru
        }

        // --- 4. PAGINATION ---
        $perPage = $request->input('per_page', 10);

        $contents = $query
            ->paginate($perPage)
            ->appends($request->all());

        // --- 5. DATA TAMBAHAN (OPSIONAL: UNTUK KARTU STATISTIK) ---
        // Agar kartu di atas tabel menampilkan jumlah yang benar
        $typeCounts = [
            'news' => \App\Models\News::count(),
            'press_release' => \App\Models\PressRelease::count(),
            'publication' => \App\Models\Publication::count(),
            'infographic' => \App\Models\Infographic::count(),
        ];

        return view('admin.contents.index', compact('contents', 'typeCounts'));
    }

    /**
     * Menghapus konten berdasarkan type dan id
     */
    public function destroy(Request $request, $id)
    {
        $type = $request->input('type');

        try {
            switch ($type) {
                case 'news':
                    News::findOrFail($id)->delete();
                    break;
                case 'press_release':
                    PressRelease::findOrFail($id)->delete();
                    break;
                case 'publication':
                    Publication::findOrFail($id)->delete();
                    break;
                case 'infographic':
                    Infographic::findOrFail($id)->delete();
                    break;
                default:
                    return back()->withErrors(['error' => 'Tipe konten tidak valid.']);
            }

            return redirect()->route('admin.contents.index')->with('success', 'Konten berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus konten: ' . $e->getMessage()]);
        }
    }

    /**
     * Menampilkan detail konten berdasarkan type dan id
     */
    public function show(Request $request, $id)
    {
        $type = $request->input('type');

        try {
            $content = match ($type) {
                'news' => News::findOrFail($id),
                'press_release' => PressRelease::findOrFail($id),
                'publication' => Publication::findOrFail($id),
                'infographic' => Infographic::findOrFail($id),
                default => abort(404, 'Tipe konten tidak valid')
            };

            return view('admin.contents.show', compact('content', 'type'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Konten tidak ditemukan.']);
        }
    }

    /**
     * Show the form to create a new content item.
     */
    public function create()
    {
        // Jika view berada di resources/views/admin/contents/create.blade.php
        return view('admin.contents.create');
    }

    /**
     * Menampilkan form edit
     */
    public function edit(Request $request, $id)
    {
        $type = $request->input('type');

        try {
            $content = match ($type) {
                'news' => News::findOrFail($id),
                'press_release' => PressRelease::findOrFail($id),
                'publication' => Publication::findOrFail($id),
                'infographic' => Infographic::findOrFail($id),
                default => abort(404, 'Tipe konten tidak valid')
            };

            // --- MAPPING DATA KE VARIABLE UMUM ---
            // Agar di view kita cukup panggil 'publish_date', 'image_url', 'abstract_text', 'desc_text'

            // 1. Mapping Tanggal & Gambar (Sama seperti sebelumnya)
            if ($type === 'publication') {
                $content->publish_date = $content->release_date;
                $content->image_url = $content->cover_url;
            } elseif ($type === 'infographic') {
                $content->publish_date = $content->date;
            } else {
                $content->publish_date = $content->date;
                $content->image_url = $content->thumbnail_url;
            }

            // 2. Mapping Abstraksi & Deskripsi (BARU)
            // Kita buat atribut sementara agar view tidak bingung
            if ($type === 'publication') {
                $content->abstract_text = $content->abstract; // Kolom database: abstract
                $content->desc_text = null; // Publikasi jarang punya 'content' panjang selain abstrak
            } elseif ($type === 'news' || $type === 'press_release') {
                $content->abstract_text = null;
                $content->desc_text = $content->content; // Kolom database: content
            } elseif ($type === 'infographic') {
                $content->abstract_text = null;
                $content->desc_text = $content->description; // Kolom database: description
            }

            return view('admin.contents.edit', compact('content', 'type'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Konten tidak ditemukan.']);
        }
    }

    /**
     * Update data ke database
     */
    public function update(Request $request, $id)
    {
        $type = $request->input('type');

        // 1. VALIDASI TAMBAHAN (Abstraksi & Deskripsi)
        $rules = [
            'title' => 'required|string|max:255',
            'category' => 'nullable|string',
            'publish_date' => 'nullable|date',
            'link' => 'nullable|url',
            'image_url' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            // Validasi Baru
            'abstract' => 'nullable|string', // Untuk input abstraksi
            'description' => 'nullable|string', // Untuk input deskripsi/konten utama
        ];

        $request->validate($rules);

        // 2. Handle Image Upload atau URL
        $imageUrl = $request->image_url;

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('content_images', $filename, 'public');
            $imageUrl = asset('storage/' . $path);
        }

        try {
            switch ($type) {
                case 'news':
                    $item = News::findOrFail($id);
                    $item->update([
                        'title' => $request->title,
                        'category' => $request->category,
                        'date' => $request->publish_date,
                        'link' => $request->link,
                        'thumbnail_url' => $imageUrl,

                        // Simpan Deskripsi ke kolom 'content'
                        'content' => $request->description,
                    ]);
                    break;

                case 'press_release':
                    $item = PressRelease::findOrFail($id);
                    $item->update([
                        'title' => $request->title,
                        'category' => $request->category,
                        'date' => $request->publish_date,
                        'link' => $request->link,
                        'thumbnail_url' => $imageUrl,

                        // Simpan Deskripsi ke kolom 'content'
                        'content' => $request->description,
                    ]);
                    break;

                case 'publication':
                    $item = Publication::findOrFail($id);
                    $item->update([
                        'title' => $request->title,
                        'category' => $request->category,
                        'release_date' => $request->publish_date,
                        'link' => $request->link,
                        'cover_url' => $imageUrl,

                        // Simpan Abstraksi ke kolom 'abstract'
                        'abstract' => $request->abstract,
                    ]);
                    break;

                case 'infographic':
                    $item = Infographic::findOrFail($id);
                    $item->update([
                        'title' => $request->title,
                        'category' => $request->category,
                        'date' => $request->publish_date,
                        'image_url' => $imageUrl,

                        // Simpan Deskripsi ke kolom 'description'
                        'description' => $request->description,
                    ]);
                    break;

                default:
                    return back()->withErrors(['error' => 'Tipe konten tidak valid.']);
            }

            return redirect()->route('admin.contents.index')->with('success', 'Konten berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal update konten: ' . $e->getMessage()]);
        }
    }

    /**
     * Menyimpan konten baru ke database
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'type' => 'required|string|in:news,press_release,publication,infographic',
            'title' => 'required|string|max:255',
            'category' => 'nullable|string',
            'publish_date' => 'nullable|date',
            'link' => 'nullable|url',
            'image_url' => 'nullable|string', // Jika input teks URL
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Jika upload file (max 2MB)

            'abstract' => 'nullable|string',     // Opsional (publikasi)
            'description' => 'nullable|string',  // Opsional (news/dll)
        ]);

        $type = $request->input('type');

        // 2. Handle Image Upload atau URL
        $imageUrl = $request->image_url;

        if ($request->hasFile('image_file')) {
            // Upload file ke storage/app/public/content_images
            $file = $request->file('image_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('content_images', $filename, 'public');
            // Generate URL untuk disimpan ke database
            $imageUrl = asset('storage/' . $path);
        }

        try {
            // 3. Mapping & Create berdasarkan Tipe
            switch ($type) {
                case 'news':
                    News::create([
                        'title' => $request->title,
                        'category' => $request->category,
                        'date' => $request->publish_date, // Mapping: publish_date -> date
                        'link' => $request->link,
                        'thumbnail_url' => $imageUrl,
                        'content' => $request->description, // Mapping: description -> content
                    ]);
                    break;

                case 'press_release':
                    PressRelease::create([
                        'title' => $request->title,
                        'category' => $request->category,
                        'date' => $request->publish_date,
                        'link' => $request->link,
                        'thumbnail_url' => $imageUrl,
                        'content' => $request->description,
                    ]);
                    break;

                case 'publication':
                    Publication::create([
                        'title' => $request->title,
                        'category' => $request->category,
                        'release_date' => $request->publish_date, // Mapping: publish_date -> release_date
                        'link' => $request->link,
                        'cover_url' => $imageUrl, // Mapping: image_url -> cover_url
                        'abstract' => $request->abstract, // Mapping: abstract -> abstract
                    ]);
                    break;

                case 'infographic':
                    Infographic::create([
                        'title' => $request->title,
                        'category' => $request->category,
                        'date' => $request->publish_date,
                        'image_url' => $imageUrl,
                        'description' => $request->description, // Mapping: description -> description
                        'link' => $request->link
                    ]);
                    break;
            }

            return redirect()->route('admin.contents.index')->with('success', 'Konten baru berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Log error jika perlu: Log::error($e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan konten: ' . $e->getMessage()]);
        }
    }
}
