<?php

namespace App\Services\NewsAggregator;

use Carbon\Carbon;

/**
 * NewsAPI Source Implementation
 */
class NewsApiSource extends AbstractNewsSource
{
    protected $baseUrl = 'https://newsapi.org/v2';

    public function getSourceIdentifier(): string
    {
        return 'newsapi';
    }

    public function fetchTrending(): array
    {
        $endpoint = "{$this->baseUrl}/top-headlines";
        $params = [
            'country' => 'us',
            'apiKey' => $this->apiKey
        ];

        $response = $this->makeRequest($endpoint, $params);
        return $this->mapToArticleModel($response);
    }

    public function fetchYesterday(): array
    {
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $today = Carbon::today()->format('Y-m-d');

        $endpoint = "{$this->baseUrl}/everything";
        $params = [
            'from' => $yesterday,
            'to' => $today,
            'sortBy' => 'popularity',
            'apiKey' => $this->apiKey
        ];

        $response = $this->makeRequest($endpoint, $params);
        return $this->mapToArticleModel($response);
    }

    public function mapToArticleModel(array $apiResponse): array
    {
        if (empty($apiResponse) || !isset($apiResponse['articles'])) {
            return [];
        }

        $articles = [];
        foreach ($apiResponse['articles'] as $item) {
            $articles[] = [
                'source' => $this->getSourceIdentifier(),
                'source_name' => $item['source']['name'] ?? 'NewsAPI',
                'author' => $item['author'] ?? null,
                'title' => $item['title'] ?? '',
                'description' => $item['description'] ?? '',
                'url' => $item['url'] ?? '',
                'image_url' => $item['urlToImage'] ?? null,
                'published_at' => $item['publishedAt'] ?? null,
                'content' => $item['content'] ?? '',
                'category' => null, // Not provided by NewsAPI directly
                'external_id' => md5($item['url']), // Generate a unique ID
            ];
        }

        return $articles;
    }
}
