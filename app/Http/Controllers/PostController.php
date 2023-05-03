<?php

namespace App\Http\Controllers;

use App\Contracts\PostContract;
use App\DataTransferObjects\PostDto;
use App\Facades\PostFacade;
use App\Http\Requests\PostStoreRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
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
}
