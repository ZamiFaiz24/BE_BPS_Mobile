<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyncLog extends Model
{
    use HasFactory;

    // Izinkan kolom ini diisi
    protected $guarded = [];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    /**
     * Sebuah Log Sinkronisasi memiliki BANYAK detail.
     */
    public function details()
    {
        return $this->hasMany(SyncLogDetail::class);
    }

    /**
     * Sebuah Log Sinkronisasi dimiliki oleh SATU user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
