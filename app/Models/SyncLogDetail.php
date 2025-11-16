<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyncLogDetail extends Model
{
    use HasFactory;

    // Izinkan kolom ini diisi
    protected $guarded = [];

    /**
     * Sebuah Detail Log dimiliki oleh SATU Log Sinkronisasi.
     */
    public function log()
    {
        return $this->belongsTo(SyncLog::class, 'sync_log_id');
    }
}