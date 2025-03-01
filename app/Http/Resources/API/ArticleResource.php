<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'author' => $this->author,
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
            'image_url' => $this->image_url,
            'content' => $this->content,
            'external_id' => $this->external_id,
            'source' => SourceResource::make($this->source),
            'platform' => PlatformResource::make($this->platform),
            'category' => CategoryResource::make($this->category),
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Create a new anonymous resource collection.
     *
     * @param  mixed  $resource
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public static function collection($resource)
    {
        $collection = parent::collection($resource);

        // Add pagination metadata if available
        if (method_exists($resource, 'currentPage')) {
            $collection->additional([
                'meta' => [
                    'current_page' => $resource->currentPage(),
                    'from' => $resource->firstItem(),
                    'last_page' => $resource->lastPage(),
                    'per_page' => $resource->perPage(),
                    'to' => $resource->lastItem(),
                    'total' => $resource->total(),
                ]
            ]);
        }

        return $collection;
    }
}
