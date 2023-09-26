<?php

namespace App\Services;

use App\Repositories\MyPostRepository;

/**
 * 商業邏輯層
 */
class MyPostService
{
    protected MyPostRepository $repository;

    /**
     * 依賴注入
     */
    public function __construct()
    {
        $this->repository = new MyPostRepository();
    }

    /**
     * 列表
     */
    public function list()
    {
        return $this->repository->list();
    }
}
