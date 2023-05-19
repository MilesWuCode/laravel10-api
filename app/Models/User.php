<?php

namespace App\Models;

use App\Notifications\CustomVerifyEmail;
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
     * 寄驗證信
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new CustomVerifyEmail);
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
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('avatar')
            ->singleFile()
            ->useFallbackUrl('/images/anonymous-user.jpg')
            ->useFallbackPath(public_path('/images/anonymous-user.jpg'));
    }
}
