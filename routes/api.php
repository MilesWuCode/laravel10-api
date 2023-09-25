<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\MeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\User\PostController as UserPostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

/**
 * Auth
 * 身份驗證
 */
Route::controller(AuthController::class)
    ->prefix('auth')
    ->group(function () {
        Route::post('register', 'register')->name('auth.register');
        Route::post('login', 'login')->name('auth.login');
        // 寄信5分鐘1次,throttle:次數,分鐘,prefix
        Route::middleware(['auth:sanctum', 'throttle:1,5'])->post('send-verify-email', 'sendVerifyEmail')->name('auth.send-verify-email');
        Route::post('verify-email', 'verifyEmail')->name('auth.verify-email');
        Route::middleware('auth:sanctum')->post('/logout', 'logout')->name('auth.logout');
        // 寄信5分鐘1次,可以客制化錯誤訊息
        Route::middleware('throttle.email')->post('forgot-password', 'forgotPassword')->name('auth.forgot-password');
        Route::post('reset-password', 'resetPassword')->name('auth.reset-password');
    });

Route::post('/socialite/signin', SocialiteController::class)->name('socialite.signin');

/**
 * Me
 * 個人資料
 */
Route::controller(MeController::class)
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/me', 'show')->name('me.show');
        Route::put('/me', 'update')->name('me.update');
        Route::put('/me/change-password', 'changePassword')->name('me.change-password');
        Route::post('/me/avatar', 'avatar')->name('me.avatar');
    });

/**
 * File
 * 檔案上傳到暫存區
 * 並回傳暫時網址
 */
Route::middleware('auth:sanctum')
    ->post('/file/temporary', [FileController::class, 'temporary'])
    ->name('file.temporary');

/**
 * Post
 */
Route::controller(PostController::class)
    ->prefix('post')
    ->group(function () {
        Route::get('/', 'index')->name('post.index');
        Route::get('/{post}', 'show')->name('post.show');
        Route::middleware(['auth:sanctum'])->post('/', 'store')->name('post.store');
        Route::middleware(['auth:sanctum'])->patch('/{post}', 'update')->name('post.update');
        Route::middleware(['auth:sanctum'])->delete('/{post}', 'destroy')->name('post.destroy');
    });

/**
 * Post
 * 需要登入
 * like, favorite
 */
// Route::controller(PostController::class)
//     ->middleware(['auth:sanctum'])
//     ->prefix('post')
//     ->group(function () {
//         Route::post('/{post}/like', 'like')->name('post.like');
//         Route::post('/{post}/favorite', 'favorite')->name('post.favorite');
//     });

/**
 * UserPost apiResource
 * 增刪改查
 */
Route::apiResource('user/post', UserPostController::class)
    ->middleware(['auth:sanctum'])
    ->except(['show'])
    ->names('user.post');

/**
 * Banner廣告
 */
Route::get('/banner', [BannerController::class, 'index'])->name('banner.index');
