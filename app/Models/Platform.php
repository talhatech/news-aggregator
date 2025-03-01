<?php

namespace App\Models;


class Platform extends BaseModel
{
    protected $fillable = [
        'name',
    ];

    /**
     * Get the articles for the news platform
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
