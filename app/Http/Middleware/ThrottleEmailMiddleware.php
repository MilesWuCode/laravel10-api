<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ThrottleEmailMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 利用email來當唯一值
        $email = $request->email;

        // 鍵值
        $key = 'throttle_email_'.$email;

        // 快取數量超過1就錯誤
        if (Cache::has($key) && Cache::get($key) >= 1) {
            abort(429, '5分鐘後才能再次寄送信件');
        }

        // 建立快取存活時間5分鐘
        Cache::add($key, 1, now()->addMinutes(5));

        return $next($request);
    }
}
