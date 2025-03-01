<?php

namespace App\Http\Resources\News;

use App\Enums\NewsSource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuardianResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $fields = $this->resource['fields'] ?? [];

        return [
            'source' => NewsSource::GUARDIAN->value,
            'platform' => 'The Guardian',
            'author' => $fields['byline'] ?? null,
            'title' => $fields['headline'] ?? $this->resource['webTitle'] ?? '',
            'description' => $fields['trailText'] ?? '',
            'url' => $this->resource['webUrl'] ?? '',
            'image_url' => $fields['thumbnail'] ?? null,
            'published_at' => $this->resource['webPublicationDate'] ?? null,
            'content' => isset($fields['bodyText']) ? $fields['bodyText'] : '',
            'category' => $this->resource['sectionName'] ?? null,
            'external_id' => $this->resource['id'] ?? md5($this->resource['webUrl'] ?? ''),
        ];
    }
}
