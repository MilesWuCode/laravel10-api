<?php

namespace App\Http\Controllers;

use App\Enums\LikeReactionEnum;
use App\Events\LikeReactionEvent;
use App\Http\Requests\LikeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * 數字不同步
 * 即時同步:QUEUE_CONNECTION=sync
 * 背景同步:QUEUE_CONNECTION=redis
 * 最佳作法由前端的websocket接收通知
 * 故不執行return new PostResource($post);
 */
class LikeController extends Controller
{
    public function like(LikeRequest $request): JsonResponse
    {
        $user = Auth::user();

        $reacterFacade = $user->viaLoveReacter();

        $modelName = $request->validated('model');

        $modelClassName = match ($modelName) {
            'post' => 'App\Models\Post',
            'product' => 'App\Models\Product',
        };

        // 可以驗證那一個table有沒有該id
        // Validator::make($request->all(), [
        //     'id' => 'required|integer|exists:'.$modelClassName.',id',
        // ])->validate();

        $modelId = $request->validated('id');

        // 直接查資料在不在
        $model = app($modelClassName)::with([
            'loveReactant.reactions.reacter.reacterable',
        ])->findOrFail($modelId);

        $like = LikeReactionEnum::LIKE->value;

        if ($reacterFacade->hasNotReactedTo($model, $like)) {
            $reacterFacade->reactTo($model, $like);
        }

        $dislike = LikeReactionEnum::DISLIKE->value;

        if ($reacterFacade->hasReactedTo($model, $dislike)) {
            $reacterFacade->unreactTo($model, $dislike);
        }

        event(new LikeReactionEvent($user));

        return response()->json(['message' => 'success'], 200);
    }

    public function unlike(LikeRequest $request): JsonResponse
    {
        $user = Auth::user();

        $reacterFacade = $user->viaLoveReacter();

        $modelName = $request->validated('model');

        $modelClassName = match ($modelName) {
            'post' => 'App\Models\Post',
            'product' => 'App\Models\Product',
        };

        // 可以驗證那一個table有沒有該id
        // Validator::make($request->all(), [
        //     'id' => 'required|integer|exists:'.$modelClassName.',id',
        // ])->validate();

        $modelId = $request->validated('id');

        // 直接查資料在不在
        $model = app($modelClassName)::with([
            'loveReactant.reactions.reacter.reacterable',
        ])->findOrFail($modelId);

        $like = LikeReactionEnum::LIKE->value;

        if ($reacterFacade->hasReactedTo($model, $like)) {
            $reacterFacade->unreactTo($model, $like);
        }

        event(new LikeReactionEvent($user));

        return response()->json(['message' => 'success'], 200);
    }

    public function dislike(LikeRequest $request): JsonResponse
    {
        $user = Auth::user();

        $reacterFacade = $user->viaLoveReacter();

        $modelName = $request->validated('model');

        $modelClassName = match ($modelName) {
            'post' => 'App\Models\Post',
            'product' => 'App\Models\Product',
        };

        // 可以驗證那一個table有沒有該id
        // Validator::make($request->all(), [
        //     'id' => 'required|integer|exists:'.$modelClassName.',id',
        // ])->validate();

        $modelId = $request->validated('id');

        // 直接查資料在不在
        $model = app($modelClassName)::with([
            'loveReactant.reactions.reacter.reacterable',
        ])->findOrFail($modelId);

        $dislike = LikeReactionEnum::DISLIKE->value;

        if ($reacterFacade->hasNotReactedTo($model, $dislike)) {
            $reacterFacade->reactTo($model, $dislike);
        }

        $like = LikeReactionEnum::LIKE->value;

        if ($reacterFacade->hasReactedTo($model, $like)) {
            $reacterFacade->unreactTo($model, $like);
        }

        event(new LikeReactionEvent($user));

        return response()->json(['message' => 'success'], 200);
    }

    public function undislike(LikeRequest $request): JsonResponse
    {
        $user = Auth::user();

        $reacterFacade = $user->viaLoveReacter();

        $modelName = $request->validated('model');

        $modelClassName = match ($modelName) {
            'post' => 'App\Models\Post',
            'product' => 'App\Models\Product',
        };

        // 可以驗證那一個table有沒有該id
        // Validator::make($request->all(), [
        //     'id' => 'required|integer|exists:'.$modelClassName.',id',
        // ])->validate();

        $modelId = $request->validated('id');

        // 直接查資料在不在
        $model = app($modelClassName)::with([
            'loveReactant.reactions.reacter.reacterable',
        ])->findOrFail($modelId);

        $dislike = LikeReactionEnum::DISLIKE->value;

        if ($reacterFacade->hasReactedTo($model, $dislike)) {
            $reacterFacade->unreactTo($model, $dislike);
        }

        event(new LikeReactionEvent($user));

        return response()->json(['message' => 'success'], 200);
    }
}
