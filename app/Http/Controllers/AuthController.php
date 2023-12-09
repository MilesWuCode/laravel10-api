<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\CustomResetPasswordNotification;
use App\Notifications\CustomVerifyEmailNotification;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * 註冊
     */
    public function register(Request $request): JsonResponse
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|max:32',
            'confirm_password' => 'required|same:password',
        ])->validate();

        $input = $request->all();

        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);

        $code = (string) rand(111111, 999999);

        $user->notify(new CustomVerifyEmailNotification($user->email, $code));

        return response()->json([
            'message' => 'success',
            'email' => $user->email,
            'code' => $code,
        ], 200);
    }

    /**
     * 寄驗證信
     */
    public function sendVerifyEmail(Request $request): JsonResponse
    {
        Validator::make($request->all(), [
            'email' => 'required|email',
        ])->validate();

        $email = $request->email;

        $user = User::where('email', $email)->firstOrFail();

        if ($user->hasVerifiedEmail()) {
            abort(400, 'Email already verified.');
        }

        $code = (string) rand(111111, 999999);

        $user->notify(new CustomVerifyEmailNotification($user->email, $code));

        return response()->json([
            'message' => 'success',
            'email' => $user->email,
            'code' => $code,
        ], 200);
    }

    /**
     * 驗證碼
     */
    public function verifyEmail(Request $request): JsonResponse
    {
        Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required',
        ])->validate();

        $user = User::where('email', $request->email)->first();

        is_null($user) && abort(400);

        if ($user->hasVerifiedEmail()) {
            abort(403, 'Your email address is verified.');
        }

        $code = Cache::get('verify_email_notification.email.'.$request->email);

        if (is_null($code) || $request->code !== $code) {
            abort(400);
        }

        // 標記驗證
        $user->markEmailAsVerified();

        // 刪除用過的驗證碼
        Cache::pull('verify_email_notification.email.'.$request->email);

        // 呼叫事件
        event(new Verified($user));

        return response()->json(['message' => 'success'], 200);
    }

    /**
     * 登入
     */
    public function login(Request $request): JsonResponse
    {
        Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8|max:32',
        ])->validate();

        if (! Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $token = Auth::user()->createToken('normal');

        return response()->json(['token' => $token->plainTextToken], 200);
    }

    /**
     * 使用 sanctum/csrf-cookie 參考 Laravel Breeze 生成的api文件
     * composer require laravel/breeze --dev
     * php artisan breeze:install api
     * Kernel.php 的 EnsureFrontendRequestsAreStateful 打開
     */
    public function csrfLogin(Request $request): Response
    {
        Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8|max:32',
        ])->validate();

        if (! Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        return response()->noContent();
    }

    /**
     * 登出
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'success'], 200);
    }

    /**
     * 忘記密碼
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        Validator::make($request->all(), [
            'email' => 'required|email',
        ])->validate();

        $user = User::where('email', $request->email)->first();

        is_null($user) && abort(400);

        $code = (string) rand(111111, 999999);

        $user->notify(new CustomResetPasswordNotification($user->email, $code));

        return response()->json([
            'message' => 'success',
            'email' => $user->email,
            'code' => $code,
        ], 200);
    }

    /**
     * 變更密碼
     */
    public function resetPassword(Request $request): JsonResponse
    {
        Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8|max:32',
            'confirm_password' => 'required|same:password',
            'code' => 'required',
        ])->validate();

        $user = User::where('email', $request->email)->first();

        is_null($user) && abort(400);

        $code = Cache::get('reset_password_notification.email.'.$request->email);

        if (is_null($code) || $request->code !== $code) {
            abort(400);
        }

        $user->password = Hash::make($request->password);

        $user->save();

        // 刪除用過的驗證碼
        Cache::pull('reset_password_notification.email.'.$request->email);

        return response()->json(['message' => 'success'], 200);
    }
}
