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
        $bpsApiService->fetchAndStore(
            $this->domain,
            $this->variable,
            $this->tahunMulai,
            $this->tahunAkhir,
            $this->label
        );
        Log::info("Selesai job sinkronisasi untuk: {$this->label}");
    }
}
