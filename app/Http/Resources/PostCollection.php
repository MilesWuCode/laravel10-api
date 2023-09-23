<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCollection extends ResourceCollection
{
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
        //     'cached_at' => now(),
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
