<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 *  使用Facade更用引用
 */
class PostFacade extends Facade
{
    /**
     * 與AppServiceProvider的bind相同名稱
     * 使用回傳的Service物件
     */
    protected static function getFacadeAccessor(): string
    {
        return 'PostService';
    }
}
