@extends('layouts.app')

@section('title', $article->meta_title ?: $article->title)
@section('meta_description', $article->meta_description ?: Str::limit(strip_tags($article->excerpt ?: $article->content), 160))
@section('keywords', $article->keywords ?: ($article->category->name_bn . ', বাংলা খবর, ' . $article->title))

@section('og_image', $article->thumbnail_url ?: asset('logo.png'))
@section('og_type', 'article')
@section('og_article_meta')
  <meta property="article:published_time" content="{{ $article->published_at ? $article->published_at->toIso8601String() : '' }}">
  <meta property="article:section" content="{{ $article->category->name_bn ?? 'খবর' }}">
@endsection

@section('content')
@php
    use App\Helpers\BengaliHelper;

    // Prepare In-Article Ad Banner HTML
    $inArticleAdHtml = '';
    if (isset($g_ads['in_article_banner']) && $g_ads['in_article_banner']->isNotEmpty()) {
        $adImages = '';
        foreach ($g_ads['in_article_banner'] as $ad) {
            $adImages .= '<a href="' . e($ad->destination_url ?? '#') . '" target="_blank" style="display:inline-block; margin: 8px;"><img src="' . asset($ad->image_url) . '" alt="' . e($ad->title) . '" style="max-width: 100%; border-radius: var(--radius-md); box-shadow: var(--shadow-sm);"></a>';
        }
        $inArticleAdHtml = '<div class="in-article-ad-box" style="margin: 24px 0; text-align: center;">' . $adImages . '</div>';
    } else {
        $inArticleAdHtml = '';
    }

    // Inject Ad after 1-2 paragraphs/sentences
    $bodyContent = $article->content;
    if (str_contains($bodyContent, '</p>')) {
        $paragraphs = explode('</p>', $bodyContent);
        if (count($paragraphs) > 2) {
            $paragraphs[1] .= '</p>' . $inArticleAdHtml;
            $bodyContent = implode('</p>', $paragraphs);
        } else {
            $paragraphs[0] .= '</p>' . $inArticleAdHtml;
            $bodyContent = implode('</p>', $paragraphs);
        }
    } else {
        $sentences = preg_split('/(।|\.)\s+/u', $bodyContent, 3, PREG_SPLIT_DELIM_CAPTURE);
        if (count($sentences) >= 4) {
            $bodyContent = $sentences[0] . $sentences[1] . ' ' . $sentences[2] . $sentences[3] . $inArticleAdHtml . (isset($sentences[4]) ? implode('', array_slice($sentences, 4)) : '');
        } else {
            $bodyContent = $bodyContent . $inArticleAdHtml;
        }
    }
@endphp

