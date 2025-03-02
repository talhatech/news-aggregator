<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Article extends BaseModel
{
    protected $fillable = [
        'source_id',
        'platform_id',
        'category_id',
        'external_id',
        'author',
        'title',
        'description',
        'url',
        'image_url',
        'content',
        'published_at',
    ];


    protected $casts = [
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the source that owns the article
     */
    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    /**
     * Get the news platform that owns the article
     */
    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    /**
     * Get the category that owns the article
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        // todo: add full/mysql-fuzzy text search
        return $query->when(
            $search,
            fn($q) =>
            $q->where('title', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%")
                ->orWhere('content', 'like', "%$search%")
        );
    }


    public function scopeFromSources(Builder $query, ?array $source_ids): Builder
    {
        return $query->when($source_ids, function ($query) use ($source_ids) {
            $query->whereHas('source', fn($subQ) => $subQ->whereIn('id', $source_ids));
        });
    }

    public function scopeFromCategories(Builder $query, ?array $category_ids): Builder
    {
        return $query->when($category_ids, function ($query) use ($category_ids) {
            $query->whereHas('category', fn($subQ) => $subQ->whereIn('id', $category_ids));
        });
    }

    public function scopePublishedBetween(Builder $query, ?string $dateFrom, ?string $dateTo): Builder
    {
        return $query->when($dateFrom, fn($q) => $q->where('published_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->where('published_at', '<=', $dateTo));
    }
}
