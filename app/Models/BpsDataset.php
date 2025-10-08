<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BpsDataset extends Model
{
    protected $table = 'bps_dataset';

    protected $guarded = ['id'];

    // --- Relasi (sebaiknya ada juga) ---
    public function values(): HasMany
    {
        return $this->hasMany(BpsDatavalue::class, 'dataset_id');
    }

    public function dimensions(): HasMany
    {
        return $this->hasMany(BpsDatadimension::class, 'dataset_id');
    }
}
