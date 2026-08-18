@extends('layouts.app')

@section('title', 'শীর্ষ সংবাদ - ' . ($g_settings['site_name'] ?? 'নিউজ১'))

@section('content')
@php
    use App\Helpers\BengaliHelper;
@endphp

<!-- Header Banner -->
<div style="background: linear-gradient(135deg, var(--bg-surface-subtle), var(--bg-surface)); border-bottom: 1px solid var(--border-color); padding: 28px 0;">
  <div class="container">
    <div class="article-breadcrumbs" style="margin-bottom: 8px;">
      <a href="{{ route('home') }}">প্রচ্ছদ</a> <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i>
      <span>শীর্ষ সংবাদ</span>
    </div>
    <h1 style="font-size: 1.8rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">
      <i class="fas fa-fire" style="color: var(--brand-red);"></i> আজকের শীর্ষ সংবাদ
    </h1>
    <p style="color: var(--text-muted); font-size: 0.95rem;">
      সর্বাধিক গুরুত্বপূর্ণ ও আলোচিত শীর্ষ সংবাদের সর্বশেষ আপডেট।
    </p>
  </div>
</div>

<main class="container main-content-layout">

  <div class="main-feed-column">
    @if($articles->isEmpty())
      <div style="text-align: center; padding: 60px 20px; background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border-color);">
        <i class="fas fa-newspaper" style="font-size: 48px; color: var(--text-muted); margin-bottom: 16px;"></i>
        <h3 style="color: var(--text-primary); font-size: 1.2rem;">কোনো শীর্ষ সংবাদ পাওয়া যায়নি।</h3>
      </div>
    @else
      <div class="cards-2col-grid">
        @foreach($articles as $article)
          <article class="news-vertical-card">
            <div class="card-image-box">
              <img src="{{ $article->thumbnail_url ?? 'https://picsum.photos/seed/'.$article->id.'/600/400' }}" alt="{{ $article->title }}">
            </div>
            <div class="card-body">
              <span class="cat-tag" style="align-self: flex-start;">{{ $article->category->name_bn ?? 'শীর্ষ সংবাদ' }}</span>
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

      <div class="pagination-wrapper">
        {{ $articles->links() }}
      </div>
    @endif
  </div>

  @include('partials.sidebar')

</main>
@endsection
