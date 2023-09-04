<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Banner extends Model implements HasMedia, Sortable
{
    use HasFactory;
    use InteractsWithMedia;
    use SortableTrait;

    protected $fillable = [
        'name',
        'link',
        'order_column',
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
            // 單一檔案
            ->singleFile();
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

    // 排序設定
    public $sortable = [
        // 欄位名稱
        'order_column_name' => 'order_column',
        'sort_when_creating' => true,
    ];

    // 如果需要群組排序使用sortQuery()
    // public function buildSortQuery()
    // {
    //     return static::query()->where('user_id', $this->user_id);
    // }
}
