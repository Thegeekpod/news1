<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = [
        'title',
        'slot_name',
        'image_url',
        'destination_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
