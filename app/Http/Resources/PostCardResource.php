<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostCardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'cover_url' => $this->cover_url, // 列表應該用thumb
            // 'cover_url' => $this->getFirstMediaUrl('cover'),
            // 'cover_thumb_url' => $this->getFirstMediaUrl('cover', 'thumb'),
            'user' => new UserCardResource($this->whenLoaded('user')),
        ];
    }
}
