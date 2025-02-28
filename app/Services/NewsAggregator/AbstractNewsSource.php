<?php

namespace App\Services\NewsAggregator;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

/**
 * Abstract NewsSource class
 * Provides common functionality for all news sources
 */
abstract class AbstractNewsSource implements NewsSourceInterface
{
    protected $apiKey;
    protected $baseUrl;
    protected $cacheTime = 60; // minutes

    public function __construct()
    {
        $this->apiKey = $this->getApiKey();
    }

    /**
     * Get API key from environment variables
     */
    protected function getApiKey(): string
    {
        return config("news_sources.{$this->getSourceIdentifier()}.api_key");
    }

    /**
     * Make an HTTP request with proper error handling
     */
    protected function makeRequest(string $endpoint, array $params = []): array
    {
        $cacheKey = "{$this->getSourceIdentifier()}:" . md5($endpoint . json_encode($params));

        return Cache::remember($cacheKey, $this->cacheTime * 60, function () use ($endpoint, $params) {
            try {
                $response = Http::get($endpoint, $params);
                $response->throw();
                return $response->json();
            } catch (\Exception $e) {
                \Log::error("Error fetching from {$this->getSourceIdentifier()}: " . $e->getMessage());
                return [];
            }
        });
    }
}
