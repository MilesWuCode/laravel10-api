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

class Post extends Model implements HasMedia, ReactableInterface
{
    use HasFactory;
    use InteractsWithMedia;
    use BroadcastsEvents;
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
            // 沒有圖片回傳預設圖片網址
            ->useFallbackUrl(config('frontend.url').'/images/fallback.jpg')
            // 沒有圖片回傳預設圖片路徑
            ->useFallbackPath('/images/fallback.jpg')
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
            ->width(200)
            ->height(400)
            ->performOnCollections('cover');
    }

    public function broadcastOn(string $event): array
    {
        return [$this, $this->user];
    }

    // public function reactTo($user, $type)
    // {
    //     $reacterFacade = $user->viaLoveReacter();

    //     $reacterFacade->reactTo($this, $type);
    // }

    // public function unreactTo($user, $type)
    // {
    //     $reacterFacade = $user->viaLoveReacter();

    //     $reacterFacade->unreactTo($this, $type);
    // }

    /**
     * Attribute: like_count: number
     */
    public function getLikeCountAttribute(): int
    {
        // list n+1: ->with(['tags', 'loveReactant.reactionCounters', 'loveReactant.reactionTotal'])
        return $this->viaLoveReactant()
            ->getReactionCounterOfType('Like')
            ->getCount();
    }

    /**
     * Attribute: dislike_count: number
     */
    public function getDislikeCountAttribute(): int
    {
        // list n+1: ->with(['tags', 'loveReactant.reactionCounters', 'loveReactant.reactionTotal'])
        return $this->viaLoveReactant()
            ->getReactionCounterOfType('Dislike')
            ->getCount();
    }

    /**
     * Attribute: like: 'Like' or 'Dislike'
     */
    public function getLikeAttribute(): string
    {
        // list n+1: ->with(['loveReactant.reactions'])
        if (auth()->check() && $this->viaLoveReactant()->isReactedBy(auth()->user(), 'Like')) {
            return 'Like';
        } elseif (auth()->check() && $this->viaLoveReactant()->isReactedBy(auth()->user(), 'Dislike')) {
            return 'Dislike';
        }

        return '';
    }
}
