{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        @if($includeImages) xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" @endif>

    {{-- 1. Static Core Pages --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ $siteLastMod ? $siteLastMod->toIso8601String() : now()->toIso8601String() }}</lastmod>
        <changefreq>{{ $homeFreq ?? 'always' }}</changefreq>
        <priority>{{ $homePriority ?? '1.0' }}</priority>
    </url>

    <url>
        <loc>{{ route('lead-news.all') }}</loc>
        <lastmod>{{ $siteLastMod ? $siteLastMod->toIso8601String() : now()->toIso8601String() }}</lastmod>
        <changefreq>{{ $homeFreq ?? 'always' }}</changefreq>
        <priority>{{ number_format((float)($homePriority ?? 1.0) * 0.9, 1) }}</priority>
    </url>

    <url>
        <loc>{{ route('videos.all') }}</loc>
        <lastmod>{{ $siteLastMod ? $siteLastMod->toIso8601String() : now()->toIso8601String() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>

    <url>
        <loc>{{ route('market.rates') }}</loc>
        <lastmod>{{ now()->toIso8601String() }}</lastmod>
        <changefreq>hourly</changefreq>
        <priority>0.9</priority>
    </url>

    {{-- 2. Dynamic Categories --}}
    @if($includeCategories ?? true)
    @foreach($categories as $category)
    <url>
        <loc>{{ route('category.show', $category->slug) }}</loc>
        @php
            $catLatest = $category->articles->first();
            $catLastMod = $catLatest ? ($catLatest->updated_at ?? $catLatest->published_at) : $category->updated_at;
        @endphp
        <lastmod>{{ $catLastMod ? $catLastMod->toIso8601String() : now()->toIso8601String() }}</lastmod>
        <changefreq>{{ $categoriesFreq ?? 'daily' }}</changefreq>
        <priority>{{ $categoriesPriority ?? '0.8' }}</priority>
    </url>
    @endforeach
    @endif

    {{-- 3. Dynamic Published Articles --}}
    @if($includeArticles ?? true)
    @foreach($articles as $article)
    <url>
        <loc>{{ route('article.show', $article->slug) }}</loc>
        <lastmod>{{ ($article->updated_at ?? $article->published_at ?? now())->toIso8601String() }}</lastmod>
        <changefreq>{{ $articlesFreq ?? 'weekly' }}</changefreq>
        <priority>{{ $articlesPriority ?? '0.7' }}</priority>
        @if(($includeImages ?? false) && !empty($article->thumbnail_url))
        <image:image>
            <image:loc>{{ $article->thumbnail_url }}</image:loc>
            <image:title><![CDATA[{{ $article->title }}]]></image:title>
        </image:image>
        @endif
    </url>
    @endforeach
    @endif

    {{-- 4. Dynamic Tags --}}
    @if($includeTags ?? true)
    @foreach($tags as $tag)
    <url>
        <loc>{{ route('tag.show', $tag->slug) }}</loc>
        <lastmod>{{ ($tag->updated_at ?? now())->toIso8601String() }}</lastmod>
        <changefreq>{{ $tagsFreq ?? 'weekly' }}</changefreq>
        <priority>{{ $tagsPriority ?? '0.6' }}</priority>
    </url>
    @endforeach
    @endif

    {{-- 5. Custom SEO Pages --}}
    @if($includeCustomPages ?? true)
    @foreach($customSeoPages as $seoPage)
    <url>
        <loc>{{ url($seoPage->page_url) }}</loc>
        <lastmod>{{ ($seoPage->updated_at ?? now())->toIso8601String() }}</lastmod>
        <changefreq>{{ $customFreq ?? 'monthly' }}</changefreq>
        <priority>{{ $customPriority ?? '0.8' }}</priority>
    </url>
    @endforeach
    @endif

    {{-- 6. Custom Static Additional URLs --}}
    @if(!empty($customUrls))
    @foreach($customUrls as $custom)
    <url>
        <loc>{{ url($custom['url']) }}</loc>
        <lastmod>{{ \Carbon\Carbon::parse($custom['lastmod'] ?? now())->toIso8601String() }}</lastmod>
        <changefreq>{{ $custom['changefreq'] ?? 'monthly' }}</changefreq>
        <priority>{{ $custom['priority'] ?? '0.8' }}</priority>
    </url>
    @endforeach
    @endif

</urlset>
