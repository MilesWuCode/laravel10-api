<?php

namespace App\Models;

use Cog\Contracts\Love\Reactable\Models\Reactable as ReactableInterface;
use Cog\Laravel\Love\Reactable\Models\Traits\Reactable;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;

class Post extends Model implements HasMedia, ReactableInterface
{
    use BroadcastsEvents;
    use HasFactory;
    use HasTags;
    use InteractsWithMedia;
    use Reactable;

    /**
     * fillable
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'user_id',
    ];

    /**
     * User
     */
    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

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
        // 套件提供RWD
        // ->withResponsiveImages();
        // 縮圖,需要Queue才會產生
        // ->registerMediaConversions(function (Media $media) {
        //     $this
        //         ->addMediaConversion('thumb')
        //         ->width(320)
        //         ->height(160);
        // });

    }

    /**
     * 縮圖,需要Queue才會產生
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(320)
            ->height(160)
            // ->nonQueued() // 不設queue會吃資源
            ->performOnCollections('cover');
    }

    /**
     * 廣播
     */
    public function broadcastOn(string $event): array
    {
        return [$this, $this->user];
    }
}
