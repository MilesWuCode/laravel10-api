<?php

namespace App\Contracts;

use App\DTO\PostDTO;
use App\Models\Post;
use Illuminate\Http\Request;

interface PostContract
{
    /**
     * 2.使用contract,service建立資料
     */
    // public function create(Request $request): Post;

    /**
     * 4.使用DTO建立資料
     */
    public function create(PostDTO $postDTO): Post;
}
