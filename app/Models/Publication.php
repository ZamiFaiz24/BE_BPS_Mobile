<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    protected $table = 'publications';

    protected $fillable = [
        'title',
        'release_date',
        'category',
        'cover_url',
        'pdf_url',
        'downloads',
        'link',
        'abstract',
    ];

    protected $casts = [
        'release_date' => 'date',
        'downloads' => 'array',
    ];
}
