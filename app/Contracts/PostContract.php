<?php

namespace App\Contracts;

use App\Models\Post;
use Illuminate\Http\Request;

interface PostContract
{
    public function list();

    public function create(Request $request): Post;

    public function update(Request $request, Post $post): Post;
}
