# laravel10-api

-   WEB https://github.com/MilesWuCode/nuxt3-demo
-   CMS https://github.com/MilesWuCode/laravel10-filament3
-   API https://github.com/MilesWuCode/laravel10-api

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

# 環境變數
cp .env.example .env

# env填入第三方登入
*_CLIENT_ID=...
*_CLIENT_SECRET=...

# shell加入sail
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'

# 容器啓動
sail up -d

# minio新增bucket/Access Policy更改public
AWS_*=...

# appkey
sail php artisan k:g

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
php artisan serve

# octane執行
php artisan octane:start

# 排程:使用laravel腳本
php artisan queue:work

# 排程:使用horizon套件
php artisan horizon

# 重新build全部容器
build --no-cache

# ide-help
php artisan ide-helper:generate
php artisan ide-helper:models --nowrite

# PhpStorm
php artisan ide-helper:meta

# lighthouse
php artisan vendor:publish --tag=lighthouse-schema

# telescope在sail時會抓不到mysql,需要執行這句
php artisan package:discover --ansi

# 更新時出現Failed to download
composer clear-cache
composer update

# 快速建好常用檔案
# ProductModel
php artisan make:model
# ProductResource, ProductCollection
php artisan make:resource
# ProductRepository, ProductService

# Ubuntu使用sail
sudo chmod -R o+w storage
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
