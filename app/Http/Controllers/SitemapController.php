<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\SeoSetting;
use App\Models\Setting;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Clear the sitemap cache
     */
    public static function clearCache(): void
    {
        Cache::forget('sitemap_xml_index');
    }

    /**
     * Check if a slug is valid based on settings
     */
    private function isValidSlug(?string $slug): bool
    {
        if (empty($slug)) {
            return false;
        }

        $asciiOnly = Setting::get('sitemap_ascii_slugs_only', '1') === '1';

        if ($asciiOnly) {
            return preg_match('/^[a-zA-Z0-9_\-]+$/', $slug) === 1;
        }

        return true;
    }

    /**
     * Parse comma or newline separated exclusions
     */
    private function parseExclusionList(?string $raw): array
    {
        if (empty($raw)) {
            return [];
        }

        $items = preg_split('/[\r\n,]+/', $raw);
        return array_values(array_filter(array_map('trim', $items)));
    }

    /**
     * Generate the single unified dynamic XML sitemap
     */
    public function index(): Response
    {
        if (Setting::get('sitemap_enabled', '1') !== '1') {
            return response('<?xml version="1.0" encoding="UTF-8"?><error>Sitemap is currently disabled.</error>', 404)
                ->header('Content-Type', 'application/xml; charset=utf-8');
        }

        Cache::forget('sitemap_xml_index');

        $content = trim($this->buildIndexXml());

        return response($content, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    /**
     * Build the complete unified sitemap XML string
     */
    private function buildIndexXml(): string
    {
        $siteName = Setting::get('site_name', 'নিউজ১');

        // Priorities & Frequencies
        $homePriority = Setting::get('sitemap_home_priority', '1.0');
        $homeFreq = Setting::get('sitemap_home_freq', 'always');
        $articlesPriority = Setting::get('sitemap_articles_priority', '0.7');
        $articlesFreq = Setting::get('sitemap_articles_freq', 'weekly');
        $categoriesPriority = Setting::get('sitemap_categories_priority', '0.8');
        $categoriesFreq = Setting::get('sitemap_categories_freq', 'daily');
        $tagsPriority = Setting::get('sitemap_tags_priority', '0.6');
        $tagsFreq = Setting::get('sitemap_tags_freq', 'weekly');
        $customPriority = Setting::get('sitemap_custom_priority', '0.8');
        $customFreq = Setting::get('sitemap_custom_freq', 'monthly');

        // Content Inclusion Flags
        $includeArticles = Setting::get('sitemap_articles_enabled', '1') === '1';
        $includeCategories = Setting::get('sitemap_categories_enabled', '1') === '1';
        $includeTags = Setting::get('sitemap_tags_enabled', '1') === '1';
        $includeCustomPages = Setting::get('sitemap_custom_pages_enabled', '1') === '1';
        $includeImages = false;

        // Exclusions
        $excludedCatIds = json_decode(Setting::get('sitemap_excluded_categories', '[]'), true) ?: [];
        $excludedArticlesRaw = Setting::get('sitemap_excluded_articles', '');
        $excludedArticles = $this->parseExclusionList($excludedArticlesRaw);
        $excludedUrlsRaw = Setting::get('sitemap_excluded_urls', '/coming-soon');
        $excludedUrls = $this->parseExclusionList($excludedUrlsRaw);
        $excludeEmptyCategories = Setting::get('sitemap_exclude_empty_categories', '0') === '1';
        $excludeEmptyTags = Setting::get('sitemap_exclude_empty_tags', '0') === '1';

        // Custom Static URLs
        $customUrls = json_decode(Setting::get('sitemap_custom_urls', '[]'), true) ?: [];

        // Latest published article for site lastmod
        $latestArticle = Article::published()->latest('published_at')->first();
        $siteLastMod = $latestArticle ? ($latestArticle->updated_at ?? $latestArticle->published_at) : now();

        // 1. Categories
        $categories = collect();
        if ($includeCategories) {
            $categoriesQuery = Category::with(['articles' => function ($query) {
                $query->published()->latest('published_at')->select('id', 'category_id', 'published_at', 'updated_at')->take(1);
            }]);

            if (!empty($excludedCatIds)) {
                $categoriesQuery->whereNotIn('id', $excludedCatIds);
            }

            if ($excludeEmptyCategories) {
                $categoriesQuery->has('articles');
            }

            $categories = $categoriesQuery->get()->filter(fn ($cat) => $this->isValidSlug($cat->slug));
        }

        // 2. Published Articles
        $articles = collect();
        if ($includeArticles) {
            $articlesQuery = Article::published()
                ->select('id', 'slug', 'title', 'thumbnail_url', 'published_at', 'updated_at', 'keywords')
                ->latest('published_at');

            if (!empty($excludedArticles)) {
                $articlesQuery->whereNotIn('slug', $excludedArticles)
                              ->whereNotIn('id', array_filter($excludedArticles, 'is_numeric'));
            }

            $articles = $articlesQuery->get()->filter(fn ($article) => $this->isValidSlug($article->slug));
        }

        // 3. Tags
        $tags = collect();
        if ($includeTags) {
            $tagsQuery = Tag::withCount(['articles' => function ($q) {
                $q->published();
            }]);

            if ($excludeEmptyTags) {
                $tagsQuery->has('articles');
            }

            $tags = $tagsQuery->get()->filter(fn ($tag) => $this->isValidSlug($tag->slug));
        }

        // 4. Custom SEO pages not covered by dynamic category/home routes
        $customSeoPages = collect();
        if ($includeCustomPages) {
            $customSeoPages = SeoSetting::where('page_url', '!=', '/')
                ->where('page_url', 'not like', '/category/%')
                ->where('page_url', 'not like', '/article/%')
                ->where('page_url', 'not like', '/tag/%')
                ->get()
                ->filter(function ($page) use ($excludedUrls) {
                    if (empty($page->page_url) || preg_match('/[^\x20-\x7e]/', $page->page_url)) {
                        return false;
                    }
                    foreach ($excludedUrls as $exUrl) {
                        if ($page->page_url === $exUrl || str_starts_with($page->page_url, rtrim($exUrl, '/'))) {
                            return false;
                        }
                    }
                    return true;
                });
        }

        return view('sitemap.index', compact(
            'siteName',
            'siteLastMod',
            'categories',
            'articles',
            'tags',
            'customSeoPages',
            'customUrls',
            'homePriority',
            'homeFreq',
            'categoriesPriority',
            'categoriesFreq',
            'articlesPriority',
            'articlesFreq',
            'tagsPriority',
            'tagsFreq',
            'customPriority',
            'customFreq',
            'includeImages',
            'includeArticles',
            'includeCategories',
            'includeTags',
            'includeCustomPages'
        ))->render();
    }
}
