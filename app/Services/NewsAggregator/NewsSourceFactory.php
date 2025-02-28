<?php

namespace App\Services\NewsAggregator;

/**
 * News Source Factory
 * Creates and returns different news source instances
 */
class NewsSourceFactory
{
    /**
     * Get all available news sources
     */
    public static function getAllSources(): array
    {
        return [
            new NewsApiSource(),
            new GuardianSource(),
            new NytimesSource(),
            // Add more sources here as needed
        ];
    }

    /**
     * Get a specific news source by identifier
     */
    public static function getSource(string $identifier): ?NewsSourceInterface
    {
        $sources = [
            'newsapi' => NewsApiSource::class,
            'guardian' => GuardianSource::class,
            'nytimes' => NytimesSource::class,
            // Add more mappings here as needed
        ];

        if (!isset($sources[$identifier])) {
            return null;
        }

        $sourceClass = $sources[$identifier];
        return new $sourceClass();
    }
}
