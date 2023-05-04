<?php

namespace App\Repositories;

use App\DataTransferObjects\PostDto;
use App\Models\Post;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * 5.使用repository建立資料
 */
class PostRepository
{
    protected Post $post;

    // public function __construct()
    // {
    //     $this->post = new Post();
    // }

    public function list()
    {
        // Collection
        // return Post::all();

        // Paginator
        // return Post::paginate(5);

        // QueryString當做key
        // $queryString = request()->getQueryString();

        // $cache = Cache::remember('posts.list.'.$queryString, 60, function () {
        //     return Post::paginate(5);
        // });

        $page = request()->get('page', '1');

        $cache = Cache::remember('posts.list.page_'.$page, 60, function () {
            return Post::paginate(5);
        });

        return $cache;
    }

    public function create(PostDto $PostDto): Post
    {
        return Post::create([
            'title' => $PostDto->title,
            'content' => $PostDto->content,
        ]);
    }
}
