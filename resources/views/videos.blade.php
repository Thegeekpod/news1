@extends('layouts.app')

@section('title', 'ভিডিও গ্যালারি - ' . ($g_settings['site_name'] ?? 'নিউজ১'))

@section('content')

<!-- Video Section Header -->
<div style="background: linear-gradient(135deg, #0f172a, #1e293b); color: #fff; border-bottom: 1px solid var(--border-color); padding: 28px 0;">
  <div class="container">
    <div class="article-breadcrumbs" style="margin-bottom: 8px;">
      <a href="{{ route('home') }}" style="color: #94a3b8;">প্রচ্ছদ</a> <i class="fas fa-chevron-right" style="font-size: 0.7rem; color: #64748b;"></i>
      <span style="color: #f8fafc;">ভিডিও সংবাদ</span>
    </div>
    <h1 style="font-size: 1.8rem; font-weight: 700; color: #fff; margin-bottom: 8px;">
      <i class="fas fa-play-circle" style="color: var(--brand-red);"></i> নিউজ১ ভিডিও গ্যালারি
    </h1>
    <p style="color: #cbd5e1; font-size: 0.95rem;">
      দেশ, রাজ্য ও বিশ্বের সেরা তাজা ভিডিও খবর সরাসরি দেখুন।
    </p>
  </div>
</div>

<main class="container main-content-layout">

  <div class="main-feed-column">
    @if($videos->isEmpty())
      <div style="text-align: center; padding: 60px 20px; background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border-color);">
        <i class="fas fa-video-slash" style="font-size: 48px; color: var(--text-muted); margin-bottom: 16px;"></i>
        <h3 style="color: var(--text-primary); font-size: 1.2rem;">কোনো ভিডিও সংবাদ পাওয়া যায়নি।</h3>
      </div>
    @else
      <div class="video-grid" style="grid-template-columns: repeat(2, 1fr);">
        @foreach($videos as $video)
          <div class="video-card" data-video="{{ $video->youtube_embed_url }}">
            <div class="video-thumb-box" style="height: 190px;">
              <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}">
              <div class="play-btn-overlay">
                <div class="play-icon"><i class="fas fa-play"></i></div>
              </div>
            </div>
            <div class="video-title" style="padding: 14px; font-size: 1rem; font-weight: 700;">
              {{ $video->title }}
            </div>
          </div>
        @endforeach
      </div>

      <div class="pagination-wrapper">
        {{ $videos->links() }}
      </div>
    @endif
  </div>

  @include('partials.sidebar')

</main>
@endsection
