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
    
    // 1. TAMBAHKAN OPSI --user_id
    protected $signature = 'bps:fetch-data {--user_id=}';

    protected $description = 'Fetch datasets from BPS API based on config targets and store them.';

    /**
     * Execute the console command.
     */
    public function handle(BpsApiService $bpsApiService) // Injeksi service Anda sudah benar
    {
        // Ambil user_id dari opsi. Jika null, berarti dijalankan oleh Sistem/Scheduler
        $userId = $this->option('user_id');

        // 2. GUNAKAN CACHE LOCK
        $lock = Cache::lock('sync:process', 300); // Kunci selama 5 menit
        if (!$lock->get()) {
            $this->error('Sinkronisasi sudah berjalan. Proses dibatalkan.');
            Log::warning('Attempted to run sync while already running.');
            return 1;
        }

        // 3. BUAT LOG INDUK ("STRUK")
        $log = SyncLog::create([
            'status' => 'berjalan',
            'user_id' => $userId,
            'started_at' => now(),
        ]);

        $this->info('Starting BPS data fetching process... (Log ID: ' . $log->id . ')');
        Log::info('Sync (Log ID: ' . $log->id . ') triggered by: ' . ($userId ? "User ID $userId" : "Scheduler"));

        // Inisialisasi penghitung
        $countAdded = 0;
        $countUpdated = 0;
        $countFailed = 0;

        // 4. BUNGKUS SEMUA LOGIC DENGAN TRY...CATCH...FINALLY
        try {

            // 1. Baca "Buku Catatan Digital" (Kode Anda)
            $targets = config('bps_targets.datasets');

            // Hapus 'dd($targets);' yang mungkin tertinggal
            // dd($targets); 

            if (empty($targets)) {
                $this->warn('No datasets found in config/bps_targets.php. Exiting.');
                $log->update([
                    'status' => 'gagal',
                    'finished_at' => now(),
                    'summary_message' => 'Gagal: File config bps_targets.php kosong atau tidak ditemukan.'
                ]);
                return 0;
            }

            // 2. Looping untuk setiap data yang terdaftar (Kode Anda)
            foreach ($targets as $target) {

                $datasetName = $target['name'] ?? 'Unknown Dataset';
                $this->info("Processing: {$datasetName}");

                // 5. TAMBAHKAN TRY...CATCH DI DALAM LOOP
                //    Ini penting agar 1 data gagal, data lain tetap lanjut.
                try {
                    if (!isset($target['variable_id'])) {
                        throw new \Exception("Missing 'variable_id'");
                    }

                    $years = range($target['tahun_mulai'], $target['tahun_akhir']);
                    $dataset = null;
                    $wasRecentlyCreated = false; // Flag untuk melacak status

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

                        // Buat atau update informasi dataset-nya (Kode Anda)
                        if (is_null($dataset)) {
                            $dataset = BpsDataset::updateOrCreate(
                                ['dataset_code' => $target['variable_id']], // Kunci unik
                                [
                                    'dataset_name' => $json['var'][0]['label'] ?? $target['name'],
                                    'subject'      => $json['subject'][0]['label'] ?? null,
                                    'source'       => 'BPS Kebumen',
                                    'source_note'  => $json['var'][0]['note'] ?? null,
                                    'last_update'  => Carbon::parse($json['last_update']),
                                    'insight_type' => $target['insight_type'] ?? 'default',
                                ]
                            );
                            // Cek apakah dataset ini BARU DIBUAT
                            if ($dataset->wasRecentlyCreated) {
                                $wasRecentlyCreated = true;
                            }
                        }

                        // ... (Parsing multi-dimensi Anda, sudah benar) ...
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

                        // Operasi upsert Anda (sudah benar)
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
                    } // --- End foreach $years ---

                    // 6. CATAT LOG DETAIL (SUKSES)
                    // Setelah semua tahun untuk 1 dataset selesai, catat hasilnya.
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
                    // 7. CATAT LOG DETAIL (GAGAL)
                    // Jika terjadi error untuk 1 dataset ini
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
            } // --- End foreach $targets ---

            // 8. UPDATE LOG INDUK (SUKSES)
            $summary = "Sinkronisasi berhasil: $countAdded ditambah, $countUpdated diperbarui, $countFailed gagal.";
            $log->update([
                'status' => 'sukses',
                'finished_at' => now(),
                'summary_message' => $summary,
            ]);

            // 9. KIRIM EMAIL SUKSES
            if (setting('email_notifications', false)) {
                $adminEmail = setting('admin_email');
                if ($adminEmail) {
                    Mail::to($adminEmail)->send(new SyncSuccessNotification());
                }
            }

            $this->info('BPS data fetching process finished successfully.');
            Log::info($summary . ' (Log ID: ' . $log->id . ')');
            return 0; // Sukses

        } catch (\Exception $e) {
            // 10. CATCH ERROR FATAL (jika ada yg error di luar loop)
            $errorMessage = $e->getMessage();
            $this->error('FATAL ERROR during sync: ' . $errorMessage);
            Log::error('SINKRONISASI FATAL GAGAL: ' . $errorMessage . ' (Log ID: ' . $log->id . ')', ['exception' => $e]);

            // 11. UPDATE LOG INDUK (GAGAL)
            $log->update([
                'status' => 'gagal',
                'finished_at' => now(),
                'summary_message' => "Gagal Total: " . Str::limit($errorMessage, 250),
            ]);

            // 12. KIRIM EMAIL GAGAL
            if (setting('email_notifications', false)) {
                $adminEmail = setting('admin_email');
                if ($adminEmail) {
                    Mail::to($adminEmail)->send(new SyncFailedNotification($errorMessage));
                }
            }

            return 1; // Error

        } finally {
            // 13. SELALU LEPAS KUNCI
            optional($lock)->release();
        }
    }
}
