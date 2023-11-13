<?php

namespace App\Http\Controllers;

use App\Http\Requests\SocialiteSigninRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SocialiteSigninRequest $request): JsonResponse
    {
        $provider = $request->input('provider');

        $token = $request->input('token');

        // userFromToken(access_token)
        $socialiteUser = Socialite::driver($provider)->stateless()->userFromToken($token);

        // updateOrCreate 用戶登入後名字每次都會更新成第三方的名字
        $user = User::updateOrCreate([
            'provider' => $provider,
            'provider_id' => $socialiteUser->id,
        ], [
            'name' => $socialiteUser->name,
            'email' => $socialiteUser->email,
        ]);

        if ($user->email_verified_at === null) {
            $user->markEmailAsVerified();
        }

        $token = $user->createToken('normal');

        return response()->json(['token' => $token->plainTextToken], 200);
    }
}
