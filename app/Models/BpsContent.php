<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BpsContent extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'bps_contents';

    protected $fillable = [
        'title',
        'publish_date',
        'type',
        'description',
        'source_url',
        'content_body',
        'file_url',
        'image_url',
        'author',
    ];
}
