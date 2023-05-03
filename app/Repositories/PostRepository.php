<?php

namespace App\Repositories;

use App\DataTransferObjects\PostDto;
use App\Models\Post;

/**
 * 5.使用repository建立資料
 */

class PostRepository
{
    protected Post $post;

    public function __construct()
    {
        $this->post = new Post();
    }

    public function create(PostDto $PostDto): Post
    {
        return Post::create([
            'title' => $PostDto->title,
            'content' => $PostDto->content,
        ]);
    }
}
