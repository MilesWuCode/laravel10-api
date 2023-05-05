<?php

namespace App\Providers;

use App\Contracts\PostContract;
use App\Services\PostService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        /**
         * 2.使用contract,service建立資料
         * PostContract,PostService
         */
        // $this->app->bind(PostContract::class, PostService::class);

        /**
         * 3.使用facade建立資料
         * 4.使用DTO建立資料
         * 5.使用repository建立資料
         *
         * PostFacade bind PostService
         */
        $this->app->bind('PostService', function () {
            return new PostService();
        });

        // telescope
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
