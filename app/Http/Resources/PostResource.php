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
        // 預設
        // return parent::toArray($request);

        // 自行填加欄位
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // 與User做關聯顯示
            // 'user' => UserResource::collection(
            //     $this->whenLoaded('user'),
            // ),
        ];
    }

    /**
     * collection keys will be preserved
     * Post::all()->keyBy->id,true:保留key
     */
    // public $preserveKeys = true;
}
