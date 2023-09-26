<?php

namespace App\Repositories;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

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
    public function create(StorePostRequest $request): Post
    {
        $user = $request->user();

        $post = $user->posts()->create($request->validated());

        if ($request->hasFile('cover')) {
            $post->addMedia($request->file('cover'))->toMediaCollection('cover');
        }

        return $post;
    }

    /**
     * 更新
     */
    public function update(UpdatePostRequest $request, Post $post): Post
    {
        $post->update($request->validated());

        if ($request->hasFile('cover')) {
            $post->addMedia($request->file('cover'))->toMediaCollection('cover');
        }

        return $post;
    }

    /**
     * 刪除
     */
    public function delete(Post $post): bool
    {
        return (bool) $post->deleteOrFail();
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
