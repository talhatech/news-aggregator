<?php

namespace App\Http\Resources\News;

use App\Enums\NewsSource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NytimesSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Extract multimedia URL
        $multimedia = !empty($this->resource['multimedia'])
            ? "https://static01.nyt.com/" . $this->resource['multimedia'][0]['url']
            : null;

        // Clean author name
        $author = $this->resource['byline']['original'] ?? null;
        if ($author) {
            $author = preg_replace('/^By\s+/i', '', $author);
        }

        return [
            'source' => NewsSource::NYTIMES->value,
            'platform' => 'The New York Times',
            'author' => $author,
            'title' => $this->resource['headline']['main'] ?? '',
            'description' => $this->resource['abstract'] ?? '',
            'url' => $this->resource['web_url'] ?? '',
            'image_url' => $multimedia,
            'published_at' => $this->resource['pub_date'] ?? null,
            'content' => $this->resource['lead_paragraph'] ?? '',
            'category' => $this->resource['section_name'] ?? null,
            'external_id' => $this->resource['_id'] ?? md5($this->resource['web_url'] ?? ''),
        ];
    }
}
