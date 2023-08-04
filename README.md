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
sail php artisan octane:start
sail php artisan serve

# 排程(使用其中一個)
sail php artisan queue:work
sail php artisan horizon

sail build --no-cache

sail up -d
sail down

# ide-help
sail php artisan ide-helper:generate
sail php artisan ide-helper:models --nowrite
sail php artisan vendor:publish --tag=lighthouse-schema
```

## minio

-   建立`laravel`的 buckets 正式用
-   建立`laravel-test`的 buckets 測試用
-   buckets policy 設為 `public`

## 工具

-   api - http://localhost/api
-   mailpit - http://localhost:8025
-   telescope - http://localhost/telescope
-   horizon - http://localhost/horizon
-   minio - http://localhost:8900
-   graphiql - http://localhost/graphiql
-   graphql - http://localhost/graphql
-   mysql - mysql:3306
-   redis - redis:6379
-   smtp - mailpit:1025
-   minio - minio:9000

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
