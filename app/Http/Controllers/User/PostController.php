<?php

namespace App\Http\Controllers\User;

use App\Facades\PostFacade;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 所有的query
        $queryString = request()->getQueryString();

        /**
         * 依案子需求
         * 因為有loveReactant
         * 所以每個用戶做快取
         */
        $userId = auth()->user() ? auth()->user()->id : 0;

        $tag = 'user.post.list.user.'.$userId;

        $key = 'query.'.$queryString;

        $cache = Cache::tags([$tag])->get($key);

        if ($cache) {
            return $cache;
        } else {
            $collection = new PostCollection(PostFacade::myPosts());

            $data = $collection->response();

            Cache::tags([$tag])->put($key, $data, 300);

            return $data;
        }
    }
}
