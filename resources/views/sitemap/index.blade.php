{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- 1. Static Core Pages --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ $siteLastMod ? $siteLastMod->toIso8601String() : now()->toIso8601String() }}</lastmod>
        <changefreq>always</changefreq>
        <priority>1.0</priority>
    </url>

    <url>
        <loc>{{ route('lead-news.all') }}</loc>
        <lastmod>{{ $siteLastMod ? $siteLastMod->toIso8601String() : now()->toIso8601String() }}</lastmod>
        <changefreq>hourly</changefreq>
        <priority>0.9</priority>
    </url>

    <url>
        <loc>{{ route('videos.all') }}</loc>
        <lastmod>{{ $siteLastMod ? $siteLastMod->toIso8601String() : now()->toIso8601String() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>

    {{-- 2. Dynamic Categories --}}
    @foreach($categories as $category)
    <url>
        <loc>{{ route('category.show', $category->slug) }}</loc>
        @php
            $catLatest = $category->articles->first();
            $catLastMod = $catLatest ? ($catLatest->updated_at ?? $catLatest->published_at) : $category->updated_at;
        @endphp
        <lastmod>{{ $catLastMod ? $catLastMod->toIso8601String() : now()->toIso8601String() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    {{-- 3. Dynamic Published Articles (Blog Posts) --}}
    @foreach($articles as $article)
    <url>
        <loc>{{ route('article.show', $article->slug) }}</loc>
        <lastmod>{{ ($article->updated_at ?? $article->published_at ?? now())->toIso8601String() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    {{-- 4. Dynamic Tags --}}
    @foreach($tags as $tag)
    <url>
        <loc>{{ route('tag.show', $tag->slug) }}</loc>
        <lastmod>{{ ($tag->updated_at ?? now())->toIso8601String() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach

    {{-- 5. Custom SEO Pages --}}
    @foreach($customSeoPages as $seoPage)
    <url>
        <loc>{{ url($seoPage->page_url) }}</loc>
        <lastmod>{{ ($seoPage->updated_at ?? now())->toIso8601String() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

</urlset>
