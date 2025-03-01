<?php

namespace App\Models;

class Category extends BaseModel
{
    protected $fillable = [
        'name',
    ];

    /**
     * Get the articles for the category
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
