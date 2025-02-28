<?php

namespace App\Enums;

enum NewsType: string
{
    case TRENDING = 'trending';
    case YESTERDAY = 'yesterday';

    /**
     * Get a human-readable label for the news type
     */
    public function label(): string
    {
        return match($this) {
            self::TRENDING => 'Trending News',
            self::YESTERDAY => 'Yesterday\'s News',
        };
    }

    /**
     * Get description of the news type
     */
    public function description(): string
    {
        return match($this) {
            self::TRENDING => 'The most popular news articles right now',
            self::YESTERDAY => 'News articles published yesterday',
        };
    }
}
