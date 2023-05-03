<?php

namespace App\Contracts;

use App\DataTransferObjects\PostDto;
use App\Models\Post;
use Illuminate\Http\Request;

interface PostContract
{
    /**
     * 2.使用contract,service建立資料
     *
     * 3.使用facade建立資料
     */
    // public function create(Request $request): Post;

    /**
     * 4.使用DTO建立資料
     *
     * 5.使用repository建立資料
     */
    public function create(PostDto $PostDto): Post;
}
