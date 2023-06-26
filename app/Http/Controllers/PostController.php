<?php

namespace App\Http\Controllers;

use App\Enums\FavoriteReactionEnum;
use App\Enums\LikeReactionEnum;
use App\Facades\PostFacade;
use App\Http\Requests\FavoriteReactRequest;
use App\Http\Requests\LikeReactRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
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

        return Cache::remember('post.index.'.$userId.'.'.$queryString, 300, function () {
            // 快取物件，使用快取時會被再執行一次裡面的函式
            // return new PostCollection(PostFacade::list());

            // 需要先轉成response再做快取
            $collection = new PostCollection(PostFacade::list());

            return $collection->response();
        });
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
        // Eager Loading取資料時會動用的關係再填入
        $post->load([
            'user',
            'loveReactant.reactions.reacter.reacterable',
            'loveReactant.reactions.type',
            'loveReactant.reactionCounters',
            'loveReactant.reactionTotal',
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

    /**
     * like/dislike
     */
    public function reactToLike(LikeReactRequest $request, Post $post): JsonResponse
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

        // ['like', 'dislike']
        $types = array_column(LikeReactionEnum::cases(), 'value');

        // 先移除其他的
        foreach ($types as $item) {
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

    /**
     * add favorite or del favorite
     */
    public function reactToFavorite(FavoriteReactRequest $request, Post $post): JsonResponse
    {
        /**
         * 目標:同時只有一個或沒有
         * 可以做成Repository模式
         */
        $user = Auth::user();

        $action = $request->action;

        $reacterFacade = $user->viaLoveReacter();

        // n+1
        $post->load([
            'loveReactant.reactions',
        ]);

        $favorite = FavoriteReactionEnum::Favorite->value;

        // 沒有加入就加入
        if ($action === 'add' && $reacterFacade->hasNotReactedTo($post, $favorite)) {
            $reacterFacade->reactTo($post, $favorite);
        }

        // 有加入就移除
        if ($action === 'del' && $reacterFacade->hasReactedTo($post, $favorite)) {
            $reacterFacade->unreactTo($post, $favorite);
        }

        // 返回
        return response()->json(['action' => $action], 200);

        /**
         * 數字不同步
         * 即時同步:QUEUE_CONNECTION=sync
         * 背景同步:QUEUE_CONNECTION=redis
         * 最佳作法由前端的websocket接收通知
         * 故不執行return new PostResource($post);
         */
    }
}
