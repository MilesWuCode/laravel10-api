# 04-telescope

http://127.0.0.1:8000/telescope

安裝

```sh
# 本地開發--dev
composer require laravel/telescope --dev

# 前端頁面
php artisan telescope:install

# 資料表
php artisan migrate
```

設定

```php
# App\Providers\AppServiceProvider

/**
 * Register any application services.
 */
public function register(): void
{
    if ($this->app->environment('local')) {
        $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        $this->app->register(TelescopeServiceProvider::class);
    }
}
```

```json
// composer.json
"extra": {
    "laravel": {
        "dont-discover": [
            "laravel/telescope"
        ]
    }
},
```

```php
# app/Console/Kernel.php
$schedule->command('telescope:prune')->daily();
```
