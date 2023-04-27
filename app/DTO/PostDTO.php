<?php

namespace App\DTO;

use App\Http\Requests\PostStoreRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostDTO
{
    /**
     * 4.使用DTO建立資料
     */
    public function __construct(
        public readonly string $title,
        public readonly string $content
    ) {
    }

    public static function create(PostStoreRequest $request): PostDTO
    {
        return new self(
            title: $request->title,
            content: $request->content,
        );
    }
}
