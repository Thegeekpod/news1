<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\SeoSetting;
use App\Models\Setting;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Check if a slug is a valid clean ASCII slug (no non-ASCII / percent-encoded characters)
     */
    private function isValidSlug(?string $slug): bool
    {
        return !empty($slug) && preg_match('/^[a-zA-Z0-9_\-]+$/', $slug) === 1;
    }

    /**
     * Generate the complete dynamic XML sitemap
     */
    public function index(): Response
    {
        $siteName = Setting::where('key', 'site_name')->value('value') ?? 'নিউজ১';
        
        // Latest published article for site lastmod
        $latestArticle = Article::published()->latest('published_at')->first();
        $siteLastMod = $latestArticle ? ($latestArticle->updated_at ?? $latestArticle->published_at) : now();

        // 1. Categories with their latest article date (clean ASCII slugs only)
        $categories = Category::with(['articles' => function ($query) {
            $query->published()->latest('published_at')->select('id', 'category_id', 'published_at', 'updated_at')->take(1);
        }])->get()->filter(fn ($cat) => $this->isValidSlug($cat->slug));

        // 2. Published Articles (clean ASCII slugs only, excludes Bengali %E0%A6%... slugs)
        $articles = Article::published()
            ->select('id', 'slug', 'title', 'thumbnail_url', 'published_at', 'updated_at', 'keywords')
            ->latest('published_at')
            ->get()
            ->filter(fn ($article) => $this->isValidSlug($article->slug));

        // 3. Tags (clean ASCII slugs only)
        $tags = Tag::withCount(['articles' => function ($q) {
            $q->published();
        }])->get()->filter(fn ($tag) => $this->isValidSlug($tag->slug));

        // 4. Custom SEO pages not covered by dynamic category/home routes
        $customSeoPages = SeoSetting::where('page_url', '!=', '/')
            ->where('page_url', 'not like', '/category/%')
            ->where('page_url', 'not like', '/article/%')
            ->where('page_url', 'not like', '/tag/%')
            ->get()
            ->filter(fn ($page) => !empty($page->page_url) && !preg_match('/[^\x20-\x7e]/', $page->page_url));

        $content = view('sitemap.index', compact(
            'siteName',
            'siteLastMod',
            'categories',
            'articles',
            'tags',
            'customSeoPages'
        ))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'no-transform, public, max-age=3600')
            ->header('X-Robots-Tag', 'noindex');
    }

    /**
     * Dynamic Google News XML Sitemap (last 48 hours or latest 1000 articles)
     */
    public function news(): Response
    {
        $siteName = Setting::where('key', 'site_name')->value('value') ?? 'নিউজ১';

        $articles = Article::published()
            ->where('published_at', '>=', now()->subHours(48))
            ->select('id', 'slug', 'title', 'thumbnail_url', 'published_at', 'updated_at', 'keywords')
            ->latest('published_at')
            ->take(1000)
            ->get()
            ->filter(fn ($article) => $this->isValidSlug($article->slug));

        // Fallback: If no articles published in last 48h (e.g. staging/demo), show latest 20 articles
        if ($articles->isEmpty()) {
            $articles = Article::published()
                ->select('id', 'slug', 'title', 'thumbnail_url', 'published_at', 'updated_at', 'keywords')
                ->latest('published_at')
                ->take(20)
                ->get()
                ->filter(fn ($article) => $this->isValidSlug($article->slug));
        }

        $content = view('sitemap.news', compact('siteName', 'articles'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'no-transform, public, max-age=3600')
            ->header('X-Robots-Tag', 'noindex');
    }

    /**
     * Articles-only XML Sitemap
     */
    public function articles(): Response
    {
        $articles = Article::published()
            ->select('id', 'slug', 'title', 'thumbnail_url', 'published_at', 'updated_at')
            ->latest('published_at')
            ->get()
            ->filter(fn ($article) => $this->isValidSlug($article->slug));

        $content = view('sitemap.articles', compact('articles'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'no-transform, public, max-age=3600')
            ->header('X-Robots-Tag', 'noindex');
    }

    /**
     * Categories-only XML Sitemap
     */
    public function categories(): Response
    {
        $categories = Category::with(['articles' => function ($query) {
            $query->published()->latest('published_at')->select('id', 'category_id', 'published_at', 'updated_at')->take(1);
        }])->get()->filter(fn ($cat) => $this->isValidSlug($cat->slug));

        $content = view('sitemap.categories', compact('categories'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'no-transform, public, max-age=3600')
            ->header('X-Robots-Tag', 'noindex');
    }

    /**
     * Tags-only XML Sitemap
     */
    public function tags(): Response
    {
        $tags = Tag::all()->filter(fn ($tag) => $this->isValidSlug($tag->slug));

        $content = view('sitemap.tags', compact('tags'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'no-transform, public, max-age=3600')
            ->header('X-Robots-Tag', 'noindex');
    }
}
