<?php

namespace App\Http\Controllers;

use App\Enums\FavoriteReactionEnum;
use App\Events\FavoriteReactionEvent;
use App\Http\Requests\FavoriteRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * 數字不同步
 * 即時同步:QUEUE_CONNECTION=sync
 * 背景同步:QUEUE_CONNECTION=redis
 * 最佳作法由前端的websocket接收通知
 * 故不執行return new PostResource($post);
 */
class FavoriteController extends Controller
{
    public function add(FavoriteRequest $request)
    {
        $user = Auth::user();

        $reacterFacade = $user->viaLoveReacter();

        $modelName = $request->validated('model');

        $modelClassName = match ($request->validated('model')) {
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

        $favorite = FavoriteReactionEnum::Favorite->value;

        // 檢查沒有加入
        if ($reacterFacade->hasNotReactedTo($model, $favorite)) {
            $reacterFacade->reactTo($model, $favorite);

            event(new FavoriteReactionEvent($user, $modelName, $model->id, true));
        }

        return response()->json(['message' => 'success'], 200);
    }

    public function del(FavoriteRequest $request)
    {
        $user = Auth::user();

        $reacterFacade = $user->viaLoveReacter();

        $modelName = $request->validated('model');

        $modelClassName = match ($request->validated('model')) {
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

        $favorite = FavoriteReactionEnum::Favorite->value;

        // 檢查有加入
        if ($reacterFacade->hasReactedTo($model, $favorite)) {
            $reacterFacade->unreactTo($model, $favorite);

            event(new FavoriteReactionEvent($user, $modelName, $model->id, false));
        }

        return response()->json(['message' => 'success'], 200);
    }
}
