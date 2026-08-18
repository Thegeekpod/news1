<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'thumbnail_url',
        'category_id',
        'user_id',
        'status',
        'view_count',
        'is_breaking',
        'is_lead',
        'is_sub_lead',
        'is_popular',
        'is_latest',
        'is_special_banner',
        'published_at',
        'meta_title',
        'meta_description',
        'keywords'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_breaking' => 'boolean',
        'is_lead' => 'boolean',
        'is_sub_lead' => 'boolean',
        'is_popular' => 'boolean',
        'is_latest' => 'boolean',
        'is_special_banner' => 'boolean',
        'view_count' => 'integer'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

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

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function getDisplayViewCountAttribute()
    {
        $offset = intval(\App\Models\Setting::where('key', 'view_count_offset')->value('value') ?? 200);
        return $this->view_count + $offset;
    }
}
