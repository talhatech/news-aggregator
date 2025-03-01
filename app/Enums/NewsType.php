<?php

namespace App\Enums;

use Illuminate\Support\Collection;

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

     /**
     * Get a list of all news types with their labels and descriptions
     */
    public static function types(): Collection
    {
        return collect(self::cases())->map(fn ($type) => (object) [
            'id' => $type->value,
            'name' => $type->label(),
            'description' => $type->description(),
        ]);
    }
}
