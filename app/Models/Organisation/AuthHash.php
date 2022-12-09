<?php

namespace App\Models\Organisation;

use App\Resources\Organisation\Settings\GuestSettings;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuthHash extends Model
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'user_id',
        'expires',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeExpires(Builder $query): void
    {
        $query->where('expires', '>', Carbon::now());
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeRef(Builder $query, string $ref): void
    {
        $query->whereHas('user', function (Builder $query) use ($ref) {
            $query->where('ref', $ref);
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->hash)) {
                $model->hash = bin2hex(random_bytes(20));
            }

            if (empty($model->status)) {
                $model->status = static::STATUS_ACTIVE;
            }
        });
    }
}
