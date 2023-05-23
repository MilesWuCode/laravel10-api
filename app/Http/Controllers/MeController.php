<?php

namespace App\Http\Controllers;

// use App\Http\Requests\MeFileAddRequest;
use App\Http\Requests\MeUpdateRequest;
// use App\Transformers\UserTransformer;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

// use Spatie\Fractal\Facades\Fractal;

class MeController extends Controller
{
    public function show(Request $request): UserResource
    {
        // return new UserResource(PostFacade::create(PostDto::create($request)));
        return new UserResource($request->user());
    }

    /**
     * 更新
     */
    public function update(MeUpdateRequest $request): UserResource
    {
        // 檢查policy,也可以在MeUpdateRequest裡檢查
        $this->authorize('update', $request->user());

        // * example
        // dump($request->validated());
        // dump($request->safe()->only(['name']));
        // dump($request->safe()->except(['other']));
        // dump($request->safe()->all());

        $request->user()->update($request->validated());

        // return Fractal::create($request->user(), new UserTransformer())
        //     ->respond();

        return new UserResource($request->user());
    }

    /**
     * 變更密碼
     */
    public function changePassword(Request $request): JsonResponse
    {
        $this->authorize('update', $request->user());

        $validator = Validator::make($request->all(), [
            'old_password' => 'required|current_password',
            'new_password' => 'required|min:8|max:32|different:old_password',
            'comfirm_password' => 'required|same:new_password',
        ]);

        $validator->validate();

        // 檢查新舊密碼
        if (! Hash::check($request->old_password, $request->user()->password)) {
            $validator->errors()->add('old_password', 'old password wrong');

            return response()->json([
                'message' => 'old password wrong',
                'errors' => $validator->errors()->messages(),
            ], 422);
        }

        $request->user()->update(['password' => Hash::make($request->new_password)]);

        return response()->json(['message' => 'success'], 200);
    }

    /**
     * Avatar
     */
    // public function fileAdd(MeFileAddRequest $request): JsonResponse
    // {
    //     $this->authorize('update', $request->user());

    //     $request->user()->setFile($request->input('collection'), [$request->input('file')]);

    //     return Fractal::create($request->user(), new UserTransformer())
    //         ->respond();
    // }
}
