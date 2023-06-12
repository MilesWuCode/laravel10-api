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

    /**
     * 列表
     */
    public function list()
    {
        return $this->postRepository->list();
    }

    /**
     * 新增
     */
    public function create(Request $request): Post
    {
        return $this->postRepository->create($request);
    }
}
