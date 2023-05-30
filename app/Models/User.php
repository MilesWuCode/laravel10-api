<?php

namespace App\Models;

use App\Notifications\CustomResetPasswordNotification;
use App\Notifications\CustomVerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use HasApiTokens, HasFactory, Notifiable;
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * 驗證碼
     */
    public function verifies(): HasMany
    {
        return $this->hasMany(Verify::class);
    }

    /**
     * 寄信箱驗證信
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new CustomVerifyEmailNotification);
    }

    /**
     * 寄密碼重置信
     */
    public function sendPasswordResetNotify(): void
    {
        $verify = $this->verifies()->create();

        $this->notify(new CustomResetPasswordNotification($verify->code));
    }

    /**
     * 已驗證
     */
    public function scopeVerified(Builder $query): Builder
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * 檔案
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            // 沒有圖片回傳預設圖片網址
            ->useFallbackUrl(config('frontend.url').'/images/fallback.jpg')
            // 沒有圖片回傳預設圖片路徑
            ->useFallbackPath('/images/fallback.jpg')
            // 類型
            ->acceptsMimeTypes(['image/jpeg'])
            // 單一檔案
            ->singleFile();
    }

    /**
     * 圖片轉換,縮圖
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(120)
            ->height(120)
            ->performOnCollections('avatar');
    }
}
