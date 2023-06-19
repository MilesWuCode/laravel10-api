<?php

namespace App\Http\Controllers;

use App\Facades\PostFacade;
use App\Http\Requests\PostReactRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): PostCollection
    {
        return new PostCollection(PostFacade::list());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): PostResource
    {
        $post = PostFacade::create($request);

        return new PostResource($post->load('user'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): PostResource
    {
        $post->load([
            'user',
            // 'loveReactant.reactions.reacter.reacterable',
            // 'loveReactant.reactions.type',
            'loveReactant.reactionCounters',
            // 'loveReactant.reactionTotal',
        ]);

        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): PostResource
    {
        PostFacade::update($request, $post);

        return new PostResource($post->load('user'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): JsonResponse
    {
        $this->authorize('delete', $post);

        if (! PostFacade::delete($post)) {
            return response()->json(['message' => 'error'], 400);
        }

        return response()->json(['message' => 'done'], 200);
    }

    public function reactTo(PostReactRequest $request, Post $post): JsonResponse
    {
        /**
         * 目標:同時只有一個或沒有
         * 可以做成Repository模式
         */
        $user = Auth::user();

        $action = $request->action;
        $type = $request->type;

        $reacterFacade = $user->viaLoveReacter();

        // n+1
        $post->load([
            'loveReactant.reactions',
        ]);

        // 先移除其他的
        foreach (['like', 'dislike'] as $item) {
            if ($item !== $type && $reacterFacade->hasReactedTo($post, $item)) {
                $reacterFacade->unreactTo($post, $item);
            }
        }

        // 沒有加入就加入
        if ($action === 'add' && $reacterFacade->hasNotReactedTo($post, $type)) {
            $reacterFacade->reactTo($post, $type);
        }

        // 有加入就移除
        if ($action === 'del' && $reacterFacade->hasReactedTo($post, $type)) {
            $reacterFacade->unreactTo($post, $type);
        }

        // 返回
        return response()->json(['action' => $action, 'type' => $type], 200);

        /**
         * 數字不同步
         * 即時同步:QUEUE_CONNECTION=sync
         * 背景同步:QUEUE_CONNECTION=redis
         * 最佳作法由前端的websocket接收通知
         * 故不執行return new PostResource($post);
         */
    }
}
