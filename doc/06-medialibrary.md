# 06-medialibrary

```sh
composer require spatie/laravel-medialibrary

php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="migrations"

php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="config"

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

ubuntu

```sh
sudo apt install jpegoptim optipng pngquant gifsicle
```

macos

```sh
brew install jpegoptim
brew install optipng
brew install pngquant
brew install svgo
brew install gifsicle
```

## minio

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
-e MINIO_SERVER_URL="https://minio.miles-home.cc" \
--restart unless-stopped \
minio/minio:latest server /data \
--address ":9000" \
--console-address ":9001"

# 在config/filesystems.php使用s3參數

# tinker測試
Storage::disk('minio')->put('hello.json', '{"hello": "world"}');
Storage::disk('minio')->get('hello.json');
file_get_contents('https://minio.miles-home.cc/test/hello.json');
```

.env

```ini
; env參數
AWS_ENDPOINT=https://minio.miles-home.cc
AWS_USE_PATH_STYLE_ENDPOINT=true
MEDIA_DISK=minio
```
