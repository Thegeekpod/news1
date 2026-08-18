<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name_bn',
        'name_en',
        'slug',
        'description',
        'display_order'
    ];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
