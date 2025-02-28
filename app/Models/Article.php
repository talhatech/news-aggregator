<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'source',
        'source_name',
        'author',
        'title',
        'description',
        'url',
        'image_url',
        'published_at',
        'content',
        'category',
        'external_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
