<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'title_bn',
        'youtube_url',
        'duration',
        'thumbnail_url'
    ];

    public function getThumbnailUrlAttribute($value)
    {
        if (!$value) {
            return null;
        }
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        return asset($value);
    }
}
