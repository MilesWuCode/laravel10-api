<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * 資料邏輯層
 * 可替換成redis或其他資料儲存方式
 */
class PostRepository
{
    public function list()
    {
        // Collection
        // return Post::all();

        // Paginator
        // return Post::paginate(5);

        // QueryString當做key
        // $queryString = request()->getQueryString();

        // $cache = Cache::remember('post.list.'.$queryString, 60, function () {
        //     return Post::paginate(5);
        // });

        /**
         * 頁碼
         */
        $page = request()->get('page', '1');

        /**
         * key|60秒
         */
        $cache = Cache::remember('post.list.page_'.$page, 60, function () {
            return Post::with('user')->paginate(5);
        });

        return $cache;
    }

    public function create(Request $request): Post
    {
        return $request->user()->posts()->create($request->validated());
    }

    public function update(Request $request, Post $post): Post
    {
        $post->update($request->validated());

        return $post;
    }
}
