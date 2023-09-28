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
        $model = app($modelClassName)::findOrFail($modelId);

        // // ['like', 'dislike']
        // $types = array_column(LikeReactionEnum::cases(), 'value');

        $reactionTypeName = LikeReactionEnum::LIKE->value;

        if ($reacterFacade->hasReactedTo($model, $reactionTypeName)) {
            return response()->json(['message' => 'not change'], 200);
        }

        $reacterFacade->reactTo($model, $reactionTypeName);

        event(new LikeReactionEvent($user));

        return response()->json(['message' => 'success'], 200);

        // // 先移除其他的
        // foreach ($types as $item) {
        //     if ($item !== $type && $reacterFacade->hasReactedTo($post, $item)) {
        //         $reacterFacade->unreactTo($post, $item);
        //     }
        // }

        // // 沒有加入就加入
        // if ($action === 'add' && $reacterFacade->hasNotReactedTo($post, $type)) {
        //     $reacterFacade->reactTo($post, $type);

        //     event(new LikeReactionEvent($user));
        // }

        // // 有加入就移除
        // if ($action === 'del' && $reacterFacade->hasReactedTo($post, $type)) {
        //     $reacterFacade->unreactTo($post, $type);

        //     event(new LikeReactionEvent($user));
        // }
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
        $model = app($modelClassName)::findOrFail($modelId);

        $reactionTypeName = LikeReactionEnum::DISLIKE->value;

        if ($reacterFacade->hasReactedTo($model, $reactionTypeName)) {
            return response()->json(['message' => 'not change'], 200);
        }

        $reacterFacade->reactTo($model, $reactionTypeName);

        event(new LikeReactionEvent($user));

        return response()->json(['message' => 'success'], 200);
    }

    public function unset(LikeRequest $request): JsonResponse
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
        $model = app($modelClassName)::findOrFail($modelId);

        // ['like', 'dislike']
        $reactionTypesName = array_column(LikeReactionEnum::cases(), 'value');

        foreach ($reactionTypesName as $reactionTypeName) {
            if ($reacterFacade->hasReactedTo($model, $reactionTypeName)) {
                $reacterFacade->unreactTo($model, $reactionTypeName);
            }
        }

        event(new LikeReactionEvent($user));

        return response()->json(['message' => 'success'], 200);
    }
}
