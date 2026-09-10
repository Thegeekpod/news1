<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;

class CheckSiteLiveMode
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Allow unrestricted access to Admin routes, Auth, Coming Soon page, Newsletter and Assets
        $allowedPrefixes = [
            'admin',
            'login',
            'logout',
            'coming-soon',
            'newsletter',
            'weather',
            'sitemap*',
            'robots.txt',
        ];

        foreach ($allowedPrefixes as $prefix) {
            if ($request->is($prefix) || $request->is($prefix . '/*')) {
                return $next($request);
            }
        }

        // Also allow common named routes
        $allowedRouteNames = [
            'login',
            'login.submit',
            'logout',
            'coming-soon',
            'newsletter.subscribe',
            'weather.refresh',
            'sitemap.index',
            'sitemap.news',
            'sitemap.articles',
            'sitemap.categories',
            'sitemap.tags',
        ];

        if (in_array($request->route()?->getName(), $allowedRouteNames)) {
            return $next($request);
        }

        // 3. Check site_live_mode from settings (Default is '1' = Live)
        $liveMode = Setting::where('key', 'site_live_mode')->value('value') ?? '1';

        // If site is turned OFF (0), redirect non-admin visitors to Coming Soon page
        if ($liveMode === '0') {
            return redirect()->route('coming-soon');
        }

        return $next($request);
    }
}
