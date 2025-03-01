<?php

namespace App\Services\NewsAggregator;

use Carbon\Carbon;
use App\Enums\NewsSource;
use App\Http\Resources\News\GuardianResource;

/**
 * The Guardian Source Implementation
 */
class GuardianSource extends AbstractNewsSource
{
    protected $baseUrl = 'https://content.guardianapis.com';

    public function getSourceIdentifier(): string
    {
        return NewsSource::GUARDIAN->value;
    }

    public function fetchTrending(): array
    {
        $endpoint = "{$this->baseUrl}/search";
        $params = [
            'section' => 'news',
            'order-by' => 'relevance',
            'api-key' => $this->apiKey,
            'show-fields' => 'headline,trailText,thumbnail,bodyText,byline',
        ];

        $response = $this->makeRequest($endpoint, $params);
        return $this->mapToArticleModel($response);
    }

    public function fetchYesterday(): array
    {
        $yesterday = Carbon::yesterday()->format('Y-m-d');

        $endpoint = "{$this->baseUrl}/search";
        $params = [
            'from-date' => $yesterday,
            'to-date' => $yesterday,
            'order-by' => 'newest',
            'api-key' => $this->apiKey,
            'show-fields' => 'headline,trailText,thumbnail,bodyText,byline',
        ];

        $response = $this->makeRequest($endpoint, $params);
        return $this->mapToArticleModel($response);
    }

    public function mapToArticleModel(array $apiResponse): array
    {
        if (empty($apiResponse) || !isset($apiResponse['response']['results'])) {
            return [];
        }

        return GuardianResource::collection($apiResponse['response']['results'])->toArray(request());
    }
}
