<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BpsApiService;
use App\Models\BpsDataset;
use App\Models\BpsDatavalue;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Models\SyncLog;
use App\Models\SyncLogDetail;
use App\Mail\SyncFailedNotification;
use App\Mail\SyncSuccessNotification;
use Illuminate\Support\Str;

class FetchBpsDataCommand extends Command implements ShouldQueue
{
    use InteractsWithQueue;

    // 1. GANTI SIGNATURE: Terima --log_id dan --dataset_id
    protected $signature = 'bps:fetch-data {--log_id=} {--dataset_id=}';

    protected $description = 'Fetch datasets from BPS API based on config targets and store them.';

    /**
     * Execute the console command.
     */
    public function handle(BpsApiService $bpsApiService)
    {
        // 2. AMBIL log_id dan CARI LOG YANG SUDAH DIBUAT
        $logId = $this->option('log_id');

        if (!$logId) {
            $this->error('log_id is required!');
            Log::error('Sync command called without log_id');
            return 1;
        }

        $log = SyncLog::find($logId);

        if (!$log) {
            $this->error("SyncLog with ID {$logId} not found!");
            Log::error("SyncLog ID {$logId} tidak ditemukan");
            return 1;
        }

        $this->info("Starting BPS data fetching process... (Log ID: {$log->id})");
        Log::info("Sync (Log ID: {$log->id}) started by: " . ($log->user_id ? "User ID {$log->user_id}" : "Scheduler"));

        // 3. TIDAK PERLU CACHE LOCK (sudah ada di SyncController)
        // 4. TIDAK PERLU BUAT LOG BARU (sudah ada)

        // Inisialisasi penghitung
        $countAdded = 0;
        $countUpdated = 0;
        $countFailed = 0;

        try {
            // Ambil dataset dari config service (sudah termasuk override)
            $configService = new \App\Services\DatasetConfigService();
            $allDatasets = $configService->getAllDatasets();

            // Filter: jika ada --dataset_id, hanya proses dataset tersebut
            $datasetIdFilter = $this->option('dataset_id');
            if ($datasetIdFilter) {
                $this->info("Filtering to single dataset: {$datasetIdFilter}");
                $allDatasets = array_filter($allDatasets, function ($ds) use ($datasetIdFilter) {
                    return ($ds['id'] ?? null) === $datasetIdFilter;
                });
            }

            if (empty($allDatasets)) {
                $this->warn('No datasets to process. Exiting.');
                $log->update([
                    'status' => 'gagal',
                    'finished_at' => now(),
                    'summary_message' => 'Gagal: Tidak ada dataset yang ditemukan.'
                ]);
                return 0;
            }

            foreach ($allDatasets as $target) {
                // Skip jika dataset di-disable
                if (isset($target['enabled']) && $target['enabled'] === false) {
                    $this->warn("Skipping disabled dataset: {$target['name']}");
                    continue;
                }

                $datasetName = $target['name'] ?? 'Unknown Dataset';
                $this->info("Processing: {$datasetName}");

                try {
                    if (!isset($target['variable_id'])) {
                        throw new \Exception("Missing 'variable_id'");
                    }

                    $years = range($target['tahun_mulai'], $target['tahun_akhir']);
                    $dataset = null;
                    $wasRecentlyCreated = false;

                    foreach ($years as $year) {
                        $apiParams = array_merge($target['params'], [
                            'domain' => $target['params']['domain'] ?? '3305',
                            'var'    => $target['variable_id'],
                            'th'     => $bpsApiService->tahunKeKode($year),
                        ]);

                        $json = $bpsApiService->fetchData($target['model'], $apiParams);

                        if (is_null($json)) {
                            $this->warn(" -> Skipping year {$year} for {$datasetName}. Data not found or API error.");
                            continue;
                        }

                        if (is_null($dataset)) {
                            $dataset = BpsDataset::updateOrCreate(
                                ['dataset_code' => $target['variable_id']],
                                [
                                    'dataset_name' => $json['var'][0]['label'] ?? $target['name'],
                                    'subject'      => $json['subject'][0]['label'] ?? null,
                                    'source'       => 'BPS Kebumen',
                                    'source_note'  => $json['var'][0]['note'] ?? null,
                                    'last_update'  => Carbon::parse($json['last_update']),
                                    'insight_type' => $target['insight_type'] ?? 'default',
                                    'category'     => isset($target['category']) ? (int)$target['category'] : null, // Ganti jadi 'category'
                                ]
                            );
                            if ($dataset->wasRecentlyCreated) {
                                $wasRecentlyCreated = true;
                            }
                        }

                        // ...existing code for data processing...
                        $var       = $json['var'][0];
                        $vervars   = $json['vervar']   ?? [];
                        $turvars   = $json['turvar']   ?? [];
                        $tahuns    = $json['tahun']    ?? [];
                        $turtahuns = $json['turtahun'] ?? [];
                        $valuesToUpsert = [];
                        foreach ($vervars as $vervar) {
                            foreach ($turvars as $turvar) {
                                foreach ($tahuns as $th) {
                                    foreach ($turtahuns as $turtahun) {
                                        $keyData = $vervar['val'] . $var['val'] . $turvar['val'] . $th['val'] . $turtahun['val'];
                                        if (isset($json['datacontent'][$keyData])) {
                                            $valuesToUpsert[] = [
                                                'dataset_id'     => $dataset->id,
                                                'vervar_label'   => $vervar['label'],
                                                'turvar_label'   => $turvar['label'],
                                                'turtahun_label' => $turtahun['label'],
                                                'year'           => $year,
                                                'value'          => $json['datacontent'][$keyData],
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
                            $this->info(" > Successfully stored data for year {$year}");
                        } else {
                            $this->warn(" > No processable data content for year {$year}");
                        }
                    }

                    if ($wasRecentlyCreated) {
                        $action = 'ditambah';
                        $countAdded++;
                    } else {
                        $action = 'diperbarui';
                        $countUpdated++;
                    }
                    SyncLogDetail::create([
                        'sync_log_id'   => $log->id,
                        'action'        => $action,
                        'dataset_title' => $datasetName,
                        'message'       => 'Sukses',
                    ]);
                } catch (\Exception $e) {
                    $countFailed++;
                    $errorMessage = $e->getMessage();
                    $this->error(" -> FAILED: {$datasetName}. Error: {$errorMessage}");
                    Log::error("Sync GAGAL untuk dataset: {$datasetName}. Error: " . $errorMessage, ['log_id' => $log->id]);

                    SyncLogDetail::create([
                        'sync_log_id'   => $log->id,
                        'action'        => 'gagal',
                        'dataset_title' => $datasetName,
                        'message'       => Str::limit($errorMessage, 250),
                    ]);
                }
            }

            $summary = "Sinkronisasi berhasil: $countAdded ditambah, $countUpdated diperbarui, $countFailed gagal.";
            $log->update([
                'status' => 'sukses',
                'finished_at' => now(),
                'summary_message' => $summary,
            ]);

            if (setting('email_notifications', false)) {
                $adminEmail = setting('admin_email');
                if ($adminEmail) {
                    Mail::to($adminEmail)->send(new SyncSuccessNotification());
                }
            }

            $this->info('BPS data fetching process finished successfully.');
            Log::info($summary . ' (Log ID: ' . $log->id . ')');
            return 0;
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $this->error('FATAL ERROR during sync: ' . $errorMessage);
            Log::error('SINKRONISASI FATAL GAGAL: ' . $errorMessage . ' (Log ID: ' . $log->id . ')', ['exception' => $e]);

            $log->update([
                'status' => 'gagal',
                'finished_at' => now(),
                'summary_message' => "Gagal Total: " . Str::limit($errorMessage, 250),
            ]);

            if (setting('email_notifications', false)) {
                $adminEmail = setting('admin_email');
                if ($adminEmail) {
                    Mail::to($adminEmail)->send(new SyncFailedNotification($errorMessage));
                }
            }

            return 1;
        }
        // TIDAK PERLU finally block karena lock dihandle di SyncController
    }
}
