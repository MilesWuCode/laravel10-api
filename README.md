# laravel10-demo

## 服務

-   資料庫 mysql
-   快取 redis
-   檔案 minio
-   寄信 mailpit
-   廣播 soketi
-   加速 swoole (單機開發使用 sail)

## 初始化

```sh
# 安裝composer套件
composer install

# 容器啓動
sail up -d

# 環境變數
cp .env.example .env
sail php artisan k:g

# 填入第三方登入
*_CLIENT_ID=...
*_CLIENT_SECRET=...

# minio新增bucket/Access Policy更改public
AWS_*=...

# 資料庫遷移
sail php artisan migrate

# 填加like,dislike
sail php artisan love:reaction-type-add --default

# 填加favorite
sail php artisan love:reaction-type-add --name favorite --mass 1

# 執行排程
sail php artisan horizon

# 關閉
sail down
```

## 常用指令

```sh
# 一般執行
sail php artisan serve

# octane執行
sail php artisan octane:start

# 排程:使用laravel腳本
sail php artisan queue:work

# 排程:使用horizon套件
sail php artisan horizon

# 重新build全部容器
sail build --no-cache

# ide-help
sail php artisan ide-helper:generate
sail php artisan ide-helper:models --nowrite

# PhpStorm
sail php artisan ide-helper:meta

# lighthouse
sail php artisan vendor:publish --tag=lighthouse-schema

# telescope在sail時會抓不到mysql,需要執行這句
sail php artisan package:discover --ansi

# 更新時出現Failed to download
sail composer clear-cache
sail composer update
```

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

## minio

-   建立`laravel`的 buckets 正式用
-   建立`laravel-test`的 buckets 測試用
-   buckets policy 設為 `public`
