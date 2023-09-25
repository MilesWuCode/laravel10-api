<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

/**
 * 資料邏輯層
 * 可替換成redis或其他資料儲存方式
 */
class PostRepository
{
    /**
     * 清單
     */
    public function list()
    {
        // Eager Loading取資料時會動用的關係再填入
        return Post::with([
            'user',
            'media',
            // 清單不應該有個人化資料,n+1問題存在
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

    /**
     * 新增
     */
    public function create(Request $request): Post
    {
        $user = $request->user();

        $post = $user->posts()->create($request->validated());

        if ($request->has('cover')) {
            $cover = $request->input('cover');

            $post->addMediaFromDisk($cover, 'minio-temporary')->toMediaCollection('cover');
        }

        // 清除快取
        Cache::tags([
            'post.list.user.0',
            'post.list.user.'.$user->id,
            'user.post.list.user.'.$user->id,
        ])->flush();

        return $post;
    }

    /**
     * 更新
     */
    public function update(Request $request, Post $post): Post
    {
        $post->update($request->validated());

        $user = $request->user();

        if ($request->has('cover')) {
            $cover = $request->input('cover');

            $post->addMediaFromDisk($cover, 'minio-temporary')->toMediaCollection('cover');
        }

        // 清除快取
        Cache::tags([
            'post.list.user.0',
            'post.list.user.'.$user->id,
            'user.post.list.user.'.$user->id,
        ])->flush();

        return $post;
    }

    /**
     * 刪除
     */
    public function delete(Post $post): bool
    {
        $isDelete = (bool) $post->deleteOrFail();

        $user = auth()->user();

        // 清除快取
        if ($isDelete) {
            Cache::tags([
                'post.list.user.0',
                'post.list.user.'.$user->id,
                'user.post.list.user.'.$user->id,
            ])->flush();
        }

        return $isDelete;
    }

    /**
     * 用戶的清單
     */
    public function userlist()
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
