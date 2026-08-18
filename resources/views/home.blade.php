@extends('layouts.app')

@section('title', ($g_settings['site_name'] ?? 'নিউজ১') . ' - নির্ভরযোগ্য সর্বশেষ বাংলা খবর | News1 Bengali News Portal')

@section('content')
@php
    use App\Helpers\BengaliHelper;
    $rawVideoUrl = $g_settings['hero_video_url'] ?? 'https://www.youtube.com/embed/dQw4w9WgXcQ';
    if (str_contains($rawVideoUrl, 'watch?v=')) {
        preg_match('/v=([a-zA-Z0-9_-]+)/', $rawVideoUrl, $matches);
        $heroVideoUrl = isset($matches[1]) ? "https://www.youtube.com/embed/{$matches[1]}?rel=0" : $rawVideoUrl;
    } elseif (str_contains($rawVideoUrl, 'youtu.be/')) {
        $parts = explode('youtu.be/', $rawVideoUrl);
        $id = explode('?', $parts[1] ?? '')[0];
        $heroVideoUrl = $id ? "https://www.youtube.com/embed/{$id}?rel=0" : $rawVideoUrl;
    } else {
        $heroVideoUrl = $rawVideoUrl;
    }
@endphp

<!-- Main Content Layout -->
<main class="container main-content-layout">

  <!-- Primary Feed Column -->
  <div class="main-feed-column">

    <!-- Hero News Grid -->
    <section class="hero-news-grid" id="live">
      <div class="hero-main-card"
        style="height: 440px; padding: 0; overflow: hidden; background: #000; position: relative; border-radius: var(--radius-md); border: 2px solid var(--brand-red);">
        <!-- Youtube / Facebook Video Embed Player -->
        <iframe id="hero-video-frame" src="{{ $heroVideoUrl }}"
          title="নিউজ১ লাইভ ভিডিও বুলেটিন"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen style="width: 100%; height: 100%; border: 0; display: block;"></iframe>
      </div>

      <div class="hero-secondary-stack">
        @if(isset($topStories) && $topStories->isNotEmpty())
          @foreach($topStories->take(4) as $story)
            <div class="news-horizontal-card">
              <div class="card-thumb">
                <img src="{{ $story->thumbnail_url ?? 'https://images.unsplash.com/photo-1526304640581-d334cdbbf45e?auto=format&fit=crop&w=400&q=80' }}"
                  alt="{{ $story->title }}">
              </div>
              <div>
                <h3 class="card-title"><a href="{{ route('article.show', $story->slug) }}">{{ $story->title }}</a></h3>
                <div class="card-meta">
                  <span>{{ $story->category->name_bn ?? 'সংবাদ' }}</span> • <span>{{ BengaliHelper::toBengaliTime($story->published_at) }}</span>
                </div>
              </div>
            </div>
          @endforeach
        @endif
      </div>
    </section>

    <!-- Homepage Ad Banner Slot (ABOVE State & National News) -->
    @if(isset($g_ads['home_banner_top']) && $g_ads['home_banner_top']->isNotEmpty())
      <div style="margin-bottom: 28px; text-align: center;">
        @foreach($g_ads['home_banner_top'] as $ad)
          <a href="{{ $ad->destination_url ?? '#' }}" target="_blank">
            <img src="{{ asset($ad->image_url) }}" alt="{{ $ad->title }}" style="max-width: 100%; border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
          </a>
        @endforeach
      </div>
    @endif

    <!-- 1. State & National News Block -->
    <section class="category-section-container theme-state">
      <div class="section-header">
        <h2 class="section-title"><i class="fas fa-newspaper"></i> রাজ্য ও দেশের খবর</h2>
        <a href="{{ route('category.show', $stateArticles->first()->category->slug ?? 'politics') }}" class="view-all-link">সব খবর দেখুন <i class="fas fa-arrow-right"></i></a>
      </div>
      <div class="cards-2col-grid">
        @if(isset($stateArticles) && $stateArticles->isNotEmpty())
          @foreach($stateArticles->take(2) as $article)
            <article class="news-vertical-card">
              <div class="card-image-box">
                <img src="{{ $article->thumbnail_url ?? 'https://images.unsplash.com/photo-1572949645841-094f3a9c4c94?auto=format&fit=crop&w=600&q=80' }}"
                  alt="{{ $article->title }}">
              </div>
              <div class="card-body">
                <span class="cat-tag" style="align-self: flex-start;">{{ $article->category->name_bn ?? 'রাজ্য' }}</span>
                <h3 class="card-title"><a href="{{ route('article.show', $article->slug) }}">{{ $article->title }}</a></h3>
                <p class="card-excerpt">{{ $article->excerpt ?? Str::limit(strip_tags($article->content), 120) }}</p>
                <div class="card-meta" style="margin-top: auto; justify-content: space-between;">
                  <span><i class="far fa-clock"></i> {{ BengaliHelper::toBengaliTime($article->published_at) }}</span>
                  <button class="save-article-btn" data-title="{{ $article->title }}" data-url="{{ route('article.show', $article->slug) }}">
                    <i class="far fa-bookmark"></i>
                  </button>
                </div>
              </div>
            </article>
          @endforeach
        @endif
      </div>
    </section>

    <!-- 2. Sports News Block -->
    <section class="category-section-container theme-sports">
      <div class="section-header">
        <h2 class="section-title"><i class="fas fa-running"></i> খেলাধুলো (Sports)</h2>
        <a href="{{ route('category.show', $sportsArticles->first()->category->slug ?? 'sports') }}" class="view-all-link">স্পোর্টস বুলেটিন <i class="fas fa-arrow-right"></i></a>
      </div>
      <div class="cards-2col-grid">
        @if(isset($sportsArticles) && $sportsArticles->isNotEmpty())
          @foreach($sportsArticles->take(2) as $article)
            <div class="news-horizontal-card">
              <div class="card-thumb">
                <img src="{{ $article->thumbnail_url ?? 'https://images.unsplash.com/photo-1531415074968-036ba1b575da?auto=format&fit=crop&w=300&q=80' }}"
                  alt="{{ $article->title }}">
              </div>
              <div>
                <h3 class="card-title"><a href="{{ route('article.show', $article->slug) }}">{{ $article->title }}</a></h3>
                <div class="card-meta">{{ $article->category->name_bn ?? 'খেলা' }} • {{ BengaliHelper::toBengaliTime($article->published_at) }}</div>
              </div>
            </div>
          @endforeach
        @endif
      </div>
    </section>

    <!-- 3. Video News Gallery Section (Dark Aesthetic) -->
    <section class="dark-section-banner">
      <div class="section-header">
        <h2 class="section-title"><i class="fas fa-play-circle"></i> নিউজ১ ভিডিও গ্যালারি</h2>
        <a href="{{ route('videos.all') }}" class="view-all-link" style="color: #f43f5e;">সব ভিডিও দেখুন <i
            class="fas fa-arrow-right"></i></a>
      </div>
      <div class="video-grid">
        @if(isset($videos) && $videos->isNotEmpty())
          @foreach($videos->take(3) as $video)
            <div class="video-card" data-video="{{ $video->youtube_embed_url }}">
              <div class="video-thumb-box">
                <img src="{{ $video->thumbnail_url ?? 'https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?auto=format&fit=crop&w=400&q=80' }}"
                  alt="{{ $video->title }}">
                <div class="play-btn-overlay">
                  <div class="play-icon"><i class="fas fa-play"></i></div>
                </div>
              </div>
              <div class="video-title">{{ $video->title }}</div>
            </div>
          @endforeach
        @endif
      </div>
    </section>

    <!-- 4. International News Block -->
    <section class="category-section-container theme-world">
      <div class="section-header">
        <h2 class="section-title"><i class="fas fa-globe-americas"></i> আন্তর্জাতিক খবর (World News)</h2>
        <a href="{{ route('category.show', $internationalArticles->first()->category->slug ?? 'international') }}" class="view-all-link">বিশ্বসংবাদ দেখুন <i class="fas fa-arrow-right"></i></a>
      </div>
      <div class="cards-2col-grid">
        @if(isset($internationalArticles) && $internationalArticles->isNotEmpty())
          @foreach($internationalArticles->take(2) as $article)
            <article class="news-vertical-card">
              <div class="card-image-box">
                <img src="{{ $article->thumbnail_url ?? 'https://images.unsplash.com/photo-1541872703-74c5e44368f9?auto=format&fit=crop&w=600&q=80' }}"
                  alt="{{ $article->title }}">
              </div>
              <div class="card-body">
                <span class="cat-tag" style="align-self: flex-start; background: #2563eb;">{{ $article->category->name_bn ?? 'আন্তর্জাতিক' }}</span>
                <h3 class="card-title"><a href="{{ route('article.show', $article->slug) }}">{{ $article->title }}</a></h3>
                <p class="card-excerpt">{{ $article->excerpt ?? Str::limit(strip_tags($article->content), 120) }}</p>
                <div class="card-meta" style="margin-top: auto; justify-content: space-between;">
                  <span><i class="far fa-clock"></i> {{ BengaliHelper::toBengaliTime($article->published_at) }}</span>
                  <button class="save-article-btn" data-title="{{ $article->title }}" data-url="{{ route('article.show', $article->slug) }}"><i
                      class="far fa-bookmark"></i></button>
                </div>
              </div>
            </article>
          @endforeach
        @endif
      </div>
    </section>

    <!-- 5. Entertainment & Cinema Block -->
    <section class="category-section-container theme-entertainment">
      <div class="section-header">
        <h2 class="section-title"><i class="fas fa-film"></i> বিনোদনের দুনিয়া (Entertainment)</h2>
        <a href="{{ route('category.show', $entertainmentArticles->first()->category->slug ?? 'entertainment') }}" class="view-all-link">বিনোদন জগত <i class="fas fa-arrow-right"></i></a>
      </div>
      <div class="entertainment-grid">
        @if(isset($entertainmentArticles) && $entertainmentArticles->isNotEmpty())
          @foreach($entertainmentArticles->take(3) as $article)
            <article class="news-vertical-card">
              <div class="card-image-box">
                <img src="{{ $article->thumbnail_url ?? 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=400&q=80' }}"
                  alt="{{ $article->title }}">
              </div>
              <div class="card-body">
                <span class="cat-tag" style="align-self: flex-start; background: #d97706;">{{ $article->category->name_bn ?? 'বিনোদন' }}</span>
                <h3 class="card-title" style="font-size: 1rem;"><a href="{{ route('article.show', $article->slug) }}">{{ $article->title }}</a></h3>
                <div class="card-meta" style="margin-top: auto;">{{ BengaliHelper::toBengaliTime($article->published_at) }}</div>
              </div>
            </article>
          @endforeach
        @endif
      </div>
    </section>

    <!-- 6. Technology & Innovations Block -->
    <section class="category-section-container theme-tech">
      <div class="section-header">
        <h2 class="section-title"><i class="fas fa-microchip"></i> টেকনোলজি ও গেজেট (Tech & Innovation)</h2>
        <a href="{{ route('category.show', $techArticles->first()->category->slug ?? 'tech') }}" class="view-all-link">টেক আপডেট <i class="fas fa-arrow-right"></i></a>
      </div>
      <div class="cards-2col-grid">
        @if(isset($techArticles) && $techArticles->isNotEmpty())
          @foreach($techArticles->take(2) as $article)
            <div class="news-horizontal-card">
              <div class="card-thumb">
                <img src="{{ $article->thumbnail_url ?? 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=300&q=80' }}"
                  alt="{{ $article->title }}">
              </div>
              <div>
                <h3 class="card-title"><a href="{{ route('article.show', $article->slug) }}">{{ $article->title }}</a></h3>
                <div class="card-meta">{{ $article->category->name_bn ?? 'টেক' }} • {{ BengaliHelper::toBengaliTime($article->published_at) }}</div>
              </div>
            </div>
          @endforeach
        @endif
      </div>
    </section>

    <!-- Homepage Tech Section Ad Banner Slot -->
    @if(isset($g_ads['tech_banner']) && $g_ads['tech_banner']->isNotEmpty())
      <div style="margin-top: 28px; margin-bottom: 28px; text-align: center;">
        @foreach($g_ads['tech_banner'] as $ad)
          <a href="{{ $ad->destination_url ?? '#' }}" target="_blank">
            <img src="{{ asset($ad->image_url) }}" alt="{{ $ad->title }}" style="max-width: 100%; border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
          </a>
        @endforeach
      </div>
    @endif

  </div>

  <!-- Sidebar Column -->
  @include('partials.sidebar')

</main>
@endsection
