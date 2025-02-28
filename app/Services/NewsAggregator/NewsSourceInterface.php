<?php

namespace App\Services\NewsAggregator;

/**
 * NewsSource Interface
 * Defines the contract for all news source implementations
 */
interface NewsSourceInterface
{
    /**
     * Fetch trending news articles
     */
    public function fetchTrending(): array;

    /**
     * Fetch yesterday's news articles
     */
    public function fetchYesterday(): array;

    /**
     * Map the API response to our standardized article format
     */
    public function mapToArticleModel(array $apiResponse): array;

    /**
     * Get a unique identifier for this news source
     */
    public function getSourceIdentifier(): string;
}
