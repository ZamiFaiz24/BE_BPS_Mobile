<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BpsApiService;    // Service "kurir" yang sudah dimodifikasi
use App\Models\BpsDataset;          // Sesuaikan dengan nama model Anda
use App\Models\BpsDatavalue;        // Sesuaikan dengan nama model Anda
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FetchBpsDataCommand extends Command
{
    protected $signature = 'bps:fetch-data';

    protected $description = 'Fetch datasets from BPS API based on config targets and store them.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(BpsApiService $bpsApiService)
    {
        $this->info('Starting BPS data fetching process...');

        // 1. Baca "Buku Catatan Digital"
        $targets = config('bps_targets.datasets');

        if (empty($targets)) {
            $this->warn('No datasets found in config/bps_targets.php. Exiting.');
            return 0;
        }

        // 2. Looping untuk setiap data yang terdaftar
        foreach ($targets as $target) {
            $this->info("Processing: {$target['name']}");

            $years = range($target['tahun_mulai'], $target['tahun_akhir']);
            $dataset = null;

            foreach ($years as $year) {
                // 3. Siapkan parameter untuk "Kurir"
                $apiParams = array_merge($target['params'], [
                    'var' => $target['variable_id'],
                    'th' => $bpsApiService->tahunKeKode($year), // Menggunakan helper dari service
                ]);

                // 4. Minta "Kurir" untuk mengambil data mentah dari BPS
                $json = $bpsApiService->fetchData($target['model'], $apiParams);

                if (is_null($json)) {
                    $this->warn(" -> Skipping year {$year} for {$target['name']}. Data not found or API error.");
                    continue;
                }

                // Buat atau update informasi dataset-nya
                if (is_null($dataset)) {
                    $dataset = BpsDataset::updateOrCreate(
                        ['dataset_code' => $target['variable_id']], // Sesuaikan 'dataset_code' dengan nama kolom Anda
                        [
                            'dataset_name' => $json['var'][0]['label'] ?? $target['name'], // Sesuaikan nama kolom
                            'subject'      => $json['subject'][0]['label'] ?? null,
                            'source'       => 'BPS Kebumen',
                            'source_note'  => $json['var'][0]['note'] ?? null,
                            'last_update'  => Carbon::parse($json['last_update']),
                            'insight_type' => $target['insight_type'] ?? 'default',
                        ]
                    );
                }

                // Ambil semua dimensi data dari response JSON
                $var       = $json['var'][0];
                $vervars   = $json['vervar']   ?? [];
                $turvars   = $json['turvar']   ?? [];
                $tahuns    = $json['tahun']    ?? [];
                $turtahuns = $json['turtahun'] ?? [];

                $valuesToUpsert = [];

                // Looping multi-dimensi untuk membangun baris data
                foreach ($vervars as $vervar) {
                    foreach ($turvars as $turvar) {
                        foreach ($tahuns as $th) {
                            foreach ($turtahuns as $turtahun) {
                                $keyData = $vervar['val'] . $var['val'] . $turvar['val'] . $th['val'] . $turtahun['val'];

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

                // Jika ada data yang bisa disimpan, lakukan operasi upsert
                if (!empty($valuesToUpsert)) {
                    BpsDatavalue::upsert(
                        $valuesToUpsert,
                        ['dataset_id', 'vervar_label', 'turvar_label', 'turtahun_label', 'year'],
                        ['value', 'unit']
                    );
                    $this->info(" > Successfully stored data for year {$year}");
                } else {
                    $this->warn(" > No processable data content for year {$year}");
                }
            }
        }

        $this->info('BPS data fetching process finished.');
        return 0;
    }
}
