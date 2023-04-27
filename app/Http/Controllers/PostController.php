<?php

namespace App\Http\Controllers;

use App\Contracts\PostContract;
use App\DTO\PostDTO;
use App\Facades\PostFacade;
use App\Http\Requests\PostStoreRequest;
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
     */
    public function store(PostStoreRequest $request)
    {
        return response()->json(PostFacade::create(PostDTO::create($request)));
    }
}
