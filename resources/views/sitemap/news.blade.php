{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">

    @foreach($articles as $article)
    <url>
        <loc>{{ route('article.show', $article->slug) }}</loc>
        <news:news>
            <news:publication>
                <news:name>{{ $siteName }}</news:name>
                <news:language>bn</news:language>
            </news:publication>
            <news:publication_date>{{ ($article->published_at ?? $article->created_at ?? now())->toIso8601String() }}</news:publication_date>
            <news:title><![CDATA[{{ $article->title }}]]></news:title>
            @if(!empty($article->keywords))
            <news:keywords><![CDATA[{{ $article->keywords }}]]></news:keywords>
            @endif
        </news:news>
    </url>
    @endforeach

</urlset>
