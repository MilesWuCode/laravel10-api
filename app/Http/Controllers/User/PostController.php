<?php

namespace App\Http\Controllers\User;

use App\Facades\PostFacade;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
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

        $tag = 'post.my-posts.user.'.$userId;

        $key = 'query.'.$queryString;

        $cache = Cache::tags([$tag])->get($key);

        if ($cache) {
            return $cache;
        } else {
            $collection = new PostCollection(PostFacade::myPosts());

            $data = $collection->response();

            Cache::tags([$tag])->put($key, $data, 300);

            return $data;
        }
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
}
