<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Verify extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'expires',
    ];

    protected static function booted()
    {
        static::retrieved(function () {
            Verify::where('expires', '<=', now())->delete();
        });

        static::creating(function ($verify) {
            $verify->code = rand(111111, 999999);
            $verify->expires = Carbon::now()->addMinutes(60);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
