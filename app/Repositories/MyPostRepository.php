<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;

/**
 * 資料邏輯層
 * 可替換成redis或其他資料儲存方式
 */
class MyPostRepository
{
    /**
     * 清單
     */
    public function list()
    {
        // Eager Loading取資料時會動用的關係再填入
        return Auth::user()->posts()->with([
            'user',
            'media',
            // 'loveReactant.reactions.reacter.reacterable',
            // 'loveReactant.reactions.type',
            // 'loveReactant.reactionCounters',
            // 'loveReactant.reactionTotal',
        ])
            ->orderBy('id', 'desc')
            ->paginate(request()->get('limit', 15)) // 每頁幾筆資料
          // ->simplePaginate(5) // 不提供頁數號碼只提供上一頁跟下一頁
          // ->cursorPaginate(5, ['*'], 'page') // 座標
            ->appends(request()->query()); // 生成的links帶queryString
    }
}
