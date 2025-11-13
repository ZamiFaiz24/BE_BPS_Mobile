<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Models\News;
use App\Models\PressRelease;
use App\Models\Infographic;
use App\Models\Publication;


class BpsContentController extends Controller
{
    public function storeNews(Request $request)
    {
        $allowedKeys = [
            'date',
            'category',
            'title',
            'abstract',
            'thumbnail_url',
            'link',
        ];

        try {
            $data = $request->validate([
                '*.date' => 'required|string|max:255',
                '*.upload_date' => 'nullable|string|max:255',
                '*.category' => 'nullable|string|max:255',
                '*.title' => 'required|string|max:255',
                '*.abstract' => 'nullable|string',
                '*.thumbnail' => 'nullable|string|max:2048',
                '*.link' => 'required|string|max:2048', // TIDAK ADA RULE 'unique'
                '*.detail_image' => 'nullable|string|max:2048',
                '*.content_html' => 'nullable|string',
                '*.content_text' => 'nullable|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error on one or more required fields.',
                'errors' => $e->errors(),
            ], 422);
        }

        $newsItems = $data;
        $savedCount = 0;
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($newsItems as $item) {
                $item['thumbnail_url'] = $item['thumbnail'] ?? null;
                $cleanedItem = array_filter($item, function ($value) {
                    return !is_null($value) && $value !== '' && !is_array($value) || (is_array($value) && !empty($value));
                });
                $newsData = array_intersect_key($cleanedItem, array_flip($allowedKeys));

                if (!isset($newsData['link'])) {
                    $errors[] = ['message' => 'Link is missing after cleaning for an item.', 'type' => 'internal_validation'];
                    continue;
                }
                $news = News::firstOrNew(['link' => $newsData['link']]);

                if ($news->exists) {
                    $errors[] = [
                        'link' => $newsData['link'],
                        'message' => 'The link has already been taken (duplicate entry).',
                        'type' => 'duplicate',
                    ];
                } else {
                    $news->fill($newsData)->save();
                    $savedCount++;
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Batch processing complete. Duplicates skipped.',
                'saved_count' => $savedCount,
                'total_received' => count($newsItems),
                'errors' => $errors,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('News Store Batch Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());

            return response()->json([
                'message' => 'Server error during batch processing.',
                'error' => 'An internal server error occurred. Check the laravel.log file for details.',
                'exception_preview' => $e->getMessage(),
            ], 500);
        }
    }

    public function storeInfographic(Request $request)
    {
        Log::info('Infographic Request Received', [
            'count' => count($request->all())
        ]);

        $batchData = $request->all();
        if (empty($batchData)) {
            return response()->json([
                'message' => 'No data received',
                'errors' => ['Empty request body']
            ], 400);
        }

        $storedCount = 0;
        $errors = [];

        $bulanMap = [
            'Januari' => 'January',
            'Februari' => 'February',
            'Maret' => 'March',
            'April' => 'April',
            'Mei' => 'May',
            'Juni' => 'June',
            'Juli' => 'July',
            'Agustus' => 'August',
            'September' => 'September',
            'Oktober' => 'October',
            'November' => 'November',
            'Desember' => 'December',
        ];

        DB::beginTransaction();

        foreach ($batchData as $index => $item) {
            // Validasi sesuai key JSON dari scraper
            $validator = Validator::make($item, [
                'title' => 'required|string|max:255',
                'infographic' => ['required', 'string', 'max:2048', Rule::unique('infographics', 'image_url')], // dedup pakai image_url
                'date' => 'nullable|string',
                'category' => 'nullable|string|max:255',                'description' => 'nullable|string',
                'link' => 'required|url|unique:infographics,link',            ]);

            if ($validator->fails()) {
                $errors[] = ['index' => $index, 'errors' => $validator->errors()->all()];
                continue;
            }

            $validated = $validator->validated();
            $formattedDate = null;
            if (!empty($validated['date'])) {
                try {
                    $formattedDate = Carbon::parse(strtr($validated['date'], $bulanMap))->toDateString();
                } catch (\Exception $e) {
                    $formattedDate = $validated['date']; // fallback simpan apa adanya
                }
            }
            $dataToStore = [
                'title' => $validated['title'],
                'image_url' => $validated['infographic'], // dari JSON "infographic"
                'category' => $validated['category'] ?? null, // subject -> category
                'date' => $formattedDate,
                'description' => $validated['description'] ?? null,
                'link' => $validated['link'],
            ];
            $dataToStore = array_filter($dataToStore, fn($v) => !is_null($v));

            try {
                Infographic::updateOrCreate(
                    ['image_url' => $dataToStore['image_url']],
                    $dataToStore
                );
                $storedCount++;
            } catch (\Exception $e) {
                $errors[] = ['index' => $index, 'db_error' => $e->getMessage()];
            }
        }

        DB::commit();

        if (!empty($errors)) {
            return response()->json([
                'message' => "Batch processed with {$storedCount} saved and " . count($errors) . " errors.",
                'saved_count' => $storedCount,
                'errors' => $errors
            ], 202);
        }

        return response()->json([
            'message' => 'Batch infographics stored successfully.',
            'saved_count' => $storedCount
        ], 201);
    }

    public function storePressRelease(Request $request)
    {
        $batchData = $request->all();
        $storedCount = 0;
        $errors = [];

        $bulanMap = [
            'Januari' => 'January',
            'Februari' => 'February',
            'Maret' => 'March',
            'April' => 'April',
            'Mei' => 'May',
            'Juni' => 'June',
            'Juli' => 'July',
            'Agustus' => 'August',
            'September' => 'September',
            'Oktober' => 'October',
            'November' => 'November',
            'Desember' => 'December',
        ];

        DB::beginTransaction();

        foreach ($batchData as $index => $item) {

            $validator = Validator::make($item, [
                'title' => 'required|string|max:512',
                'link' => ['required', 'string', 'max:512', Rule::unique('press_releases', 'link')],
                'date' => 'required|string',
                'abstract' => 'nullable|string',
                'thumbnail' => 'nullable|string|max:512', 
                'pdf' => 'nullable|string|max:512',      
                'downloads' => 'nullable|array',         
                'category' => 'nullable|string|max:255',
                'content_html' => 'nullable|string',
                'content_text' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                $errors[] = ['index' => $index, 'errors' => $validator->errors()->all()];
                continue;
            }

            $validatedData = $validator->validated();

            $formattedDate = null;
            if (isset($validatedData['date'])) {
                $englishDate = strtr($validatedData['date'], $bulanMap);
                try {
                    $formattedDate = Carbon::parse($englishDate)->toDateString();
                } catch (\Exception $e) { /* Gagal */
                }
            }

            $dataToStore = [
                'title' => $validatedData['title'],
                'link' => $validatedData['link'],
                'date' => $formattedDate,
                'thumbnail_url' => $validatedData['thumbnail'] ?? null, // FIXED
                'pdf_url' => $validatedData['pdf'] ?? null,             // FIXED
                'abstract' => $validatedData['abstract'] ?? null,
                'category' => $validatedData['category'] ?? null,
                'content_html' => $validatedData['content_html'] ?? null,
                'content_text' => $validatedData['content_text'] ?? null,
            ];

            if (isset($validatedData['downloads'])) {
                $dataToStore['downloads'] = json_encode($validatedData['downloads']);
            } else {
                $dataToStore['downloads'] = null;
            }

            try {
                PressRelease::updateOrCreate(
                    ['link' => $dataToStore['link']],
                    $dataToStore
                );
                $storedCount++;
            } catch (\Exception $e) {
                $errors[] = ['index' => $index, 'db_error' => "DB Error: " . $e->getMessage()];
            }
        }

        DB::commit();

        if (!empty($errors)) {
            return response()->json([
                'message' => "Batch processed with {$storedCount} saved and " . count($errors) . " errors.",
                'saved_count' => $storedCount,
                'errors' => $errors
            ], 202);
        }

        return response()->json([
            'message' => 'Batch press releases stored successfully.',
            'saved_count' => $storedCount
        ], 201);
    }

    public function storePublication(Request $request)
    {
        $batchData = $request->all();
        $storedCount = 0;
        $errors = [];
        $bulanMap = [
            'Januari' => 'January',
            'Februari' => 'February',
            'Maret' => 'March',
            'April' => 'April',
            'Mei' => 'May',
            'Juni' => 'June',
            'Juli' => 'July',
            'Agustus' => 'August',
            'September' => 'September',
            'Oktober' => 'October',
            'November' => 'November',
            'Desember' => 'December',
        ];

        DB::beginTransaction();

        foreach ($batchData as $index => $item) {

            $validator = Validator::make($item, [
                'title' => 'required|string|max:255',
                'link' => 'required|string|unique:publications,link', // Cek duplikat
                'date' => 'nullable|string', // Kunci dari Python
                'subject' => 'nullable|string|max:255', // Kunci dari Python
                'cover' => 'nullable|string|max:512', // Kunci dari Python
                'pdf' => 'nullable|string|max:512', // Kunci dari Python
                'abstract' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                $errors[] = ['index' => $index, 'errors' => $validator->errors()->all()];
                continue; 

            $validatedData = $validator->validated();
            $formattedDate = null;
            if (isset($validatedData['date'])) {
                $rawDate = $validatedData['date']; // Contoh: "30 Oktober 2025"
                $englishDate = strtr($rawDate, $bulanMap);
                try {
                    $formattedDate = Carbon::parse($englishDate)->toDateString();
                } catch (\Exception $e) { /* Biarkan null jika konversi gagal */
                }
            }
            $dataToStore = [
                'title' => $validatedData['title'],
                'link' => $validatedData['link'],
                'abstract' => $validatedData['abstract'] ?? null,
                'release_date' => $formattedDate,
                'category' => $validatedData['subject'] ?? null,
                'cover_url' => $validatedData['cover'] ?? null,
                'pdf_url' => $validatedData['pdf'] ?? null,
            ];

            if (isset($item['downloads'])) {
                $dataToStore['downloads'] = json_encode($item['downloads']);
            }

            try {
                Publication::updateOrCreate(
                    ['link' => $dataToStore['link']],
                    $dataToStore
                );
                $storedCount++;
            } catch (\Exception $e) {
                $errors[] = ['index' => $index, 'db_error' => "DB Error: " . $e->getMessage()];
            }
        }

        DB::commit();
        if (!empty($errors)) {
            return response()->json([
                'message' => "Batch processed with **{$storedCount} saved** and " . count($errors) . " errors.",
                'saved_count' => $storedCount,
                'errors' => $errors
            ], 202);
        }

        return response()->json([
            'message' => 'Batch publication stored successfully.',
            'saved_count' => $storedCount
        ], 201);
        
        }
    }

    /**
     * Get News with pagination, filtering, and sorting
     */
    public function getNews(Request $request)
    {
        $query = News::query();

        if ($request->has('category') && $request->category) {
            $query->where('category', 'like', '%' . $request->category . '%');
        }

        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        
        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 10);
        $news = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'News retrieved successfully',
            'data' => $news->items(),
            'pagination' => [
                'current_page' => $news->currentPage(),
                'last_page' => $news->lastPage(),
                'per_page' => $news->perPage(),
                'total' => $news->total(),
            ]
        ], 200);
    }

    /**
     * Get Press Releases with pagination, filtering, and sorting
     */
    public function getPressReleases(Request $request)
    {
        $query = PressRelease::query();

        if ($request->has('category') && $request->category) {
            $query->where('category', 'like', '%' . $request->category . '%');
        }

        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 15);
        $pressReleases = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Press releases retrieved successfully',
            'data' => $pressReleases->items(),
            'pagination' => [
                'current_page' => $pressReleases->currentPage(),
                'last_page' => $pressReleases->lastPage(),
                'per_page' => $pressReleases->perPage(),
                'total' => $pressReleases->total(),
            ]
        ], 200);
    }

    /**
     * Get Infographics with pagination, filtering, and sorting
     */
    public function getInfographics(Request $request)
    {
        $query = Infographic::query();

        if ($request->has('category') && $request->category) {
            $query->where('category', 'like', '%' . $request->category . '%');
        }

        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 15);
        $infographics = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Infographics retrieved successfully',
            'data' => $infographics->items(),
            'pagination' => [
                'current_page' => $infographics->currentPage(),
                'last_page' => $infographics->lastPage(),
                'per_page' => $infographics->perPage(),
                'total' => $infographics->total(),
            ]
        ], 200);
    }

    /**
     * Get Publications with pagination, filtering, and sorting
     */
    public function getPublications(Request $request)
    {
        $query = Publication::query();

        if ($request->has('category') && $request->category) {
            $query->where('category', 'like', '%' . $request->category . '%');
        }

        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('date_from')) {
            $query->where('release_date', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->where('release_date', '<=', $request->date_to);
        }

        $sortBy = $request->get('sort_by', 'release_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 15);
        $publications = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Publications retrieved successfully',
            'data' => $publications->items(),
            'pagination' => [
                'current_page' => $publications->currentPage(),
                'last_page' => $publications->lastPage(),
                'per_page' => $publications->perPage(),
                'total' => $publications->total(),
            ]
        ], 200);
    }
}
