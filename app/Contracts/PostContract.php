<?php

namespace App\Contracts;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;

interface PostContract
{
    public function list();

    public function create(StorePostRequest $request): Post;

    public function update(UpdatePostRequest $request, Post $post): Post;

    public function delete(Post $post): bool;
}
