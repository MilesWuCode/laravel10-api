# 06-medialibrary

```sh
# 安裝
composer require spatie/laravel-medialibrary

# 資料表腳本
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="migrations"

# 設定檔
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="config"

# 建立資料表
php artisan migrate
```

config/filesystems.php

```php
'media' => [
    'driver' => 'local',
    'root' => public_path('media'),
    'url' => env('APP_URL').'/media',
],
```

.env

```ini
; 設定medialibrary使用filesystem裡的那一個disk
MEDIA_DISK=local
```

擴充套件

```sh
# ubuntu
sudo apt install jpegoptim optipng pngquant gifsicle

# macos
brew install jpegoptim
brew install optipng
brew install pngquant
brew install svgo
brew install gifsicle
```

## minio 伺服器

```sh
# 專案安裝driver
composer require league/flysystem-aws-s3-v3

# 建立minio server
docker run -d \
--name minio \
-v ${PWD}/data:/data \
-p 19000:9000 \
-p 19001:9001 \
-e MINIO_ROOT_USER=admin \
-e MINIO_ROOT_PASSWORD=password \
-e MINIO_SERVER_URL="http://127.0.0.1:9000" \
--restart unless-stopped \
minio/minio:latest server /data \
--address ":9000" \
--console-address ":9001"

# 在config/filesystems.php使用s3參數

# tinker測試
Storage::disk('minio')->put('hello.json', '{"hello": "world"}');
Storage::disk('minio')->get('hello.json');
file_get_contents('http://127.0.0.1:9000/test/hello.json');
```

.env 修改

```ini
; env參數
AWS_ENDPOINT=http://127.0.0.1:9000
AWS_USE_PATH_STYLE_ENDPOINT=true
MEDIA_DISK=minio
```
