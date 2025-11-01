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
        // Query gabungan dari 4 tabel
        $newsQuery = DB::table('news')
            ->select(
                'id',
                'title',
                DB::raw("'news' as type"),
                'date as publish_date',
                'category',
                'thumbnail_url as image_url',
                'link',
                'created_at'
            );

        $pressQuery = DB::table('press_releases')
            ->select(
                'id',
                'title',
                DB::raw("'press_release' as type"),
                'date as publish_date',
                'category',
                'thumbnail_url as image_url',
                'link',
                'created_at'
            );

        $publicationQuery = DB::table('publications')
            ->select(
                'id',
                'title',
                DB::raw("'publication' as type"),
                'release_date as publish_date',
                'category',
                'cover_url as image_url',
                'link',
                'created_at'
            );

        $infographicQuery = DB::table('infographics')
            ->select(
                'id',
                'title',
                DB::raw("'infographic' as type"),
                'date as publish_date',
                'category',
                'image_url',
                DB::raw("NULL as link"),
                'created_at'
            );

        // Union semua query
        $unionQuery = $newsQuery
            ->unionAll($pressQuery)
            ->unionAll($publicationQuery)
            ->unionAll($infographicQuery);

        // Wrap dalam subquery untuk filter dan sort
        $query = DB::table(DB::raw("({$unionQuery->toSql()}) as contents"))
            ->mergeBindings($unionQuery);

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

        // Paginate dengan query string
        $contents = $query
            ->orderByDesc('publish_date')
            ->paginate(15)
            ->appends($request->all());

        return view('admin.contents.index', compact('contents'));
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
}
