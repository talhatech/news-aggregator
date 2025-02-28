<?php

namespace App\Services\NewsAggregator;

use Carbon\Carbon;

/**
 * The Guardian Source Implementation
 */
class GuardianSource extends AbstractNewsSource
{
    protected $baseUrl = 'https://content.guardianapis.com';

    public function getSourceIdentifier(): string
    {
        return 'guardian';
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

        $articles = [];
        foreach ($apiResponse['response']['results'] as $item) {
            $fields = $item['fields'] ?? [];

            $articles[] = [
                'source' => $this->getSourceIdentifier(),
                'source_name' => 'The Guardian',
                'author' => $fields['byline'] ?? null,
                'title' => $fields['headline'] ?? $item['webTitle'] ?? '',
                'description' => $fields['trailText'] ?? '',
                'url' => $item['webUrl'] ?? '',
                'image_url' => $fields['thumbnail'] ?? null,
                'published_at' => $item['webPublicationDate'] ?? null,
                'content' => $fields['bodyText'] ?? '',
                'category' => $item['sectionName'] ?? null,
                'external_id' => $item['id'] ?? md5($item['webUrl']),
            ];
        }

        return $articles;
    }
}
