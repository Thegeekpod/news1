{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">

    @foreach($articles as $article)
    <url>
        <loc>{{ route('article.show', $article->slug) }}</loc>
        <lastmod>{{ ($article->updated_at ?? $article->published_at ?? now())->toIso8601String() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
        @if(!empty($article->thumbnail_url))
        <image:image>
            <image:loc>{{ $article->thumbnail_url }}</image:loc>
            <image:title><![CDATA[{{ $article->title }}]]></image:title>
        </image:image>
        @endif
    </url>
    @endforeach

</urlset>
