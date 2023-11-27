<?php

namespace App\Repositories;

use App\Models\Banner;

/**
 * 資料邏輯層
 */
class BannerRepository
{
    /**
     * 清單
     */
    public function list()
    {
        return Banner::with(['media'])
            ->orderBy('order_column', 'asc')
            ->paginate(request()->get('limit', 15)) // 每頁幾筆資料
          // ->simplePaginate(5) // 不提供頁數號碼只提供上一頁跟下一頁
          // ->cursorPaginate(5, ['*'], 'page') // 座標
            ->appends(request()->query()); // 生成的links帶queryString
    }
}
