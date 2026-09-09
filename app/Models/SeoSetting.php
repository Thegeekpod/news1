<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    use HasFactory;

    protected $table = 'seo_settings';

    protected $fillable = [
        'page_url',
        'meta_title',
        'meta_description',
        'other_tags',
    ];

    /**
     * Normalize URL path to ensure consistent matching (e.g. leading slash, trimmed)
     */
    public static function normalizePath(?string $path): string
    {
        if (empty($path) || $path === '/') {
            return '/';
        }

        $trimmed = '/' . trim(parse_url($path, PHP_URL_PATH), '/');
        return $trimmed;
    }

    /**
     * Get SEO record for a given path or return null
     */
    public static function getForPath(?string $path): ?self
    {
        $normalized = self::normalizePath($path);
        return self::where('page_url', $normalized)->first();
    }
}
