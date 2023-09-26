<?php

namespace App\Http\Controllers;

use App\Enums\LikeReactionEnum;
use App\Events\LikeReactionEvent;
use App\Facades\PostFacade;
use App\Http\Requests\LikeReactRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostCardResource;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

        // 改用tags保留原語法
        // return Cache::remember('post.index.'.$queryString, 300, function () {
        //     // 快取物件，使用快取時會被再執行一次裡面的函式
        //     // return new PostCollection(PostFacade::list());

        //     // 需要先轉成response再做快取
        //     $collection = new PostCollection(PostFacade::list());

        //     return $collection->response();
        // });

        $tag = 'post.index';

        $key = 'query.'.$queryString;

        // 使用tags可以用名字來清除資料
        $cache = Cache::tags([$tag])->get($key);

        if ($cache) {
            return $cache;
        } else {
            // PostCollection可定義toplevel欄位
            // $collection = new PostCollection(PostFacade::list());

            // PostCardResource可以省Collection檔案
            $collection = PostCardResource::collection(PostFacade::list());

            $data = $collection->response();

            Cache::tags([$tag])->put($key, $data, 300);

            return $data;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Post $post): PostResource
    {
        /**
         * 沒使用middleware(['auth:sanctum'])
         * 但又要取得使用者或登入使用者
         */
        if (auth('sanctum')->check()) {
            auth()->loginUsingId(auth('sanctum')->user()->id);
        }

        // Eager Loading取資料時會動用的關係再填入
        $post->load([
            'user',
            'media',
            'loveReactant.reactions.reacter.reacterable',
            'loveReactant.reactions.type',
            'loveReactant.reactionCounters',
            'loveReactant.reactionTotal',
        ]);

        return new PostResource($post);
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

        return response()->json(['message' => 'success'], 200);
    }

    /**
     * like
     */
    public function like(LikeReactRequest $request, Post $post): JsonResponse
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

            event(new LikeReactionEvent($user));
        }

        // 有加入就移除
        if ($action === 'del' && $reacterFacade->hasReactedTo($post, $type)) {
            $reacterFacade->unreactTo($post, $type);

            event(new LikeReactionEvent($user));
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
