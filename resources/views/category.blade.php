@extends('layouts.app')

@section('title', $category->name_bn . ' - ' . ($g_settings['site_name'] ?? 'নিউজ১'))

@section('content')
@php
    use App\Helpers\BengaliHelper;
@endphp

<!-- Category Banner Header -->
<div style="background: linear-gradient(135deg, var(--bg-surface-subtle), var(--bg-surface)); border-bottom: 1px solid var(--border-color); padding: 28px 0;">
  <div class="container">
    <div class="article-breadcrumbs" style="margin-bottom: 8px;">
      <a href="{{ route('home') }}">প্রচ্ছদ</a> <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i>
      <span>বিভাগ</span> <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i>
      <span>{{ $category->name_bn }}</span>
    </div>
    <h1 style="font-size: 2rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">
      <i class="fas fa-newspaper" style="color: var(--brand-red);"></i> {{ $category->name_bn }}
    </h1>
    <p style="color: var(--text-muted); font-size: 0.95rem;">
      {{ $category->description ?? $category->name_bn . ' বিভাগের সমস্ত টাটকা খবর একনজরে।' }}
    </p>
  </div>
</div>

<!-- Main Content Layout -->
<main class="container main-content-layout">

  <div class="main-feed-column">
    
    @if($articles->isEmpty())
      <div style="text-align: center; padding: 60px 20px; background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border-color);">
        <i class="fas fa-newspaper" style="font-size: 48px; color: var(--text-muted); margin-bottom: 16px;"></i>
        <h3 style="color: var(--text-primary); font-size: 1.2rem;">এই বিভাগে কোনো নিবন্ধ পাওয়া যায়নি।</h3>
        <p style="color: var(--text-muted); margin-top: 8px;">শীঘ্রই নতুন খবর যোগ করা হবে, আমাদের সাথেই থাকুন।</p>
      </div>
    @else
      <!-- Category Top Ad Banner (Where news starts) -->
      @if(isset($g_ads['category_banner_top']) && $g_ads['category_banner_top']->isNotEmpty())
        <div style="margin-bottom: 24px; text-align: center;">
          @foreach($g_ads['category_banner_top'] as $ad)
            <a href="{{ $ad->destination_url ?? '#' }}" target="_blank">
              <img src="{{ asset($ad->image_url) }}" alt="{{ $ad->title }}" style="max-width: 100%; border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
            </a>
          @endforeach
        </div>
      @endif

      <div class="cards-2col-grid">
        @foreach($articles as $article)
          <article class="news-vertical-card">
            <div class="card-image-box">
              <img src="{{ $article->thumbnail_url ?? 'https://picsum.photos/seed/'.$article->id.'/600/400' }}" alt="{{ $article->title }}">
            </div>
            <div class="card-body">
              <span class="cat-tag" style="align-self: flex-start;">{{ $category->name_bn }}</span>
              <h3 class="card-title"><a href="{{ route('article.show', $article->slug) }}">{{ $article->title }}</a></h3>
              <p class="card-excerpt">{{ $article->excerpt ?? Str::limit(strip_tags($article->content), 120) }}</p>
              <div class="card-meta" style="margin-top: auto; justify-content: space-between;">
                <span><i class="far fa-clock"></i> {{ BengaliHelper::toBengaliTime($article->published_at) }}</span>
                <button class="save-article-btn" data-title="{{ $article->title }}" data-url="{{ route('article.show', $article->slug) }}"><i class="far fa-bookmark"></i></button>
              </div>
            </div>
          </article>
        @endforeach
      </div>

      <!-- Category Bottom Ad Banner (Where news ends) -->
      @if(isset($g_ads['category_banner_bottom']) && $g_ads['category_banner_bottom']->isNotEmpty())
        <div style="margin-top: 28px; margin-bottom: 24px; text-align: center;">
          @foreach($g_ads['category_banner_bottom'] as $ad)
            <a href="{{ $ad->destination_url ?? '#' }}" target="_blank">
              <img src="{{ asset($ad->image_url) }}" alt="{{ $ad->title }}" style="max-width: 100%; border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
            </a>
          @endforeach
        </div>
      @endif

      <!-- Pagination -->
      <div class="pagination-wrapper">
        {{ $articles->links() }}
      </div>
    @endif

  </div>

  <!-- Sidebar Column -->
  @include('partials.sidebar')

</main>
@endsection
