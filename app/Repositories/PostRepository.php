<?php

namespace App\Repositories;

use App\DTO\PostDTO;
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

    public function create(PostDTO $postDTO): Post
    {
        return Post::create([
            'title' => $postDTO->title,
            'content' => $postDTO->content,
        ]);
    }
}
