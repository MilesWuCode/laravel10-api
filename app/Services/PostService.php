<?php

namespace App\Services;

use App\Contracts\PostContract;
use App\DataTransferObjects\PostDto;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Http\Request;

class PostService implements PostContract
{
    /**
     * 2.使用contract,service建立資料
     * 3.使用facade建立資料
     *
     * PostContract限制名字為create
     * 在Post::create()之前可以寫商業邏輯
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
     * PostContract限制名字為create
     * 在Post::create()之前可以寫商業邏輯
     */
    // public function create(PostDto $PostDto): Post
    // {
    //     return Post::create([
    //         'title' => $PostDto->title,
    //         'content' => $PostDto->content,
    //     ]);
    // }

    /**
     * 5.使用repository建立資料
     * PostContract限制名字為create
     * 在$this->postRepository->create()之前可以寫商業邏輯
     */
    protected PostRepository $postRepository;

    public function __construct()
    {
        $this->postRepository = new PostRepository();
    }

    public function list()
    {
        return $this->postRepository->list();
    }

    public function create(PostDto $PostDto): Post
    {
        return $this->postRepository->create($PostDto);
    }
}
