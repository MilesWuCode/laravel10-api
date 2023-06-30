# laravel10-demo

## 需求

-   mysql
-   redis
-   minio
-   mailpit
-   soketi
-   swoole

## 執行

```sh
php artisan octane:start
php artisan serve
php artisan queue:work

sail build --no-cache
sail up -d
sail down
```

## 工具

-   http://localhost
-   http://localhost:8025
-   http://localhost/telescope

##

```sh
sail php artisan migrate

# 填加like,dislike
sail php artisan love:reaction-type-add --default

# 填加favorite
sail php artisan love:reaction-type-add --name favorite --mass 1

sail php artisan o:c

# telescope在sail時會抓不到mysql,需要執行這句
sail php artisan package:discover --ansi

# composer update出現Failed to download
sail composer clear-cache
sail composer update
```
