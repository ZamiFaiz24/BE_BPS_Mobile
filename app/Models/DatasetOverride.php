<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatasetOverride extends Model
{
    protected $table = 'dataset_overrides';

    protected $fillable = [
        'dataset_id',
        'source_type',
        'enabled',
        'api_url',
        'config',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'config' => 'array',
        'enabled' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope untuk filter datasets yang enabled
     */
    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    /**
     * Scope untuk filter datasets yang disabled
     */
    public function scopeDisabled($query)
    {
        return $query->where('enabled', false);
    }

    /**
     * Scope untuk filter quick add datasets
     */
    public function scopeQuickAdd($query)
    {
        return $query->where('source_type', 'quick_add');
    }

    /**
     * Scope untuk filter config datasets
     */
    public function scopeFromConfig($query)
    {
        return $query->where('source_type', 'config');
    }
}
