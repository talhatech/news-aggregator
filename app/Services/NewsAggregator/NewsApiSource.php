<?php

namespace App\Services\NewsAggregator;

use Carbon\Carbon;
use App\Enums\NewsSource;
use App\Http\Resources\News\NewsApiResource;

/**
 * NewsAPI Source Implementation
 */
class NewsApiSource extends AbstractNewsSource
{
    protected $baseUrl = 'https://newsapi.org/v2';

    public function getSourceIdentifier(): string
    {
        return NewsSource::NEWSAPI->value;
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
        return NewsApiResource::collection($apiResponse['articles'])->toArray(request());
    }
}
