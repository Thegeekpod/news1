<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Video;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Secondary hero stack articles (top 4 stories)
        $topStories = Article::published()
            ->where(function ($q) {
                $q->where('is_lead', true)->orWhere('is_sub_lead', true);
            })
            ->latest('published_at')
            ->with(['category', 'author'])
            ->take(4)
            ->get();

        if ($topStories->count() < 4) {
            $existingIds = $topStories->pluck('id')->toArray();
            $fill = Article::published()
                ->whereNotIn('id', $existingIds)
                ->latest('published_at')
                ->with(['category', 'author'])
                ->take(4 - $topStories->count())
                ->get();
            $topStories = $topStories->concat($fill);
        }

        // State & National News articles (3 articles)
        $stateArticles = Article::published()
            ->whereHas('category', function ($q) {
                $q->whereIn('slug', ['state', 'politics', 'country', 'national', 'kolkata']);
            })
            ->latest('published_at')
            ->with(['category', 'author'])
            ->take(4)
            ->get();

        if ($stateArticles->isEmpty()) {
            $stateArticles = Article::published()->latest('published_at')->with(['category', 'author'])->take(4)->get();
        }

        // Sports articles (2 articles)
        $sportsArticles = Article::published()
            ->whereHas('category', function ($q) {
                $q->whereIn('slug', ['sports', 'cricket', 'football']);
            })
            ->latest('published_at')
            ->with(['category', 'author'])
            ->take(2)
            ->get();

        if ($sportsArticles->isEmpty()) {
            $sportsArticles = Article::published()->latest('published_at')->with(['category', 'author'])->skip(2)->take(2)->get();
        }

        // International / World articles (2 articles)
        $internationalArticles = Article::published()
            ->whereHas('category', function ($q) {
                $q->whereIn('slug', ['international', 'world']);
            })
            ->latest('published_at')
            ->with(['category', 'author'])
            ->take(2)
            ->get();

        if ($internationalArticles->isEmpty()) {
            $internationalArticles = Article::published()->latest('published_at')->with(['category', 'author'])->skip(4)->take(2)->get();
        }

        // Entertainment articles (3 articles)
        $entertainmentArticles = Article::published()
            ->whereHas('category', function ($q) {
                $q->whereIn('slug', ['entertainment', 'cinema', 'tollywood', 'bollywood']);
            })
            ->latest('published_at')
            ->with(['category', 'author'])
            ->take(3)
            ->get();

        if ($entertainmentArticles->isEmpty()) {
            $entertainmentArticles = Article::published()->latest('published_at')->with(['category', 'author'])->skip(6)->take(3)->get();
        }

        // Tech & Lifestyle articles (2 articles)
        $techArticles = Article::published()
            ->whereHas('category', function ($q) {
                $q->whereIn('slug', ['technology', 'tech', 'lifestyle']);
            })
            ->latest('published_at')
            ->with(['category', 'author'])
            ->take(2)
            ->get();

        if ($techArticles->isEmpty()) {
            $techArticles = Article::published()->latest('published_at')->with(['category', 'author'])->skip(8)->take(2)->get();
        }

        // Videos
        $videos = Video::latest()->take(3)->get();

        // Special banner article
        $specialArticle = Article::published()
            ->where('is_special_banner', true)
            ->latest('published_at')
            ->first();

        return view('home', compact(
            'topStories',
            'stateArticles',
            'sportsArticles',
            'internationalArticles',
            'entertainmentArticles',
            'techArticles',
            'videos',
            'specialArticle'
        ));
    }

    public function videos()
    {
        $videos = Video::latest()->paginate(12);
        return view('videos', compact('videos'));
    }

    public function topStories()
    {
        $articles = Article::published()
            ->where(function ($query) {
                $query->where('is_lead', true)
                      ->orWhere('is_sub_lead', true);
            })
            ->latest('published_at')
            ->with(['category', 'author'])
            ->paginate(10);
        return view('top-news', compact('articles'));
    }

    public function refreshWeather()
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'key');

        if (($settings['weather_auto_fetch'] ?? '0') === '1' && !empty($settings['weather_api_key'])) {
            if (!\Illuminate\Support\Facades\Cache::has('weather_last_fetched')) {
                \App\Http\Controllers\Admin\SettingsController::fetchWeather(
                    $settings['weather_api_key'],
                    $settings['weather_location'] ?? 'Durgapur'
                );
                $settings = \App\Models\Setting::all()->pluck('value', 'key');
            }
        }

        return response()->json([
            'temp'     => $settings['weather_temp'] ?? '--',
            'desc'     => $settings['weather_desc'] ?? '',
            'humidity' => $settings['weather_humidity'] ?? '--',
            'wind'     => $settings['weather_wind'] ?? '--',
            'high'     => $settings['weather_high'] ?? '--',
            'low'      => $settings['weather_low'] ?? '--',
            'location' => $settings['weather_location'] ?? '',
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $articles = Article::published()
            ->when($query, function ($q, $query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('title', 'like', "%{$query}%")
                        ->orWhere('content', 'like', "%{$query}%");
                });
            })
            ->latest('published_at')
            ->with(['category', 'author'])
            ->paginate(10);

        return view('search', compact('articles', 'query'));
    }
}
