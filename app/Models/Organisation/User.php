<?php

namespace App\Models\Organisation;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Helpers\DataBase;
use App\Resources\Route\ShardCoordinatorResource;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    public const ROLE_USER = 'user';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_GUEST = 'guest';
    public const ROLES = [
        self::ROLE_USER,
        self::ROLE_ADMIN,
        self::ROLE_GUEST,
    ];

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'ref',
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->attributes['uuid'];
    }

    /**
     * The key name to use as the JWT's subject
     *
     * @return string
     */
    public function getAuthIdentifierName(): string
    {
        return 'uuid';
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     * @throws Exception
     */
    public function getJWTCustomClaims(): array
    {
        if (!$shard_coordinator = app(ShardCoordinatorResource::class)->getByDbname(DataBase::getDefault())) {
            throw new Exception('Can not resolve a hash by database name.');
        }

        return [
            ShardCoordinatorResource::FIELD_UUID => $shard_coordinator->{ShardCoordinatorResource::FIELD_UUID},
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }

    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function setRoleAttribute(string $value): void
    {
        $this->attributes['role'] = strtolower($value);
    }

    public function setStatusAttribute(string $value): void
    {
        $this->attributes['status'] = strtolower($value);
    }

    public function scopeOneOf(Builder $query, int|string $value): void
    {
        $query->where(function (Builder $query) use ($value) {
            $query->where('id', $value)
                ->orWhere('uuid', $value)
                ->orWhere('ref', $value);
        });
    }

}
