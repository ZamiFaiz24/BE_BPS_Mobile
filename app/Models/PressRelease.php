<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PressRelease extends Model
{
    protected $table = 'press_releases';

    protected $fillable = [
        'date',
        'title',
        'abstract',
        'thumbnail_url',
        'pdf_url',      // <-- WAJIB ADA
        'downloads',
        'link',
        'category',
        'content_html',
        'content_text',
    ];

    protected $casts = [
        'date' => 'date',
        'downloads' => 'array',
    ];
}
