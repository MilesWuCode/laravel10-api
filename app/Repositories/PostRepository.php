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
    public function list()
    {
        // Eager Loading取資料時會動用的關係再填入
        return Post::with([
            'user',
            'loveReactant.reactions.reacter.reacterable',
            'loveReactant.reactions.type',
            'loveReactant.reactionCounters',
            'loveReactant.reactionTotal',
        ])
            ->orderBy('id', 'desc')
            ->paginate(request()->get('limit', 15)) // 每頁幾筆資料
          // ->simplePaginate(5) // 不提供頁數號碼只提供上一頁跟下一頁
          // ->cursorPaginate(5, ['*'], 'page') // 座標
            ->appends(request()->query()); // 生成的links帶queryString
    }

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
            'post.index.user.0',
            'post.index.user.'.$user->id,
            'post.myposts.user.'.$user->id,
        ])->flush();

        return $post;
    }

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
            'post.index.user.0',
            'post.index.user.'.$user->id,
            'post.myposts.user.'.$user->id,
        ])->flush();

        return $post;
    }

    public function delete(Post $post): bool
    {
        $isDelete = (bool) $post->deleteOrFail();

        $user = auth()->user();

        // 清除快取
        if ($isDelete) {
            Cache::tags([
                'post.index.user.0',
                'post.index.user.'.$user->id,
                'post.myposts.user.'.$user->id,
            ])->flush();
        }

        return $isDelete;
    }

    public function myPosts()
    {
        // Eager Loading取資料時會動用的關係再填入
        return Auth::user()->posts()->with([
            'user',
            'loveReactant.reactions.reacter.reacterable',
            'loveReactant.reactions.type',
            'loveReactant.reactionCounters',
            'loveReactant.reactionTotal',
        ])
            ->orderBy('id', 'desc')
            ->paginate(request()->get('limit', 15)) // 每頁幾筆資料
          // ->simplePaginate(5) // 不提供頁數號碼只提供上一頁跟下一頁
          // ->cursorPaginate(5, ['*'], 'page') // 座標
            ->appends(request()->query()); // 生成的links帶queryString
    }
}
