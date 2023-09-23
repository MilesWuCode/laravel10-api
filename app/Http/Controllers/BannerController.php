<?php

namespace App\Http\Controllers;

use App\Http\Resources\BannerCollection;
use App\Services\BannerService;
use Illuminate\Support\Facades\Cache;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BannerService $bannerService)
    {
        // 不用快取時
        // return new BannerCollection($bannerService->list());

        return Cache::remember('banner.index', 300, function () use($bannerService) {
            // 快取物件，使用快取時會被再執行一次裡面的函式
            // return new BannerCollection($bannerService->list());

            // 需要先轉成response再做快取
            $collection = new BannerCollection($bannerService->list());

            return $collection->response();
        });
    }
}
