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
    'root'   => public_path('media'),
    'url'    => env('APP_URL').'/media',
],
```

ubunto

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
docker run -d \
--name minio \
-v ${PWD}/data:/data \
-p 19000:9000 \
-p 19001:9001 \
-e MINIO_ROOT_USER=admin \
-e MINIO_ROOT_PASSWORD=password \
-e MINIO_BROWSER_REDIRECT_URL="https://minio-console.miles-home.cc" \
-e MINIO_SERVER_URL="https://minio.miles-home.cc" \
--restart unless-stopped \
minio/minio:latest server /data \
--address ":9000" \
--console-address ":9001"
```
