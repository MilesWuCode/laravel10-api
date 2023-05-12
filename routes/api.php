<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MeController;
use App\Http\Controllers\PostController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::middleware('api')->get('/post', [PostController::class, 'index'])->name('post.index');
// Route::middleware('api')->post('/post', [PostController::class, 'store'])->name('post.store');
// Route::middleware('api')->get('/post/{post}', [PostController::class, 'show'])->name('post.show');

// repository example
Route::middleware('api')
    ->controller(PostController::class)
    ->group(function () {
        Route::get('/post', 'index')->name('post.index');
        Route::post('/post', 'store')->name('post.store');
        Route::get('/post/{post}', 'show')->name('post.show');
    });

// auth
Route::controller(AuthController::class)
    ->middleware('throttle:6,1')
    ->prefix('auth')
    ->group(function () {
        Route::post('register', 'register')->name('auth.register');
        Route::post('send-verify-email', 'sendVerifyEmail')->name('auth.send-verify-email');
        Route::post('verify-email', 'verifyEmail')->name('auth.verify-email');
        Route::post('login', 'login')->name('auth.login');
        Route::middleware('auth:sanctum')->post('/logout', 'logout')->name('auth.logout');
    });

// me
// Route::controller(MeController::class)
//     ->middleware(['auth:sanctum', 'throttle:6,1'])
//     ->group(function () {
//         Route::get('/me', 'show')->name('me.show');
//         Route::put('/me', 'update')->name('me.update');
//         Route::put('/me/change-password', 'changePassword')->name('me.change-password');
//         Route::post('/me/file', 'fileAdd')->name('me.file.add');
//     });

// todo
// Route::middleware(['auth:sanctum', 'throttle:6,1'])
//     ->apiResource('todo', TodoController::class);
