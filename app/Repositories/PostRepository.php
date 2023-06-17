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
        // 頁碼
        $page = request()->get('page');

        // 數量
        $limit = request()->get('limit', 15);

        // 所有的query
        $queryString = request()->getQueryString();

        /**
         * 做暫存
         * key:$page或$queryString|600秒
         */
        $cache = Cache::remember('post.list.'.$queryString, 600, fn () =>
            // 取資料
            Post::with('user')
                ->with([
                    'loveReactant.reactions.reacter.reacterable',
                    'loveReactant.reactions.type',
                    'loveReactant.reactionCounters',
                    'loveReactant.reactionTotal',
                ])
                ->paginate($limit) // 每頁幾筆資料
                // ->simplePaginate(5) // 不提供頁數號碼只提供上一頁跟下一頁
                // ->cursorPaginate(5, ['*'], 'page') // 座標
                ->appends(request()->query()) // 生成的links帶queryString
        );

        // 返回值
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

    public function delete(Post $post): bool
    {
        return (bool) $post->deleteOrFail();
    }
}
