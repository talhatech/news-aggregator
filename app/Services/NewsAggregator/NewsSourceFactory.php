<?php

namespace App\Services\NewsAggregator;

use App\Models\Source;
use App\Enums\NewsSource;

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
        $sourceInstances = [];
        $activeSources = Source::active()->get();

        foreach ($activeSources as $source) {
            $instance = self::createSourceInstance($source->identifier);
            if ($instance) {
                $sourceInstances[] = $instance;
            }
        }

        return $sourceInstances;
    }

    /**
     * Get a specific news source by identifier
     */
    public static function getSource(string $identifier): ?NewsSourceInterface
    {
        $source = Source::where('identifier', $identifier)->active()->first();
        return $source ? self::createSourceInstance($identifier): null;
    }

    /**
     * Get a source by enum
     */
    public static function getSourceByEnum(NewsSource $source): ?NewsSourceInterface
    {
        return self::getSource($source->value);
    }

    /**
     * Create a source instance based on identifier
     */
    private static function createSourceInstance(string $identifier): ?NewsSourceInterface
    {
        $sources = [
            NewsSource::NEWSAPI->value => NewsApiSource::class,
            NewsSource::GUARDIAN->value => GuardianSource::class,
            NewsSource::NYTIMES->value => NytimesSource::class,
            // Add more mappings here as needed
        ];

        if (!isset($sources[$identifier])) {
            return null;
        }

        $sourceClass = $sources[$identifier];
        return new $sourceClass();
    }
}
