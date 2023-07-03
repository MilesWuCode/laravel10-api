<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Http\Request;

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
        $post = $request->user()->posts()->create($request->validated());

        if ($request->has('cover')) {
            $cover = $request->input('cover');

            $post->addMediaFromDisk($cover, 'minio-temporary')->toMediaCollection('cover');
        }

        return $post;
    }

    public function update(Request $request, Post $post): Post
    {
        $post->update($request->validated());

        if ($request->has('cover')) {
            $cover = $request->input('cover');

            $post->addMediaFromDisk($cover, 'minio-temporary')->toMediaCollection('cover');
        }

        return $post;
    }

    public function delete(Post $post): bool
    {
        return (bool) $post->deleteOrFail();
    }
}
