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
        $email = $request->email;

        $key = 'email_throttle_'.$email;

        $maxRequests = 1; // 每個 IP 位址每 5 分鐘允許的最大請求數量

        $decayMinutes = 5; // 時間間隔（分鐘）

        if (Cache::has($key) && Cache::get($key) >= $maxRequests) {
            abort(429, 'Too Many Requests');
        }

        Cache::add($key, 1, now()->addMinutes($decayMinutes));

        return $next($request);
    }
}
