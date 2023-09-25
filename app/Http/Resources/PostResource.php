<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'cover' => $this->getFirstMediaUrl('cover'),
            'cover_thumb' => $this->getFirstMediaUrl('cover', 'thumb'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'reaction' => new ReactionResource($this->whenLoaded('loveReactant', $this)),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
