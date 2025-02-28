<?php

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Pagination\LengthAwarePaginator;

class ArticleRepository
{
    /**
     * Get paginated articles with filters
     */
    public function getArticles(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Article::query()->orderBy('published_at', 'desc');

        // Apply filters
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', "%{$filters['search']}%")
                   ->orWhere('description', 'like', "%{$filters['search']}%")
                   ->orWhere('content', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['source'])) {
            $query->where('source', $filters['source']);
        }

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (!empty($filters['date_from'])) {
            $query->where('published_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('published_at', '<=', $filters['date_to']);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get available categories
     */
    public function getCategories(): array
    {
        return Article::select('category')
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->toArray();
    }

    /**
     * Get available sources
     */
    public function getSources(): array
    {
        return Article::select('source', 'source_name')
            ->distinct()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->source,
                    'name' => $item->source_name
                ];
            })
            ->toArray();
    }
}
