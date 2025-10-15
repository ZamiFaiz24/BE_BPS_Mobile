<?php

namespace App\Jobs;

use App\Services\BpsApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
// (PENTING) Tambahkan juga model-model Anda jika mau menyimpan data
use App\Models\BpsDataset;
use App\Models\BpsDatavalue;
use Carbon\Carbon;

class SyncBpsDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $domain, $variable, $tahunMulai, $tahunAkhir, $category;
    protected string $label;

    public function __construct(int $domain, int $variable, int $tahunMulai, int $tahunAkhir, string $label, int $category)
    {
        $this->domain = $domain;
        $this->variable = $variable;
        $this->tahunMulai = $tahunMulai;
        $this->tahunAkhir = $tahunAkhir;
        $this->label = $label;
        $this->category = $category;
    }

    public function handle(BpsApiService $bpsApiService): void
    {
        Log::info("Memulai job sinkronisasi untuk: {$this->label}");

        $years = range($this->tahunMulai, $this->tahunAkhir);
        $dataset = null;

        foreach ($years as $year) {
            $apiParams = [
                'domain' => $this->domain,
                'var'    => $this->variable,
                'th'     => $bpsApiService->tahunKeKode($year),
            ];

            $json = $bpsApiService->fetchData('data', $apiParams);

            if (is_null($json)) {
                Log::warning(" -> [JOB] Skipping year {$year} for {$this->label}. Data tidak ditemukan atau error API.");
                continue;
            }

            if (is_null($dataset)) {
                $dataset = BpsDataset::updateOrCreate(
                    ['dataset_code' => $this->variable],
                    [
                        'dataset_name' => $json['var'][0]['label'] ?? $this->label,
                        'subject'      => $json['subject'][0]['label'] ?? null,
                        'source'       => 'BPS Kebumen',
                        'source_note'  => $json['var'][0]['note'] ?? null,
                        'last_update'  => Carbon::parse($json['last_update']),
                        'insight_type' => 'default',
                        'category'     => $this->category,
                    ]
                );
            }

            $var = $json['var'][0];

            // Ambil dimensi atau gunakan array kosong jika tidak ada
            $vervars   = $json['vervar']   ?? [];
            $turvars   = $json['turvar']   ?? [];
            $tahuns    = $json['tahun']    ?? [];
            $turtahuns = $json['turtahun'] ?? [];

            // PENTING: Pastikan loop berjalan minimal sekali, bahkan jika dimensinya kosong.
            // Ini untuk menjaga konsistensi 'kunci' upsert.
            if (empty($vervars)) {
                $vervars[]   = ['val' => $var['val'], 'label' => null];
            }
            if (empty($turvars)) {
                $turvars[]   = ['val' => '', 'label' => null];
            }
            if (empty($tahuns)) {
                $tahuns[]    = ['val' => '', 'label' => null];
            }
            if (empty($turtahuns)) {
                $turtahuns[] = ['val' => '', 'label' => null];
            }

            $valuesToUpsert = [];

            foreach ($vervars as $vervar) {
                foreach ($turvars as $turvar) {
                    foreach ($tahuns as $th) {
                        foreach ($turtahuns as $turtahun) {
                            // Untuk 'vervar' kita gunakan val dari variabel utama jika vervar kosong
                            $vervarVal = $vervar['val'] === '' ? $var['val'] : $vervar['val'];
                            $keyData = $vervarVal . $var['val'] . $turvar['val'] . $th['val'] . $turtahun['val'];

                            if (isset($json['datacontent'][$keyData])) {
                                $nilai = $json['datacontent'][$keyData];

                                $valuesToUpsert[] = [
                                    'dataset_id'     => $dataset->id,
                                    'vervar_label'   => $vervar['label'],
                                    'turvar_label'   => $turvar['label'],
                                    'turtahun_label' => $turtahun['label'],
                                    'year'           => $year,
                                    'value'          => $nilai,
                                    'unit'           => $var['unit'],
                                ];
                            }
                        }
                    }
                }
            }

            if (!empty($valuesToUpsert)) {
                BpsDatavalue::upsert(
                    $valuesToUpsert,
                    ['dataset_id', 'vervar_label', 'turvar_label', 'turtahun_label', 'year'],
                    ['value', 'unit']
                );
                Log::info(" -> [JOB] Berhasil memproses dan MENYIMPAN/MEMPERBARUI data tahun {$year} untuk '{$this->label}'");
            } else {
                Log::warning(" -> [JOB] Berhasil memproses tahun {$year} untuk '{$this->label}', namun tidak ada konten data untuk disimpan.");
            }
        }

        Log::info("Job sinkronisasi untuk '{$this->label}' selesai.");
    }
}
