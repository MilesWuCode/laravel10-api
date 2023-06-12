<?php

namespace App\Services;

use App\Contracts\PostContract;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Http\Request;

/**
 * 商業邏輯層
 */
class PostService implements PostContract
{
    protected PostRepository $postRepository;

    /**
     * 依賴注入
     */
    public function __construct()
    {
        $this->postRepository = new PostRepository();
    }

    // public function list()
    // {
    //     return $this->postRepository->list();
    // }

    public function create(Request $request): Post
    {
        return $this->postRepository->create($request);
    }
}
