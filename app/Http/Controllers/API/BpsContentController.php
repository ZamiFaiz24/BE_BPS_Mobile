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
        // Daftar field yang DIIZINKAN (harus sesuai dengan $fillable di model News.php)
        $allowedKeys = [
            'date',
            'category',
            'title',
            'abstract',
            'thumbnail_url',
            'link',
        ];

        // 1. Validasi Awal: Hapus rule 'unique' agar batch tidak langsung gagal.
        try {
            $data = $request->validate([
                '*.date' => 'required|string|max:255',
                '*.upload_date' => 'nullable|string|max:255', // Dibiarkan di sini untuk validasi tipe data saja
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
            // Tangani error validasi seperti missing 'link' atau 'title'
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
                // --- MAPPING FIELD SCRAPER ---
                $item['thumbnail_url'] = $item['thumbnail'] ?? null;

                // --- PEMBERSIHAN DATA INPUT ---
                // Hapus field yang bernilai NULL/kosong dan yang tidak diperlukan sebelum pemrosesan
                $cleanedItem = array_filter($item, function ($value) {
                    return !is_null($value) && $value !== '' && !is_array($value) || (is_array($value) && !empty($value));
                });

                // 2. FILTER AKHIR: Hanya ambil field yang ada di $fillable database (untuk mencegah Error 500)
                $newsData = array_intersect_key($cleanedItem, array_flip($allowedKeys));

                if (!isset($newsData['link'])) {
                    $errors[] = ['message' => 'Link is missing after cleaning for an item.', 'type' => 'internal_validation'];
                    continue;
                }

                // 3. Cek Duplikat Manual (First or New)
                $news = News::firstOrNew(['link' => $newsData['link']]);

                if ($news->exists) {
                    // Data sudah ada di database, SKIP dan catat error duplikat.
                    $errors[] = [
                        'link' => $newsData['link'],
                        'message' => 'The link has already been taken (duplicate entry).',
                        'type' => 'duplicate',
                    ];
                } else {
                    // Data belum ada, simpan data baru.
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
            // Logging error untuk debugging 500
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
                'subject' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                // link tidak wajib (jarang ada di payload)
            ]);

            if ($validator->fails()) {
                $errors[] = ['index' => $index, 'errors' => $validator->errors()->all()];
                continue;
            }

            $validated = $validator->validated();

            // Konversi tanggal (opsional, sudah ISO dalam contoh Anda)
            $formattedDate = null;
            if (!empty($validated['date'])) {
                try {
                    $formattedDate = Carbon::parse(strtr($validated['date'], $bulanMap))->toDateString();
                } catch (\Exception $e) {
                    $formattedDate = $validated['date']; // fallback simpan apa adanya
                }
            }

            // Mapping JSON -> kolom DB
            $dataToStore = [
                'title' => $validated['title'],
                'image_url' => $validated['infographic'], // dari JSON "infographic"
                'category' => $validated['subject'] ?? null, // subject -> category
                'date' => $formattedDate,
                'description' => $validated['description'] ?? null,
                // 'link' => $item['link'] ?? null, // jika suatu saat tersedia
            ];

            // Hapus null
            $dataToStore = array_filter($dataToStore, fn($v) => !is_null($v));

            try {
                // Upsert berdasarkan image_url (bukan link)
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

            // 1. Validasi Data Item (Menggunakan Kunci JSON Anda)
            $validator = Validator::make($item, [
                'title' => 'required|string|max:512',
                'link' => ['required', 'string', 'max:512', Rule::unique('press_releases', 'link')],
                'date' => 'required|string',
                'abstract' => 'nullable|string',

                // Kunci dari JSON Python Anda
                'thumbnail' => 'nullable|string|max:512', // Kunci dari JSON
                'pdf' => 'nullable|string|max:512',       // Kunci dari JSON
                'downloads' => 'nullable|array',          // Kunci dari JSON

                // Kunci yang Anda simpan di DB saat ini
                'category' => 'nullable|string|max:255',
                'content_html' => 'nullable|string',
                'content_text' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                $errors[] = ['index' => $index, 'errors' => $validator->errors()->all()];
                continue;
            }

            $validatedData = $validator->validated();

            // 2. Konversi Tanggal
            $formattedDate = null;
            if (isset($validatedData['date'])) {
                $englishDate = strtr($validatedData['date'], $bulanMap);
                try {
                    $formattedDate = Carbon::parse($englishDate)->toDateString();
                } catch (\Exception $e) { /* Gagal */
                }
            }

            // 3. Pemetaan Data Akhir (MAPPING DILAKUKAN DI SINI)
            $dataToStore = [
                'title' => $validatedData['title'],
                'link' => $validatedData['link'],
                'date' => $formattedDate,

                // Mapping Kunci dari JSON ke Kolom DB:
                'thumbnail_url' => $validatedData['thumbnail'] ?? null, // FIXED
                'pdf_url' => $validatedData['pdf'] ?? null,             // FIXED

                // Kunci yang sudah sinkron
                'abstract' => $validatedData['abstract'] ?? null,
                'category' => $validatedData['category'] ?? null,
                'content_html' => $validatedData['content_html'] ?? null,
                'content_text' => $validatedData['content_text'] ?? null,
            ];

            // 4. Handle Downloads (Array ke JSON String)
            if (isset($validatedData['downloads'])) {
                $dataToStore['downloads'] = json_encode($validatedData['downloads']);
            } else {
                $dataToStore['downloads'] = null;
            }

            // 5. Penyimpanan (Update or Create)
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

        // 6. Respons Akhir
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
        // Menerima data sebagai ARRAY dari Python (Batch Mode)
        $batchData = $request->all();
        $storedCount = 0;
        $errors = [];

        // Mapping nama bulan Indonesia ke Inggris untuk Carbon
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

        DB::beginTransaction(); // Memastikan semua penyimpanan berhasil atau tidak sama sekali

        foreach ($batchData as $index => $item) {

            // --- 1. Validasi Item Individual ---
            // Validasi menggunakan kunci yang dikirim DARI PYTHON
            $validator = Validator::make($item, [
                'title' => 'required|string|max:255',
                'link' => 'required|string|unique:publications,link', // Cek duplikat
                'date' => 'nullable|string', // Kunci dari Python
                'subject' => 'nullable|string|max:255', // Kunci dari Python
                'cover' => 'nullable|string|max:512', // Kunci dari Python
                'pdf' => 'nullable|string|max:512', // Kunci dari Python
                'abstract' => 'nullable|string',
                // upload_date tidak perlu divalidasi karena sama dengan 'date'
            ]);

            if ($validator->fails()) {
                $errors[] = ['index' => $index, 'errors' => $validator->errors()->all()];
                continue; // Lanjut ke item berikutnya
            }

            $validatedData = $validator->validated();

            // --- 2. Konversi Tanggal ---
            $formattedDate = null;
            if (isset($validatedData['date'])) {
                $rawDate = $validatedData['date']; // Contoh: "30 Oktober 2025"
                $englishDate = strtr($rawDate, $bulanMap);
                try {
                    // Konversi ke format YYYY-MM-DD
                    $formattedDate = Carbon::parse($englishDate)->toDateString();
                } catch (\Exception $e) { /* Biarkan null jika konversi gagal */
                }
            }

            // --- 3. Pemetaan Data Akhir (Mapping Python Key -> DB Column) ---
            $dataToStore = [
                'title' => $validatedData['title'],
                'link' => $validatedData['link'],
                'abstract' => $validatedData['abstract'] ?? null,

                // MAPPING: Python 'date' -> DB 'release_date'
                'release_date' => $formattedDate,

                // MAPPING: Python 'subject' -> DB 'category' (berdasarkan struktur DB Anda)
                'category' => $validatedData['subject'] ?? null,

                // MAPPING: Python 'cover' -> DB 'cover_url'
                'cover_url' => $validatedData['cover'] ?? null,

                // MAPPING: Python 'pdf' -> DB 'pdf_url'
                'pdf_url' => $validatedData['pdf'] ?? null,
            ];

            // Tangani kunci 'downloads' dari Python (yang berisi array)
            if (isset($item['downloads'])) {
                $dataToStore['downloads'] = json_encode($item['downloads']);
            }

            // --- 4. Penyimpanan ---
            try {
                // updateOrCreate: Jika 'link' sudah ada, update. Jika tidak, buat baru.
                Publication::updateOrCreate(
                    ['link' => $dataToStore['link']],
                    $dataToStore
                );
                $storedCount++;
            } catch (\Exception $e) {
                $errors[] = ['index' => $index, 'db_error' => "DB Error: " . $e->getMessage()];
            }
        }

        DB::commit(); // Selesaikan transaksi

        // --- 5. Respons ---
        if (!empty($errors)) {
            // Mengembalikan status 202 jika ada error dalam batch
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
