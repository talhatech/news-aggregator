<?php

namespace App\Services\NewsAggregator;

use Carbon\Carbon;

/**
 * New York Times Source Implementation
 */
class NytimesSource extends AbstractNewsSource
{
    protected $baseUrl = 'https://api.nytimes.com/svc';

    public function getSourceIdentifier(): string
    {
        return 'nytimes';
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
        $articles = [];

        // Handle most popular API response
        if (isset($apiResponse['results'])) {
            foreach ($apiResponse['results'] as $item) {
                $media = !empty($item['media'][0]['media-metadata'])
                    ? $item['media'][0]['media-metadata'][2]['url'] ?? null
                    : null;

                $articles[] = [
                    'source' => $this->getSourceIdentifier(),
                    'source_name' => 'The New York Times',
                    'author' => $item['byline'] ? preg_replace('/^By\s+/i', '', $item['byline']) : null,
                    'title' => $item['title'] ?? '',
                    'description' => $item['abstract'] ?? '',
                    'url' => $item['url'] ?? '',
                    'image_url' => $media,
                    'published_at' => $item['published_date'] ?? null,
                    'content' => $item['abstract'] ?? '',
                    'category' => $item['section'] ?? null,
                    'external_id' => $item['uri'] ?? md5($item['url']),
                ];
            }
        }
        // Handle article search API response
        elseif (isset($apiResponse['response']['docs'])) {
            foreach ($apiResponse['response']['docs'] as $item) {
                $multimedia = !empty($item['multimedia'])
                    ? "https://static01.nyt.com/" . $item['multimedia'][0]['url']
                    : null;

                $articles[] = [
                    'source' => $this->getSourceIdentifier(),
                    'source_name' => 'The New York Times',
                    'author' => $item['byline']['original'] ?? null,
                    'title' => $item['headline']['main'] ?? '',
                    'description' => $item['abstract'] ?? '',
                    'url' => $item['web_url'] ?? '',
                    'image_url' => $multimedia,
                    'published_at' => $item['pub_date'] ?? null,
                    'content' => $item['lead_paragraph'] ?? '',
                    'category' => $item['section_name'] ?? null,
                    'external_id' => $item['_id'] ?? md5($item['web_url']),
                ];
            }
        }

        return $articles;
    }
}
