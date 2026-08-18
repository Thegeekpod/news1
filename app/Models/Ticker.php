<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticker extends Model
{
    protected $fillable = [
        'text_bn',
        'link_url',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
}
