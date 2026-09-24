@php
    use App\Helpers\BengaliHelper;
    $defaultTitle = (!empty($g_seo) && !empty($g_seo->meta_title)) ? $g_seo->meta_title : ($g_settings['site_meta_title'] ?? (($g_settings['site_name'] ?? 'নিউজ১') . ' - নির্ভরযোগ্য সর্বশেষ বাংলা খবর | News1 Bengali News Portal'));
    $defaultDesc = (!empty($g_seo) && !empty($g_seo->meta_description)) ? $g_seo->meta_description : ($g_settings['site_meta_description'] ?? 'নিউজ১ বাংলা সংবাদের সবচেয়ে নির্ভরযোগ্য ডিজিটাল পোর্টাল। জানুন রাজ্য, দেশ, আন্তর্জাতিক, খেলাধুলো, বিনোদন ও লাইফস্টাইলের সর্বশেষ খবর।');
@endphp
<!DOCTYPE html>
<html lang="bn">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', $defaultTitle)</title>
  <meta name="description" content="@yield('meta_description', $defaultDesc)">
  <meta name="google-site-verification" content="eyat-0-_-NY7SJQ1YwpM-w-kKKo6iqBc_se0UnG9OZM" />
  <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-N745HGJV');</script>
<!-- End Google Tag Manager -->
  @if(!empty($g_settings['site_meta_keywords']))
    <meta name="keywords" content="{{ $g_settings['site_meta_keywords'] }}">
  @endif

  @if(!empty($g_seo) && !empty($g_seo->other_tags))
    {!! $g_seo->other_tags !!}
  @endif
  <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('/sitemap.xml') }}">
  @if(!empty($g_settings['site_favicon']))
    <link rel="shortcut icon" href="{{ asset($g_settings['site_favicon']) }}" type="image/x-icon">
    <link rel="icon" href="{{ asset($g_settings['site_favicon']) }}" type="image/x-icon">
  @else
    <link rel="shortcut icon" href="{{ asset('logo_png.png') }}" type="image/x-icon">
  @endif
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  @yield('styles')
</head>

