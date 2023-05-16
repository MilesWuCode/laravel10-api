# 07-test

> 參考 https://ralphjsmit.com/pest-php-testing-laravel
>
> 測試用的 env 檔案 `.env.testing`

```sh
# 安裝pest
composer require pestphp/pest --dev --with-all-dependencies

# 安裝laravel專用pest-plugin-laravel
composer require pestphp/pest-plugin-laravel --dev

# pest init
./vendor/bin/pest --init

# 測試用env
# DB_CONNECTION=sqlite
cp .env .env.testing

# 實體檔案位置,建立sqlite資料庫檔案
touch database/database.sqlite

# migrate
php artisan migrate --env=testing

# Feature
php artisan make:test UserTest

# Unit
php artisan make:test UserTest --unit

# Feature + pest,擇一
php artisan make:test UserTest --pest
php artisan pest:test UserTest

# Unit + pest
php artisan pest:test UsersTest --unit

# 測試Feature
php artisan test --testsuite=Feature --stop-on-failure

# 單測
php artisan test --filter UserTest

# 多線
php artisan test --parallel
php artisan test --parallel --recreate-databases
```

.env.testing

```php
// 使用sqlite,預設在database/database.sqlite
DB_CONNECTION=sqlite

// 使用記憶體,用之前要先migrate
// 所以要修改tests/TestCase.php
DB_DATABASE=:memory:
```

tests/TestCase.php

> 如果想要在測試前 migrate
>
> 可以用 RefreshDatabase 或 DatabaseMigrations
>
> 或是用 Artisan 指令執行

```diff
+ use Illuminate\Foundation\Testing\DatabaseMigrations;
+ use Illuminate\Foundation\Testing\RefreshDatabase;
+ use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
+   use RefreshDatabase;

+   protected function setUp(): void
+   {
+       parent::setUp();

+       Artisan::call('migrate');
+   }
}
```
