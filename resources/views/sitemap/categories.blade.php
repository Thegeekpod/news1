{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

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

</urlset>
