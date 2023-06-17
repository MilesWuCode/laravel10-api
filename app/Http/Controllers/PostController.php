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

        return response()->json(['message' => 'done'], 200);
    }

    public function reactTo(PostReactRequest $request, Post $post): PostResource
    {
        // wip

        $user = Auth::user();

        $type = strtolower($request->type);

        $reacterFacade = $user->viaLoveReacter();

        foreach (['like', 'dislike'] as $item) {
            if ($item !== $type && $reacterFacade->hasReactedTo($post, $item)) {
                $reacterFacade->unreactTo($post, $item);
            }
        }

        if ($reacterFacade->hasNotReactedTo($post, $type)) {
            $reacterFacade->reactTo($post, $type);
        } else {
            $reacterFacade->unreactTo($post, $type);
        }

        /**
         * 數字不同步
         * 即時同步:QUEUE_CONNECTION=sync
         * 背景同步:QUEUE_CONNECTION=redis
         * 最佳作法由前端的websocket接收通知
         */
        return new PostResource($post);
    }
}
