<?php

namespace App\Services\NewsAggregator;

use App\Repositories\ArticleRepository;
use App\Enums\NewsType;

/**
 * News Service
 * Main service to interact with news sources and fetch articles
 */
class NewsService
{
    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * Fetch trending news from all sources
     */
    public function getTrendingNews(array $sourceIdentifiers = []): array
    {
        return $this->fetchNewsFromSources(NewsType::TRENDING, $sourceIdentifiers);
    }

    /**
     * Fetch yesterday's news from all sources
     */
    public function getYesterdayNews(array $sourceIdentifiers = []): array
    {
        return $this->fetchNewsFromSources(NewsType::YESTERDAY, $sourceIdentifiers);
    }

    /**
     * Fetch news from specified sources
     */
    private function fetchNewsFromSources(NewsType $type, array $sourceIdentifiers = []): array
    {
        $allArticles = [];

        // Get sources to fetch from
        $sources = empty($sourceIdentifiers)
            ? NewsSourceFactory::getAllSources()
            : array_filter(
                array_map(
                    fn($id) => NewsSourceFactory::getSource($id),
                    $sourceIdentifiers
                )
            );

        // Fetch from each source
        foreach ($sources as $source) {
            if (!$source) continue;

            $articles = $type === NewsType::TRENDING
                ? $source->fetchTrending()
                : $source->fetchYesterday();

            $allArticles = array_merge($allArticles, $articles);
        }

        return $allArticles;
    }

    /**
     * Save articles to the database
     */
    public function saveArticles(array $articles): void
    {
        foreach ($articles as $articleData) {
            $this->articleRepository->saveArticle($articleData);
        }
    }

    /**
     * Fetch and save news from all sources
     */
    public function fetchAndSaveNews(NewsType $type): void
    {
        $articles = $type === NewsType::TRENDING
            ? $this->getTrendingNews()
            : $this->getYesterdayNews();

        $this->saveArticles($articles);
    }
}
