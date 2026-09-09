<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::defaultView('vendor.pagination.custom');

        view()->composer(['layouts.app', 'home', 'category', 'article', 'coming-soon', 'search', 'videos', 'top-news'], function ($view) {
            $popular = \App\Models\Article::published()->where('is_popular', true)->orderBy('view_count', 'desc')->take(5)->get();
            if ($popular->isEmpty()) {
                $popular = \App\Models\Article::published()->orderBy('view_count', 'desc')->take(5)->get();
            }

            $latest = \App\Models\Article::published()->where('is_latest', true)->orderBy('created_at', 'desc')->take(5)->get();
            if ($latest->isEmpty()) {
                $latest = \App\Models\Article::published()->orderBy('created_at', 'desc')->take(5)->get();
            }

            // Always serve instantly from database — weather is refreshed via background AJAX
            $settings = \App\Models\Setting::all()->keyBy('key')->map(fn($item) => $item->value);

            $tickers = \App\Models\Ticker::where('is_active', true)->latest()->get()->map(function ($t) {
                return (object) [
                    'text_bn' => $t->text_bn,
                    'link_url' => $t->link_url ?? '#'
                ];
            });

            $breakingArticles = \App\Models\Article::published()
                ->where('is_breaking', true)
                ->latest('published_at')
                ->take(10)
                ->get()
                ->map(function ($a) {
                    return (object) [
                        'text_bn' => $a->title,
                        'link_url' => route('article.show', $a->slug)
                    ];
                });

            $combinedTickers = $tickers->concat($breakingArticles);

            if ($combinedTickers->count() < 10) {
                $needed = 10 - $combinedTickers->count();
                $existingUrls = $combinedTickers->pluck('link_url')->filter()->toArray();

                $latestFallback = \App\Models\Article::published()
                    ->latest('published_at')
                    ->get()
                    ->map(function ($a) {
                        return (object) [
                            'text_bn' => $a->title,
                            'link_url' => route('article.show', $a->slug)
                        ];
                    })
                    ->reject(fn($item) => in_array($item->link_url, $existingUrls))
                    ->take($needed);

                $combinedTickers = $combinedTickers->concat($latestFallback);
            }

            $combinedTickers = $combinedTickers->take(10);

            // Resolve SEO Settings for the current request path
            $currentPath = \App\Models\SeoSetting::normalizePath(request()->getPathInfo());
            $currentSeo = \App\Models\SeoSetting::getForPath($currentPath);

            $view->with([
                'g_categories' => \App\Models\Category::orderBy('display_order', 'asc')->get(),
                'g_tickers' => $combinedTickers,
                'g_popular' => $popular,
                'g_latest' => $latest,
                'g_settings' => $settings,
                'g_seo' => $currentSeo,
                'g_ads' => \App\Models\Advertisement::where('is_active', true)->get()->groupBy('slot_name'),
            ]);
        });
    }
}
