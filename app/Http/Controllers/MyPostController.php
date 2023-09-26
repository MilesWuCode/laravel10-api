<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCollection;
use App\Services\MyPostService;
use Illuminate\Support\Facades\Cache;

class MyPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(MyPostService $service)
    {
        // 所有的query
        $queryString = request()->getQueryString();

        $userId = auth()->id();

        $tag = 'user.'.$userId.'.post.index';

        $key = 'query.'.$queryString;

        // 使用tags可以用名字來清除資料
        $cache = Cache::tags([$tag])->get($key);

        if ($cache) {
            return $cache;
        } else {
            $collection = new PostCollection($service->list());

            $data = $collection->response();

            Cache::tags([$tag])->put($key, $data, 300);

            return $data;
        }
    }
}
