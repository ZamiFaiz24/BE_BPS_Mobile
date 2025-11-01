<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Infographic extends Model
{
    protected $table = 'infographics';

    protected $fillable = [
        'title',
        'date',
        'category',
        'image_url',
        'link',
        'description',
    ];
}
