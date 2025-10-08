<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BpsDatavalue extends Model
{
    protected $table = 'bps_datavalue';
    protected $guarded = ['id'];

}