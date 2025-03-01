<?php

namespace App\Http\Resources\News;

use App\Enums\NewsSource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NytimesPopularResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Extract media URL
        $media = !empty($this->resource['media'][0]['media-metadata'])
            ? $this->resource['media'][0]['media-metadata'][2]['url'] ?? null
            : null;

        // Clean author name
        $author = $this->resource['byline'] ?? null;
        if ($author) {
            $author = preg_replace('/^By\s+/i', '', $author);
        }

        return [
            'source' => NewsSource::NYTIMES->value,
            'platform' => 'The New York Times',
            'author' => $author,
            'title' => $this->resource['title'] ?? '',
            'description' => $this->resource['abstract'] ?? '',
            'url' => $this->resource['url'] ?? '',
            'image_url' => $media,
            'published_at' => $this->resource['published_date'] ?? null,
            'content' => $this->resource['abstract'] ?? '',
            'category' => $this->resource['section'] ?? null,
            'external_id' => $this->resource['uri'] ?? md5($this->resource['url'] ?? ''),
        ];
    }
}