<main class="container main-content-layout">

  <div class="article-container" style="width: 100%;">
    
    <!-- Article Header & Meta -->
    <div class="article-header">
      <div class="article-breadcrumbs">
        <a href="{{ route('home') }}">প্রচ্ছদ</a> <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i>
        @if($article->category)
          <a href="{{ route('category.show', $article->category->slug) }}">{{ $article->category->name_bn }}</a> <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i>
        @endif
        <span>বিস্তারিত সংবাদ</span>
      </div>
      
      <h1 class="article-title">
        {{ $article->title }}
      </h1>

      @if($article->excerpt)
        <h2 class="article-subtitle">
          {{ $article->excerpt }}
        </h2>
      @endif

      <div class="article-meta-bar">
        <div class="author-info">
          <img src="{{ $article->author->avatar_url ?? 'https://picsum.photos/seed/author'.$article->id.'/150/150' }}" alt="{{ $article->author->name ?? 'সংবাদদাতা' }}" class="author-avatar">
          <div>
            <div class="author-name">{{ $article->author->name ?? 'নিউজ১ স্পেশাল ডেস্ক' }}</div>
            <div class="pub-date">প্রকাশিত: {{ BengaliHelper::toBengaliDate($article->published_at) }} • {{ BengaliHelper::toBengaliTime($article->published_at) }}</div>
          </div>
        </div>
        
        <div class="article-actions">
          <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="share-btn fb" title="ফেসবুকে শেয়ার করুন"><i class="fab fa-facebook-f"></i></a>
          <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($article->title) }}" target="_blank" class="share-btn tw" title="টুইটারে শেয়ার করুন"><i class="fab fa-twitter"></i></a>
          <a href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' ' . request()->url()) }}" target="_blank" class="share-btn wa" title="হোয়াটসঅ্যাপে শেয়ার করুন"><i class="fab fa-whatsapp"></i></a>
          <button class="save-article-btn share-btn cp" data-title="{{ $article->title }}" data-url="{{ route('article.show', $article->slug) }}" title="সংরক্ষণ করুন">
            <i class="far fa-bookmark"></i>
          </button>
        </div>
      </div>

      <!-- Audio Reader Widget -->
      <div class="audio-reader-box">
        <div class="audio-reader-info">
          <i class="fas fa-headphones-alt" style="color: var(--brand-red); font-size: 1.3rem;"></i>
          <span>নিউজ১ অডিও ভয়েস: এই খবরটি শুনুন</span>
        </div>
        <button id="btn-play-article-audio">
          <i class="fas fa-play-circle"></i> খবর শুনুন
        </button>
      </div>

      <!-- Featured Media Image -->
      @if($article->thumbnail_url)
        <div class="featured-media-box">
          <img src="{{ $article->thumbnail_url }}" alt="{{ $article->title }}" style="width: 100%; border-radius: var(--radius-md);">
          <div class="media-caption">{{ $article->title }} — ছবি: নিউজ১।</div>
        </div>
      @endif
    </div>

    <!-- Article Body Content (With Injected Ad) -->
    <div class="article-content">
      {!! $bodyContent !!}
    </div>

    <!-- Ad Space ABOVE Tags Cloud -->
    @if(isset($g_ads['above_related_banner']) && $g_ads['above_related_banner']->isNotEmpty())
      <div style="margin-top: 28px; margin-bottom: 24px; text-align: center;">
        @foreach($g_ads['above_related_banner'] as $ad)
          <a href="{{ $ad->destination_url ?? '#' }}" target="_blank">
            <img src="{{ asset($ad->image_url) }}" alt="{{ $ad->title }}" style="max-width: 100%; border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
          </a>
        @endforeach
      </div>
    @endif

    <!-- Tags Cloud -->
    @if($article->tags && $article->tags->isNotEmpty())
      <div style="border-top: 1px solid var(--border-color); padding-top: 16px; margin-bottom: 32px; display: flex; flex-wrap: wrap; align-items: center; gap: 8px;">
        <span style="font-weight: 700; font-size: 0.9rem; color: var(--text-muted);"><i class="fas fa-tags"></i> সম্পর্কিত ট্যাগ:</span>
        @foreach($article->tags as $tag)
          <a href="{{ route('tag.show', $tag->slug) }}" class="cat-tag" style="background: var(--bg-surface-subtle); color: var(--text-primary); border: 1px solid var(--border-color);">
            #{{ $tag->name_bn }}
          </a>
        @endforeach
      </div>
    @endif

    <!-- Related Articles Grid -->
    @php
        $relatedArticles = \App\Models\Article::published()
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->take(2)
            ->get();
    @endphp

    @if($relatedArticles->isNotEmpty())
      <div class="category-section-container theme-state" style="margin-top: 20px;">
        <div class="section-header">
          <h3 class="section-title"><i class="fas fa-layer-group"></i> এই বিভাগের আরও খবর</h3>
        </div>
        <div class="cards-2col-grid">
          @foreach($relatedArticles as $rel)
            <article class="news-vertical-card">
              <div class="card-image-box">
                <img src="{{ $rel->thumbnail_url ?? 'https://picsum.photos/seed/'.$rel->id.'/600/400' }}" alt="{{ $rel->title }}">
              </div>
              <div class="card-body">
                <h3 class="card-title" style="font-size: 1rem;"><a href="{{ route('article.show', $rel->slug) }}">{{ $rel->title }}</a></h3>
                <div class="card-meta" style="margin-top: auto;">
                  <span><i class="far fa-clock"></i> {{ BengaliHelper::toBengaliTime($rel->published_at) }}</span>
                </div>
              </div>
            </article>
          @endforeach
        </div>
      </div>
    @endif

  </div>

  <!-- Sidebar Column -->
  @include('partials.sidebar')

</main>
@endsection
