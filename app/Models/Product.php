<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'content',
        'price',
    ];

    /**
     * 檔案
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
            // getFirstMediaUrl,沒有圖片回傳預設圖片網址/路徑
            ->useFallbackUrl('/images/fallback.jpg')
            ->useFallbackUrl('/images/fallback.jpg', 'thumb')
            // getFirstMediaPath,沒有圖片回傳預設圖片路徑
            ->useFallbackPath(public_path('/images/fallback.jpg'))
            ->useFallbackPath(public_path('/images/fallback.jpg'), 'thumb')
            // 類型
            ->acceptsMimeTypes(['image/jpeg', 'image/png'])
            // 限制檔案
            ->onlyKeepLatest(6);
    }

    /**
     * 圖片轉換,縮圖
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(320)
            ->height(160)
            ->performOnCollections('cover');
    }
}
