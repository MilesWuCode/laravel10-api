<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
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
            'name' => $this->name,
            'link' => $this->link,
            'order_column' => $this->order_column,
            'cover' => $this->getFirstMediaUrl('cover'),
            'cover_thumb' => $this->getFirstMediaUrl('cover', 'thumb'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
