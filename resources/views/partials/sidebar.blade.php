@php
    use App\Helpers\BengaliHelper;
    $sidebarTags = \App\Models\Tag::take(6)->get();
@endphp

<!-- Sidebar Column -->
<aside class="sidebar">

  <!-- Trending Top 5 Widget -->
  <div class="widget-box">
    <h3 class="widget-title">
      <span><i class="fas fa-fire" style="color: var(--brand-red);"></i> সর্বাধিক পঠিত (Trending)</span>
    </h3>
    <div class="trending-list">
      @if(isset($g_popular) && $g_popular->isNotEmpty())
        @foreach($g_popular->take(5) as $index => $p_art)
          <div class="trending-item">
            <span class="trending-rank">{{ BengaliHelper::toBengaliNumerals($index + 1) }}</span>
            <a href="{{ route('article.show', $p_art->slug) }}" class="trending-text">{{ $p_art->title }}</a>
          </div>
        @endforeach
      @else
        <div class="trending-item">
          <span class="trending-rank">১</span>
          <a href="#" class="trending-text">রাজ্য সরকারি কর্মচারীদের জন্য ডিএ বৃদ্ধিতে নতুন নির্দেশিকা জারি</a>
        </div>
        <div class="trending-item">
          <span class="trending-rank">২</span>
          <a href="#" class="trending-text">উচ্চমাধ্যমিক পরীক্ষার ফলাফলে মেধাতালিকায় বাঁকুড়ার ছাত্রের শীর্ষস্থান</a>
        </div>
        <div class="trending-item">
          <span class="trending-rank">৩</span>
          <a href="#" class="trending-text">আইপিএল নিলামে সর্বাধিক দর পেলেন ভারতীয় স্পিনার, তৈরি হল ইতিহাস</a>
        </div>
      @endif
    </div>
  </div>

  <!-- Sidebar Advertisement Slot (Right ABOVE Popular Topics) -->
  @if(isset($g_ads['sidebar_banner']) && $g_ads['sidebar_banner']->isNotEmpty())
    <div class="widget-box" style="text-align: center; padding: 14px;">
      <span style="display: block; font-size: 0.72rem; color: var(--text-muted); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">বিজ্ঞাপন</span>
      @foreach($g_ads['sidebar_banner'] as $ad)
        <a href="{{ $ad->destination_url ?? '#' }}" target="_blank">
          <img src="{{ asset($ad->image_url) }}" alt="{{ $ad->title }}" style="max-width: 100%; border-radius: var(--radius-md); margin: 0 auto;">
        </a>
      @endforeach
    </div>
  @endif

  <!-- Popular Trending Hashtags & Topics Widget -->
  <div class="widget-box">
    <h3 class="widget-title">
      <span><i class="fas fa-hashtag" style="color: var(--brand-red);"></i> জনপ্রিয় বিষয়বস্তু</span>
    </h3>
    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
      @if(isset($sidebarTags) && $sidebarTags->isNotEmpty())
        @foreach($sidebarTags as $tag)
          <a href="{{ route('tag.show', $tag->slug) }}"
            style="background: var(--bg-surface-subtle); border: 1px solid var(--border-color); color: var(--text-primary); padding: 6px 12px; border-radius: var(--radius-full); font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 4px;">#{{ $tag->name_bn }}</a>
        @endforeach
      @endif
    </div>
  </div>

  <!-- Live City Weather & Forecast Widget -->
  <div class="widget-box">
    <h3 class="widget-title">
      <span><i class="fas fa-cloud-sun" style="color: #0284c7;"></i> শহরের আবহাওয়া</span>
    </h3>
    <div class="weather-city-tabs" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; margin-bottom: 12px;">
      <button class="city-weather-tab active" data-city="durgapur" style="background: var(--brand-red); color: #fff; border: none; padding: 6px; border-radius: var(--radius-sm); font-size: 0.8rem; font-weight: 600; cursor: pointer;">দূর্গাপুর</button>
      <button class="city-weather-tab" data-city="kolkata" style="background: var(--bg-surface-subtle); border: 1px solid var(--border-color); color: var(--text-primary); padding: 6px; border-radius: var(--radius-sm); font-size: 0.8rem; font-weight: 600; cursor: pointer;">কলকাতা</button>
      <button class="city-weather-tab" data-city="siliguri" style="background: var(--bg-surface-subtle); border: 1px solid var(--border-color); color: var(--text-primary); padding: 6px; border-radius: var(--radius-sm); font-size: 0.8rem; font-weight: 600; cursor: pointer;">শিলিগুড়ি</button>
      <button class="city-weather-tab" data-city="asansol" style="background: var(--bg-surface-subtle); border: 1px solid var(--border-color); color: var(--text-primary); padding: 6px; border-radius: var(--radius-sm); font-size: 0.8rem; font-weight: 600; cursor: pointer;">আসানসোল</button>
    </div>
    <div id="city-weather-display" style="background: linear-gradient(135deg, #0284c7, #0369a1); color: #fff; padding: 14px; border-radius: var(--radius-md); text-align: center;">
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <div style="text-align: left;">
          <span id="weather-city-name" style="font-size: 1.1rem; font-weight: 700;">{{ $g_settings['weather_location'] ?? 'দূর্গাপুর' }}</span>
          <div id="weather-condition" style="font-size: 0.85rem; opacity: 0.9;">{{ $g_settings['weather_desc'] ?? 'পরিষ্কার আকাশ' }}</div>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
          <i id="weather-icon" class="fas fa-sun" style="font-size: 2rem; color: #fde047;"></i>
          <span id="weather-temp" style="font-size: 1.8rem; font-weight: 800;">{{ BengaliHelper::toBengaliNumerals($g_settings['weather_temp'] ?? '৩৩') }}°C</span>
        </div>
      </div>
      <div style="display: flex; justify-content: space-between; margin-top: 10px; padding-top: 8px; border-top: 1px solid rgba(255,255,255,0.2); font-size: 0.78rem;">
        <span>বাতাসের আর্দ্রতা: <strong id="weather-humidity">{{ BengaliHelper::toBengaliNumerals($g_settings['weather_humidity'] ?? '৬৪%') }}</strong></span>
        <span>বায়ুর গতিবেগ: <strong id="weather-wind">{{ BengaliHelper::toBengaliNumerals($g_settings['weather_wind'] ?? '১১') }} কিমি/ঘণ্টা</strong></span>
      </div>
  <!-- Newsletter Subscription Box -->
  <div class="ad-banner-box" style="background: linear-gradient(135deg, #1e1b4b, #312e81); border: 1px solid var(--border-color); padding: 16px; border-radius: var(--radius-md); margin-top: 20px;">
    <div class="ad-title" style="color: #f43f5e; font-weight: 700; font-size: 0.9rem; margin-bottom: 6px;"><i class="fas fa-envelope-open-text"></i> নিউজলেটার</div>
    <div class="ad-heading" style="font-size: 1.05rem; font-weight: 700; color: #ffffff; margin-bottom: 6px;">দৈনিক খবর পান ইমেইলে</div>
    <p style="font-size: 0.82rem; margin-bottom: 12px; color: #cbd5e1;">প্রতিদিনের প্রধান ব্রেকিং সংবাদ সরাসরি আপনার ইমেইল বক্সে পেতে বিনামূল্যে সাবস্ক্রাইব করুন</p>
    <form action="{{ route('newsletter.subscribe') }}" method="POST" style="display: flex; flex-direction: column; gap: 8px;">
      @csrf
      <input type="email" name="email" placeholder="আপনার ইমেইল ঠিকানা লিখুন..." required
        style="padding: 10px 14px; border-radius: var(--radius-full); border: none; font-family: inherit; font-size: 0.88rem; width: 100%; outline: none; background: #ffffff; color: #0f172a;">
      <button type="submit" class="ad-btn"
        style="background: var(--brand-red); color: #fff; border: none; border-radius: var(--radius-full); cursor: pointer; padding: 10px; font-weight: 700; font-size: 0.9rem;">সাবস্ক্রাইব করুন</button>
    </form>
  </div>

</aside>
