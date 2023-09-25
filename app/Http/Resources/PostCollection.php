<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCollection extends ResourceCollection
{
    // 指定使用Resource
    public $collects = PostCardResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);

        /**
         * PostResource的格式相同
         */
        // return [
        //     // 可改由with()加入
        //     // 'cached_at' => now(),
        //     'data' => $this->collection,
        // ];
    }

    public function with(Request $request)
    {
        return [
            'cached_at' => now(),
        ];
    }
}
