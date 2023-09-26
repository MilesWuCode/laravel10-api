<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MeController;
use App\Http\Controllers\MyPostController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SocialiteController;
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
    ->group(function () {
        Route::post('/auth/register', 'register')->name('auth.register');
        Route::post('/auth/login', 'login')->name('auth.login');
        Route::middleware('auth:sanctum')->post('/auth/logout', 'logout')->name('auth.logout');

        // 寄信5分鐘1次,throttle:次數,分鐘,prefix
        Route::middleware(['auth:sanctum', 'throttle:1,5'])->post('/auth/send-verify-email', 'sendVerifyEmail')->name('auth.send-verify-email');
        Route::post('/auth/verify-email', 'verifyEmail')->name('auth.verify-email');

        // 寄信5分鐘1次,可以客制化錯誤訊息
        Route::middleware('throttle.email')->post('/auth/forgot-password', 'forgotPassword')->name('auth.forgot-password');
        Route::post('/auth/reset-password', 'resetPassword')->name('auth.reset-password');
    });

Route::post('/socialite/signin', SocialiteController::class)->name('socialite.signin');

/**
 * Me
 * 個人資料
 */
Route::controller(MeController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/me', 'show')->name('me.show');
        Route::put('/me', 'update')->name('me.update');
        Route::put('/me/change-password', 'changePassword')->name('me.change-password');
        Route::post('/me/avatar', 'avatar')->name('me.avatar');
    });

/**
 * Post
 * 貼文
 */
Route::controller(PostController::class)
    ->prefix('post')
    ->group(function () {
        Route::get('/', 'index')->name('post.index');
        Route::get('/{post}', 'show')->name('post.show');
        Route::middleware('auth:sanctum')->post('/', 'store')->name('post.store');
        Route::middleware('auth:sanctum')->patch('/{post}', 'update')->name('post.update');
        Route::middleware('auth:sanctum')->delete('/{post}', 'destroy')->name('post.destroy');
    });

/**
 * MyPost
 * 我的貼文
 */
Route::controller(MyPostController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/my/post', 'index')->name('me.post.index');
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
 * Banner廣告
 */
Route::get('/banner', [BannerController::class, 'index'])->name('banner.index');

Route::controller(FavoriteController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::post('/favorite/add', 'add')->name('favorite.add');
        Route::post('/favorite/del', 'del')->name('favorite.del');
    });
