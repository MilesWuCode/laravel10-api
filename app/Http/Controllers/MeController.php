<?php

namespace App\Http\Controllers;

use App\Http\Requests\MeAvatarRequest;
use App\Http\Requests\UpdateMeRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MeController extends Controller
{
    public function show(Request $request): UserResource
    {
        return new UserResource($request->user()->load('media'));
    }

    /**
     * 更新
     */
    public function update(UpdateMeRequest $request): UserResource
    {
        // 檢查policy,也可以在UpdateMeRequest裡檢查
        // $this->authorize('update', $request->user());

        // * example
        // dump($request->validated());
        // dump($request->safe()->only(['name']));
        // dump($request->safe()->except(['other']));
        // dump($request->safe()->all());

        $request->user()->load('media')->update($request->validated());

        return new UserResource($request->user());
    }

    /**
     * 變更密碼
     */
    public function changePassword(Request $request): JsonResponse
    {
        // 檢查policy
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
    public function avatar(MeAvatarRequest $request): UserResource
    {
        // 檢查policy,也可以在MeFileRequest裡檢查
        // $this->authorize('update', $request->user());

        if ($request->hasFile('avatar')) {
            Auth::user()->addMedia($request->file('avatar'))->toMediaCollection('avatar');
        }

        return new UserResource($request->user()->load('media'));
    }
}
