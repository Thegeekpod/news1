<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SitemapController as PublicSitemapController;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\SeoSetting;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SitemapController extends Controller
{
    /**
     * Default sitemap configuration settings for the single unified sitemap
     */
    public static function getDefaultSettings(): array
    {
        return [
            'sitemap_enabled' => '1',
            'sitemap_articles_enabled' => '1',
            'sitemap_categories_enabled' => '1',
            'sitemap_tags_enabled' => '1',
            'sitemap_custom_pages_enabled' => '1',
            'sitemap_include_images' => '0',
            'sitemap_ascii_slugs_only' => '1',
            'sitemap_exclude_empty_categories' => '0',
            'sitemap_exclude_empty_tags' => '0',
            'sitemap_cache_duration' => '60',

            // Priorities & Frequencies
            'sitemap_home_priority' => '1.0',
            'sitemap_home_freq' => 'always',
            'sitemap_articles_priority' => '0.7',
            'sitemap_articles_freq' => 'weekly',
            'sitemap_categories_priority' => '0.8',
            'sitemap_categories_freq' => 'daily',
            'sitemap_tags_priority' => '0.6',
            'sitemap_tags_freq' => 'weekly',
            'sitemap_custom_priority' => '0.8',
            'sitemap_custom_freq' => 'monthly',

            // Exclusions
            'sitemap_excluded_categories' => '[]',
            'sitemap_excluded_articles' => '',
            'sitemap_excluded_urls' => '/coming-soon',

            // Custom URLs
            'sitemap_custom_urls' => '[]',
        ];
    }

    /**
     * Display the Single Sitemap Control Panel
     */
    public function index()
    {
        $defaults = self::getDefaultSettings();
        $settings = [];

        foreach ($defaults as $key => $default) {
            $settings[$key] = Setting::get($key, $default);
        }

        // Decode JSON fields
        $excludedCategories = json_decode($settings['sitemap_excluded_categories'] ?? '[]', true) ?: [];
        $customUrls = json_decode($settings['sitemap_custom_urls'] ?? '[]', true) ?: [];

        // All categories with article counts for exclusion selection
        $categories = Category::withCount(['articles' => function ($q) {
            $q->published();
        }])->orderBy('name_en')->get();

        // Statistics
        $articlesCount = Article::published()->count();
        $articlesWithImageCount = Article::published()->whereNotNull('thumbnail_url')->count();
        $categoriesCount = Category::count();
        $tagsCount = Tag::count();
        $seoCount = SeoSetting::count();

        // Robots.txt status
        $robotsPath = public_path('robots.txt');
        $robotsExists = File::exists($robotsPath);
        $robotsContent = $robotsExists ? File::get($robotsPath) : '';
        $robotsWritable = File::isWritable($robotsPath) || (! $robotsExists && File::isWritable(public_path()));
        $robotsHasMainSitemap = str_contains($robotsContent, 'sitemap-1.xml') || str_contains($robotsContent, 'sitemap.xml');

        return view('admin.sitemap.index', compact(
            'settings',
            'excludedCategories',
            'customUrls',
            'categories',
            'articlesCount',
            'articlesWithImageCount',
            'categoriesCount',
            'tagsCount',
            'seoCount',
            'robotsContent',
            'robotsExists',
            'robotsWritable',
            'robotsHasMainSitemap'
        ));
    }

    /**
     * Update Sitemap Settings
     */
    public function update(Request $request)
    {
        $defaults = self::getDefaultSettings();

        // Checkbox boolean fields (if not in request, set to '0')
        $booleanKeys = [
            'sitemap_enabled',
            'sitemap_articles_enabled',
            'sitemap_categories_enabled',
            'sitemap_tags_enabled',
            'sitemap_custom_pages_enabled',
            'sitemap_include_images',
            'sitemap_ascii_slugs_only',
            'sitemap_exclude_empty_categories',
            'sitemap_exclude_empty_tags',
        ];

        foreach ($booleanKeys as $key) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $request->has($key) ? '1' : '0']
            );
        }

        // Standard string & numeric inputs
        $standardKeys = [
            'sitemap_cache_duration',
            'sitemap_home_priority',
            'sitemap_home_freq',
            'sitemap_articles_priority',
            'sitemap_articles_freq',
            'sitemap_categories_priority',
            'sitemap_categories_freq',
            'sitemap_tags_priority',
            'sitemap_tags_freq',
            'sitemap_custom_priority',
            'sitemap_custom_freq',
            'sitemap_excluded_articles',
            'sitemap_excluded_urls',
        ];

        foreach ($standardKeys as $key) {
            if ($request->has($key)) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => (string) $request->input($key, $defaults[$key] ?? '')]
                );
            }
        }

        // Excluded Categories (array)
        $excludedCats = $request->input('sitemap_excluded_categories', []);
        if (is_array($excludedCats)) {
            $excludedCats = array_map('intval', $excludedCats);
            Setting::updateOrCreate(
                ['key' => 'sitemap_excluded_categories'],
                ['value' => json_encode(array_values($excludedCats))]
            );
        }

        // Custom URLs
        if ($request->has('custom_urls')) {
            $rawCustomUrls = $request->input('custom_urls');
            $customUrlsList = [];

            if (is_array($rawCustomUrls)) {
                foreach ($rawCustomUrls as $item) {
                    if (!empty($item['url'])) {
                        $customUrlsList[] = [
                            'url' => trim($item['url']),
                            'priority' => $item['priority'] ?? '0.8',
                            'changefreq' => $item['changefreq'] ?? 'monthly',
                            'lastmod' => $item['lastmod'] ?? now()->toDateString(),
                        ];
                    }
                }
            }

            Setting::updateOrCreate(
                ['key' => 'sitemap_custom_urls'],
                ['value' => json_encode($customUrlsList)]
            );
        }

        // If robots_content is provided, update robots.txt as well
        if ($request->has('robots_content')) {
            File::put(public_path('robots.txt'), $request->input('robots_content', ''));
        }

        // Clear public sitemap cache
        PublicSitemapController::clearCache();

        return redirect()->route('admin.sitemap.index')->with('success', 'Sitemap configuration successfully updated and cache refreshed!');
    }

    /**
     * Clear sitemap cache
     */
    public function clearCache()
    {
        PublicSitemapController::clearCache();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Sitemap cache cleared successfully!'
            ]);
        }

        return redirect()->route('admin.sitemap.index')->with('success', 'Sitemap cache cleared successfully!');
    }

    /**
     * Save Robots.txt content directly
     */
    public function updateRobots(Request $request)
    {
        $request->validate([
            'robots_content' => 'nullable|string',
        ]);

        $content = $request->input('robots_content', '');
        $robotsPath = public_path('robots.txt');

        try {
            File::put($robotsPath, $content);
            return redirect()->route('admin.sitemap.index')->with('success', 'robots.txt file successfully updated!');
        } catch (\Throwable $e) {
            return redirect()->route('admin.sitemap.index')->with('error', 'Failed to update robots.txt: ' . $e->getMessage());
        }
    }

    /**
     * Automatically ensure single sitemap directive in robots.txt
     */
    public function syncRobots()
    {
        $robotsPath = public_path('robots.txt');
        $content = File::exists($robotsPath) ? File::get($robotsPath) : "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /login\nDisallow: /logout\n";

        $mainSitemapUrl = route('sitemap.index');
        $mainLine = "Sitemap: {$mainSitemapUrl}";

        // Remove any outdated sitemap-news or sub-sitemaps
        $lines = explode("\n", $content);
        $cleanLines = [];
        $hasMain = false;

        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (str_starts_with(strtolower($trimmed), 'sitemap:')) {
                if (str_contains($trimmed, 'sitemap-1.xml') || str_contains($trimmed, 'sitemap.xml')) {
                    $cleanLines[] = $mainLine;
                    $hasMain = true;
                }
                // Skip other multiple sitemaps
            } else {
                $cleanLines[] = $line;
            }
        }

        if (!$hasMain) {
            $cleanLines[] = $mainLine;
        }

        $content = rtrim(implode("\n", $cleanLines)) . "\n";
        File::put($robotsPath, $content);

        return redirect()->route('admin.sitemap.index')->with('success', 'robots.txt successfully synchronized with single sitemap!');
    }

    /**
     * Test and validate the single sitemap endpoint
     */
    public function testEndpoint()
    {
        $sitemapController = app(PublicSitemapController::class);
        $startTime = microtime(true);

        try {
            $response = $sitemapController->index();
            $endpointUrl = route('sitemap.index');

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            $statusCode = $response->getStatusCode();
            $contentType = $response->headers->get('Content-Type');
            $xmlContent = $response->getContent();

            // Validate XML parsing
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($xmlContent);
            $xmlErrors = libxml_get_errors();
            libxml_clear_errors();

            $isValidXml = ($xml !== false && empty($xmlErrors));
            $urlCount = 0;

            if ($xml !== false) {
                $urlCount = count($xml->url ?? []);
            }

            $sizeInBytes = strlen($xmlContent);
            $sizeFormatted = $sizeInBytes > 1048576 
                ? round($sizeInBytes / 1048576, 2) . ' MB' 
                : round($sizeInBytes / 1024, 2) . ' KB';

            return response()->json([
                'success' => true,
                'endpoint' => $endpointUrl,
                'status_code' => $statusCode,
                'content_type' => $contentType,
                'is_valid_xml' => $isValidXml,
                'url_count' => $urlCount,
                'size' => $sizeFormatted,
                'execution_time_ms' => $executionTime,
                'xml_errors' => array_map(fn($err) => trim($err->message), $xmlErrors),
                'preview_snippet' => mb_substr($xmlContent, 0, 900),
            ]);

        } catch (\Throwable $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'execution_time_ms' => $executionTime,
            ], 500);
        }
    }
}
