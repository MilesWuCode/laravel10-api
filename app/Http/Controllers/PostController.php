<?php

namespace App\Http\Controllers;

use App\Contracts\PostContract;
use App\DataTransferObjects\PostDto;
use App\Facades\PostFacade;
use App\Http\Requests\PostStoreRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResource
    {
        // 直接輸出
        // return PostResource::collection(Post::all());
        // paginate功能,/post?page=2
        // return PostResource::collection(Post::paginate());

        // 會自動參照PostResource的欄位,all(),paginate()
        // return new PostCollection(Post::paginate(5));

        // PostRepository加Cache
        return new PostCollection(PostFacade::list());
    }

    /**
     * 1.直接建立資料
     */
    // public function store(Request $request)
    // {
    //     Post::create([
    //         'title' => $request->title,
    //         'content' => $request->content,
    //     ]);
    // }

    /**
     * 2.使用contract,service建立資料
     * PostContract,PostService,AppServiceProvider
     * 用AppServiceProvider的register來bind
     */
    // public function store(Request $request, PostContract $postContract)
    // {
    //     return response()->json($postContract->create($request));
    // }

    /**
     * 3.使用facade建立資料
     * PostContract,PostService,AppServiceProvider,PostFacade
     * 用AppServiceProvider的register來bind
     */
    // public function store(Request $request)
    // {
    //     return response()->json(PostFacade::create($request));
    // }

    /**
     * 4.使用DTO建立資料
     *
     * 5.使用repository建立資料
     */
    public function store(PostStoreRequest $request): PostResource
    {
        // 一般response json回傳
        // return response()->json(PostFacade::create(PostDto::create($request)));

        // 使用PostResource::make()回傳單筆
        // return PostResource::make(PostFacade::create(PostDto::create($request)));

        // 使用new PostResource()回傳單筆
        return new PostResource(PostFacade::create(PostDto::create($request)));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): PostResource
    {
        return new PostResource($post);
    }
}
