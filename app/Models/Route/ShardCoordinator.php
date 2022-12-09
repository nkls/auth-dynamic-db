<?php

namespace App\Models\Route;

use Illuminate\Support\Str;

class ShardCoordinator extends Model
{

    protected $fillable = [
        'name',
        'subdomain',
        'dbname',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();;
            }
        });
    }

}
