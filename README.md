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
```

## 工具

-   http://localhost:8025
-   http://localhost/telescope

##

```sh
sail php artisan migrate
sail php artisan love:reaction-type-add --default
sail php artisan love:reaction-type-add --name favorite --mass 1
```
