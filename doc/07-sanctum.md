# 07-sanctum

```sh
composer require laravel/sanctum

php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

php artisan migrate
```

app/Http/Kernel.php

```diff
    'api' => [
+       \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    ],
```

todo example

-   login
-   crud api
