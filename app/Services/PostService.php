<?php

namespace App\Services;

use App\Contracts\PostContract;
use App\DTO\PostDTO;
use App\Models\Post;
use Illuminate\Http\Request;

class PostService implements PostContract
{
    /**
     * 2.使用contract,service建立資料
     * PostContract限制名字為create
     *
     * 3.使用facade建立資料
     * 不用更改
     */
    // public function create(Request $request): Post
    // {
    //     return Post::create([
    //         'title' => $request->title,
    //         'content' => $request->content,
    //     ]);
    // }

    /**
     * 4.使用DTO建立資料
     */
    public function create(PostDTO $postDTO): Post
    {
        return Post::create([
            'title' => $postDTO->title,
            'content' => $postDTO->content,
        ]);
    }
}
