<?php

namespace App\Services;

use App\Contracts\PostContract;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Repositories\PostRepository;

/**
 * 商業邏輯層
 */
class PostService implements PostContract
{
    protected PostRepository $repository;

    /**
     * 依賴注入
     */
    public function __construct()
    {
        $this->repository = new PostRepository();
    }

    /**
     * 列表
     */
    public function list()
    {
        return $this->repository->list();
    }

    /**
     * 新增
     */
    public function create(StorePostRequest $request): Post
    {
        return $this->repository->create($request);
    }

    /**
     * 更新
     */
    public function update(UpdatePostRequest $request, Post $post): Post
    {
        return $this->repository->update($request, $post);
    }

    /**
     * 更新
     */
    public function delete(Post $post): bool
    {
        return $this->repository->delete($post);
    }
}
