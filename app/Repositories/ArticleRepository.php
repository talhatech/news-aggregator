<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\Category;
use App\Models\Platform;
use App\Models\Source;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ArticleRepository
{
    public function getArticles(array $filters = []): Builder
    {
        return Article::query()
            ->search($filters['search'] ?? null)
            ->fromSources($filters['sources'] ?? null)
            ->fromCategories($filters['categories'] ?? null)
            ->publishedBetween($filters['date_from'] ?? null, $filters['date_to'] ?? null)
            ->orderBy('published_at', 'desc');
    }

    public function getCategories(): Builder
    {
        return Category::orderBy('name');
    }

    public function getSources(): Builder
    {
        return Source::orderBy('name');
    }

     /**
     * Save an article and its related entities
     */
    public function saveArticle(array $articleData): Article
    {
        // Find or create the source
        $source = Source::firstOrCreate(
            ['identifier' => $articleData['source']],
            ['name' => $articleData['platform']]
        );

        // Find or create the news platform if available
        $platformId = null;
        if (!empty($articleData['platform'])) {
            $platform = Platform::firstOrCreate(['name' => $articleData['platform']]);
            $platformId = $platform->getKey();
        }

        // Find or create the category if available
        $categoryId = null;
        if (!empty($articleData['category'])) {
            $category = Category::firstOrCreate(['name' => $articleData['category']]);
            $categoryId = $category->getKey();
        }

        // Create or update the article
        return Article::updateOrCreate(
            ['url' => $articleData['url']],
            [
                'source_id' => $source->getKey(),
                'platform_id' => $platformId,
                'category_id' => $categoryId,
                'author' => $articleData['author'] ?? null,
                'title' => $articleData['title'],
                'description' => $articleData['description'] ?? null,
                'image_url' => $articleData['image_url'] ?? null,
                'published_at' => $articleData['published_at'] ?? now(),
                'content' => $articleData['content'] ?? null,
                'external_id' => $articleData['external_id'] ?? null,
            ]
        );
    }
}
