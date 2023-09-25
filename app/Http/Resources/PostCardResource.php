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
            'cover' => $this->getFirstMediaUrl('cover'),
            'cover_thumb' => $this->getFirstMediaUrl('cover', 'thumb'),
            'user' => new UserCardResource($this->whenLoaded('user')),
        ];
    }
}
