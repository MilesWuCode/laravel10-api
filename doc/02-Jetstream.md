# 02-Jetstream

```sh
# 安裝laravel-jetstream
# 僅限新應用
# Jetstream 應該只安裝到新的 Laravel 應用程序中。
# 嘗試將 Jetstream 安裝到現有的 Laravel 應用程序中將導致意外行為和問題。
composer require laravel/jetstream

# 使用livewire
php artisan jetstream:install livewire

# 用於團隊,不使用
php artisan jetstream:install livewire --teams

#
php artisan migrate
```
