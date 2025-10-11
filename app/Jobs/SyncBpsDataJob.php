<?php

namespace App\Jobs;

use App\Services\BpsApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncBpsDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $domain, $variable, $tahunMulai, $tahunAkhir;
    protected string $label;

    public function __construct(int $domain, int $variable, int $tahunMulai, int $tahunAkhir, string $label)
    {
        $this->domain = $domain;
        $this->variable = $variable;
        $this->tahunMulai = $tahunMulai;
        $this->tahunAkhir = $tahunAkhir;
        $this->label = $label;
    }

    public function handle(BpsApiService $bpsApiService): void
    {
        Log::info("Memulai job sinkronisasi untuk: {$this->label}");
        $result = $bpsApiService->fetchData(
            $this->domain,
            [$this->variable],
            $this->tahunMulai,
            $this->tahunAkhir,
            $this->label
        );

        if (!empty($result['error'])) {
            Log::error("Sinkronisasi gagal untuk: {$this->label}. Error: {$result['error']}");
        } elseif ($result['added'] > 0) {
            Log::info("Sinkronisasi selesai untuk: {$this->label}. Data baru: {$result['added']}, update: {$result['updated']}");
        } else {
            Log::info("Sinkronisasi selesai untuk: {$this->label}. Tidak ada data baru.");
        }
    }
}
