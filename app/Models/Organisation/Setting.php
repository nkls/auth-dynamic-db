<?php

namespace App\Models\Organisation;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'param',
        'data',
    ];

    protected $casts = [
        'data' => 'object'
    ];
}
