<?php

namespace App\Services\NewsAggregator;

use Carbon\Carbon;
use App\Enums\NewsSource;
use App\Http\Resources\News\NytimesPopularResource;
use App\Http\Resources\News\NytimesSearchResource;

/**
 * New York Times Source Implementation
 */
class NytimesSource extends AbstractNewsSource
{
    protected $baseUrl = 'https://api.nytimes.com/svc';

    public function getSourceIdentifier(): string
    {
        return NewsSource::NYTIMES->value;
    }

    public function fetchTrending(): array
    {
        $endpoint = "{$this->baseUrl}/mostpopular/v2/viewed/1.json";
        $params = [
            'api-key' => $this->apiKey
        ];

        $response = $this->makeRequest($endpoint, $params);
        return $this->mapToArticleModel($response);
    }

    public function fetchYesterday(): array
    {
        $yesterday = Carbon::yesterday()->format('Ymd');

        $endpoint = "{$this->baseUrl}/search/v2/articlesearch.json";
        $params = [
            'begin_date' => $yesterday,
            'end_date' => $yesterday,
            'sort' => 'newest',
            'api-key' => $this->apiKey
        ];

        $response = $this->makeRequest($endpoint, $params);
        return $this->mapToArticleModel($response);
    }

    public function mapToArticleModel(array $apiResponse): array
    {
        if (isset($apiResponse['results'])) {
            return NytimesPopularResource::collection($apiResponse['results'])->toArray(request());
        }
        elseif (isset($apiResponse['response']['docs'])) {
            return NytimesSearchResource::collection($apiResponse['response']['docs'])->toArray(request());
        }

        return [];
    }
}