<body>
  <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N745HGJV"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

  <!-- Top Utilities Bar -->
  <div class="topbar">
    <div class="container topbar-wrapper">
      <div class="topbar-left">
        <div class="topbar-item">
          <i class="far fa-calendar-alt"></i>
          <span id="current-bengali-date">{{ BengaliHelper::toBengaliDate(now()) }}</span>
        </div>
        <div class="topbar-item">
          <i class="fas fa-cloud-sun"></i>
          <span>{{ $g_settings['weather_location'] ?? 'কলকাতা' }} {{ BengaliHelper::toBengaliNumerals($g_settings['weather_temp'] ?? '৩২') }}°C ({{ $g_settings['weather_desc'] ?? 'রৌদ্রোজ্জ্বল' }})</span>
        </div>

      </div>
      <div class="topbar-right">
        <a href="{{ route('market.rates') }}" class="topbar-market-btn" title="আজকের সোনা, রূপো ও সেনসেক্স বাজার দর">
          <i class="fas fa-coins" style="color: #eab308;"></i>
          <span>বাজার দর</span>
        </a>
        <button class="theme-toggle-btn">
          <i class="fas fa-moon"></i>
          <span class="theme-label">ডার্ক মোড</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Main Header & Brand Logo -->
  <header class="main-header">
    <div class="container header-wrapper">
      <a href="{{ route('home') }}" class="brand-logo">
        <img src="{{ asset('logo_png.png') }}" alt="{{ $g_settings['site_name'] ?? 'NEWS 1' }} Logo" class="site-logo-img">
        <div class="logo-tagline-rotator" aria-label="Tagline">
          <span class="tagline-item">Always 1</span>
          <span class="tagline-item">জানতে হলে দেখতে হবে</span>
        </div>
      </a>
      <div class="header-actions">
        <button class="search-trigger-btn">
          <i class="fas fa-search"></i>
          <span>সন্ধান করুন...</span>
        </button>
        <button class="bookmark-drawer-btn">
          <i class="fas fa-bookmark"></i>
          <span>সংরক্ষিত খবর</span>
        </button>
        <button class="mobile-menu-toggle" id="mobile-menu-toggle-btn">
          <i class="fas fa-bars"></i>
        </button>
      </div>
    </div>
  </header>

  <!-- Category Navigation Bar -->
  <nav class="cat-navbar">
    <div class="container" style="display: flex; align-items: center; justify-content: space-between;">
      <ul class="nav-menu">
        <li><a href="{{ route('home') }}" class="nav-link @if(request()->routeIs('home')) active @endif">প্রচ্ছদ</a></li>
        @if(isset($g_categories))
          @foreach($g_categories as $cat)
            <li>
              <a href="{{ route('category.show', $cat->slug) }}" class="nav-link @if(isset($category) && $category->id === $cat->id) active @endif">
                {{ $cat->name_bn }}
              </a>
            </li>
          @endforeach
        @endif
        <li><a href="{{ route('videos.all') }}" class="nav-link @if(request()->routeIs('videos.all')) active @endif">ভিডিও</a></li>
        <li><a href="{{ route('market.rates') }}" class="nav-link @if(request()->routeIs('market.rates')) active @endif"><i class="fas fa-coins" style="color: #eab308; margin-right: 4px;"></i> বাজার দর</a></li>
      </ul>
    </div>
  </nav>

  <!-- Breaking News Live Ticker Bar -->
  <div class="breaking-ticker-bar">
    <div class="container ticker-wrapper">
      <div class="ticker-label">
        <i class="fas fa-bolt"></i> জরুরি খবর
      </div>
      <div class="ticker-content">
        <div class="ticker-track">
          @if(isset($g_tickers) && $g_tickers->isNotEmpty())
            @foreach($g_tickers as $ticker)
              <a href="{{ $ticker->link_url }}" class="ticker-item">
                <span class="ticker-dot"></span> {{ $ticker->text_bn }}
              </a>
            @endforeach
            @foreach($g_tickers as $ticker)
              <a href="{{ $ticker->link_url }}" class="ticker-item">
                <span class="ticker-dot"></span> {{ $ticker->text_bn }}
              </a>
            @endforeach
          @else
            <a href="{{ route('home') }}" class="ticker-item">
              <span class="ticker-dot"></span> রাজ্যজুড়ে সবুজ শক্তি প্রসারে নতুন সৌর বিদ্যুৎ নীতি ঘোষণা রাজ্য সরকারের
            </a>
          @endif
        </div>
      </div>
    </div>
  </div>

  @yield('content')

  <!-- Footer -->
  <footer class="site-footer">
    <div class="container">
      <div class="footer-grid">
        <div>
          <a href="{{ route('home') }}" class="brand-logo" style="margin-bottom: 16px;">
            <img src="{{ asset('logo_png.png') }}" alt="{{ $g_settings['site_name'] ?? 'NEWS 1' }} Logo" class="site-logo-img footer-logo-img" style="height: 140px;">
          </a>
          <p style="font-size: 0.9rem; line-height: 1.6; color: #94a3b8;">
            {{ $g_settings['footer_about'] ?? 'নিউজ১ হল ভারতের অন্যতম অগ্রণী ডিজিটাল বাংলা সংবাদ মাধ্যম। সত্য, নিরপেক্ষ ও বস্তুনিষ্ঠ খবর পৌঁছে দেওয়াই আমাদের একমাত্র লক্ষ্য।' }}
          </p>
        </div>
        <div>
          <h4 class="footer-col-title">বিভাগসমূহ</h4>
          <div class="footer-links">
            @if(isset($g_categories))
              @foreach($g_categories->take(5) as $cat)
                <a href="{{ route('category.show', $cat->slug) }}">{{ $cat->name_bn }}</a>
              @endforeach
            @endif
          </div>
        </div>
        <div>
          <h4 class="footer-col-title">অন্যান্য পরিষেবা</h4>
          <div class="footer-links">
            <a href="{{ route('home') }}#live">লাইভ টিভি</a>
            <a href="{{ route('coming-soon') }}">ই-পেপার</a>
            <a href="{{ route('videos.all') }}">ভিডিও গ্যালারি</a>
            <a href="{{ route('home') }}">আবহাওয়া আপডেট</a>
          </div>
        </div>
        <div>
          <h4 class="footer-col-title">যোগাযোগ & তথ্য</h4>
          <div class="footer-links">
            <a href="{{ route('coming-soon') }}">আমাদের কথা</a>
            <a href="{{ route('coming-soon') }}">বিজ্ঞাপনের জন্য</a>
            <a href="{{ route('coming-soon') }}">গোপনীয়তা নীতি</a>
            <a href="{{ route('coming-soon') }}">যোগাযোগ করুন</a>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <p>© {{ BengaliHelper::toBengaliNumerals(date('Y')) }} {{ $g_settings['site_name'] ?? 'নিউজ১' }} ডিজিটাল মিডিয়া লিমিটেড। সর্বস্বত্ব সংরক্ষিত।</p>
        <div style="display: flex; gap: 14px; font-size: 1.1rem;">
          @if(!empty($g_settings['facebook_url']))
            <a href="{{ $g_settings['facebook_url'] }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
          @endif
          @if(!empty($g_settings['twitter_url']))
            <a href="{{ $g_settings['twitter_url'] }}" target="_blank"><i class="fab fa-twitter"></i></a>
          @endif
          @if(!empty($g_settings['youtube_url']))
            <a href="{{ $g_settings['youtube_url'] }}" target="_blank"><i class="fab fa-youtube"></i></a>
          @endif
          @if(!empty($g_settings['instagram_url']))
            <a href="{{ $g_settings['instagram_url'] }}" target="_blank"><i class="fab fa-instagram"></i></a>
          @endif
        </div>
      </div>
    </div>
  </footer>

  <!-- Search Modal Overlay -->
  <div class="modal-overlay" id="search-modal">
    <div class="modal-card">
      <div class="modal-header">
        <h3 style="font-size: 1.2rem; font-weight: 700;">সংবাদ অনুসন্ধান</h3>
        <button class="modal-close-btn" id="close-search-modal"><i class="fas fa-times"></i></button>
      </div>
      <form action="{{ route('search') }}" method="GET" id="modal-search-form">
        <div class="search-input-group">
          <i class="fas fa-search" style="color: var(--brand-red);"></i>
          <input type="text" name="q" id="modal-search-input" placeholder="কী বিষয়ে খবর খুঁজছেন? (যেমন: কলকাতা, ক্রিকেট)...">
        </div>
        <div id="search-results-box" style="margin-top: 16px; max-height: 280px; overflow-y: auto;">
          <p style="color: var(--text-muted); font-size: 0.9rem; text-align: center; padding: 20px;">খবর খুঁজতে শব্দ টাইপ করুন...</p>
        </div>
      </form>
    </div>
  </div>

  <!-- Bookmark Side Drawer -->
  <div class="side-drawer" id="bookmark-drawer">
    <div class="drawer-header">
      <h3 style="font-size: 1.15rem; font-weight: 700;"><i class="fas fa-bookmark" style="color: var(--brand-red);"></i> আপনার সংরক্ষিত খবর</h3>
      <button class="modal-close-btn" id="close-bookmark-drawer"><i class="fas fa-times"></i></button>
    </div>
    <div class="bookmark-items-list" id="bookmark-items-list">
      <!-- Dynamic Bookmarks list -->
    </div>
  </div>

  <!-- Video Player Modal Overlay -->
  <div class="modal-overlay" id="video-modal">
    <div class="modal-card" style="max-width: 800px; padding: 12px; background: #000;">
      <div style="display: flex; justify-content: flex-end; padding-bottom: 8px;">
        <button class="modal-close-btn" id="close-video-modal" style="color: #fff;"><i class="fas fa-times"></i></button>
      </div>
      <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
        <iframe id="modal-video-frame" style="position: absolute; top:0; left: 0; width: 100%; height: 100%; border:0;" allowfullscreen></iframe>
      </div>
    </div>
  </div>

  <script src="{{ asset('js/main.js?v=2.2') }}"></script>
  @yield('scripts')
</body>

</html>
