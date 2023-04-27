<?php

namespace App\Facades;

use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;

class PostFacade extends Facade
{
    /**
     * 3.使用facade建立資料
     * getFacadeAccessor提供服務名字PostService::class
     * 和AppServiceProvider裡bind的名字一樣
     */

    protected static function getFacadeAccessor(): string
    {
        // 給PostService::class會自動bind
        return 'PostService';
    }
}
