<?php

namespace App\Services;

use App\Models\News;
use App\Models\PressRelease;
use App\Models\Publication;
use App\Models\Infographic;
use Illuminate\Support\Facades\Log;

class BpsContentService
{
    public function storeFromScraper(array $json)
    {
        // Validasi minimal field wajib
        if (empty($json['type'])) {
            return [
                'success' => false,
                'message' => 'Tipe konten tidak boleh kosong'
            ];
        }

        if (empty($json['title'])) {
            return [
                'success' => false,
                'message' => 'Judul konten tidak boleh kosong'
            ];
        }

        $type = $json["type"];

        try {
            switch ($type) {

                case "news":
                    $data = News::create([
                        "title" => $json["title"],
                        "date" => $json["date_iso"] ?? null,
                        "category" => $json["category"] ?? null,
                        "abstract" => $json["content"] ?? null,
                        "thumbnail_url" => $json["image"] ?? null,
                        "link" => $json["url"] ?? null
                    ]);
                    break;

                case "pressrelease":
                    $data = PressRelease::create([
                        "title" => $json["title"],
                        "date" => $json["date_iso"] ?? null,
                        "category" => $json["category"] ?? null,
                        "abstract" => $json["content"] ?? null,
                        "thumbnail_url" => $json["image"] ?? null,
                        "link" => $json["url"] ?? null
                    ]);
                    break;

                case "publication":
                    $data = Publication::create([
                        "title" => $json["title"],
                        "release_date" => $json["date_iso"] ?? null,
                        "cover_url" => $json["image"] ?? null,
                        "pdf_url" => $json["pdf_url"] ?? null,
                        "abstract" => $json["content"] ?? null,
                        "link" => $json["url"] ?? null
                    ]);
                    break;

                case "infographic":
                    $data = Infographic::create([
                        "title" => $json["title"],
                        "date" => $json["date_iso"] ?? null,
                        "category" => $json["category"] ?? null,
                        "image_url" => $json["image"] ?? null,
                        "link" => $json["url"] ?? null
                    ]);
                    break;

                default:
                    return [
                        "success" => false,
                        "message" => "Jenis konten tidak dikenali: {$type}"
                    ];
            }

            return [
                "success" => true,
                "message" => "Data berhasil disimpan",
                "type" => $type,
                "stored" => $data
            ];
        } catch (\Exception $e) {
            Log::error('BpsContentService::storeFromScraper error', [
                'type' => $type,
                'data' => $json,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ];
        }
    }
}
