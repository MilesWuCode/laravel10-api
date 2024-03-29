<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'avatar_url' => $this->avatar_url,
            // 'avatar_url' => $this->getFirstMediaUrl('avatar'),
            // 'avatar_thumb_url' => $this->getFirstMediaUrl('avatar', 'thumb'),
            'email_verified_at' => $this->email_verified_at,
            'provider' => $this->provider,
            'provider_id' => $this->provider_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            /**
             * 關聯顯示
             */
            'posts' => PostResource::collection(
                $this->whenLoaded('posts'),
            ),
        ];
    }
}
