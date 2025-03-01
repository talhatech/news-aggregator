<?php

namespace App\Http\Resources\News;

use App\Enums\NewsSource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $platform = isset($this->resource['source']['name']) ? $this->resource['source']['name'] : null;

        return [
            'source' => NewsSource::NEWSAPI->value,
            'platform' => $platform,
            'author' => $this->resource['author'] ?? null,
            'title' => $this->resource['title'] ?? '',
            'description' => $this->resource['description'] ?? '',
            'url' => $this->resource['url'] ?? '',
            'image_url' => $this->resource['urlToImage'] ?? null,
            'published_at' => $this->resource['publishedAt'] ?? null,
            'content' => $this->resource['content'] ?? '',
            'category' => null,
            'external_id' => md5($this->resource['url'] ?? ''),
        ];
    }
}
