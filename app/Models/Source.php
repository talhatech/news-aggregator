<?php

namespace App\Models;


class Source extends BaseModel
{

    protected $fillable = [
        'identifier',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the articles for the source
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Scope a query to only include active sources.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
