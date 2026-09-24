@extends('layouts.admin')

@section('title', 'Settings Panel')
@section('header_title', 'Settings Panel')

@section('content')
<div class="admin-card">
  <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <!-- Website Live / Maintenance Mode Section -->
    <div style="margin-bottom: 24px; border: 1px solid {{ ($settings['site_live_mode'] ?? '1') === '1' ? 'rgba(34, 197, 94, 0.3)' : 'rgba(239, 68, 68, 0.4)' }}; background: {{ ($settings['site_live_mode'] ?? '1') === '1' ? 'rgba(34, 197, 94, 0.05)' : 'rgba(239, 68, 68, 0.08)' }}; border-radius: 10px; padding: 20px;">
      <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
        <div>
          <h3 style="font-size: 16px; font-weight: 700; color: #f8fafc; margin: 0 0 6px 0; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-power-off" style="color: {{ ($settings['site_live_mode'] ?? '1') === '1' ? '#22c55e' : '#ef4444' }};"></i>
            Website Live Status (ওয়েবসাইট লাইভ মোড)
            <span style="font-size: 11px; padding: 3px 8px; border-radius: 12px; font-weight: 600; background: {{ ($settings['site_live_mode'] ?? '1') === '1' ? '#166534' : '#991b1b' }}; color: {{ ($settings['site_live_mode'] ?? '1') === '1' ? '#86efac' : '#fca5a5' }};">
              {{ ($settings['site_live_mode'] ?? '1') === '1' ? '🟢 লাইভ সক্রিয়' : '🔴 অফ (Coming Soon মোড)' }}
            </span>
          </h3>
          <p style="margin: 0; font-size: 13px; color: #94a3b8; max-width: 620px; line-height: 1.5;">
            <strong>লাইভ (অন)</strong> থাকলে সমস্ত ভিজিটর ওয়েবসাইট দেখতে পাবে। <strong>অফ (Coming Soon)</strong> করলে সাধারণ ভিজিটরদের জন্য সাইট বন্ধ থাকবে এবং তারা <em>'শীঘ্রই আসছি'</em> পেজ দেখতে পাবে (লগইন করা এডমিনরা যথারীতি সাইট ও এডমিন প্যানেল দেখতে পারবেন)।
          </p>
        </div>
        <div>
          <select name="site_live_mode" id="site_live_mode" class="form-control" style="font-weight: 600; min-width: 220px; padding: 10px 14px; border: 1px solid {{ ($settings['site_live_mode'] ?? '1') === '1' ? '#22c55e' : '#ef4444' }}; background: #0f172a; color: {{ ($settings['site_live_mode'] ?? '1') === '1' ? '#4ade80' : '#f87171' }};">
            <option value="1" {{ ($settings['site_live_mode'] ?? '1') === '1' ? 'selected' : '' }}>🟢 Live (অন - সবার জন্য উন্মুক্ত)</option>
            <option value="0" {{ ($settings['site_live_mode'] ?? '1') === '0' ? 'selected' : '' }}>🔴 Maintenance (অফ - কামিং সুন পেজ)</option>
          </select>
        </div>
      </div>

      <!-- Additional Coming Soon Options (Collapsible / Toggleable) -->
      <div id="coming-soon-options" style="margin-top: 18px; pt-3; border-top: 1px dashed rgba(255,255,255,0.1); padding-top: 14px; {{ ($settings['site_live_mode'] ?? '1') === '1' ? 'display: none;' : '' }}">
        <h4 style="font-size: 14px; color: #f87171; margin-bottom: 12px;">
          <i class="fas fa-wrench"></i> Coming Soon পেজ সেটিংস
        </h4>
        <div class="form-group" style="margin-bottom: 12px;">
          <label for="coming_soon_title" class="form-label">Coming Soon Title (শিরোনাম)</label>
          <input type="text" name="coming_soon_title" id="coming_soon_title" class="form-control" value="{{ old('coming_soon_title', $settings['coming_soon_title'] ?? 'আমাদের ওয়েবসাইট খুব শীঘ্রই চালু হচ্ছে!') }}" placeholder="যেমন: আমাদের ওয়েবসাইট খুব শীঘ্রই চালু হচ্ছে!">
        </div>
        <div class="form-group" style="margin-bottom: 0;">
          <label for="coming_soon_description" class="form-label">Coming Soon Description (বিস্তারিত বার্তা)</label>
          <textarea name="coming_soon_description" id="coming_soon_description" class="form-control" rows="2" placeholder="আমাদের ওয়েবসাইট প্রস্তুত করার কাজ চলছে...">{{ old('coming_soon_description', $settings['coming_soon_description'] ?? 'আমাদের ওয়েবসাইট প্রস্তুত করার কাজ চলছে। সর্বশেষ আপডেট পেতে এবং ওয়েবসাইট চালু হওয়ার সাথে সাথে জানতে আপনার ইমেল দিয়ে সাবস্ক্রাইব করুন।') }}</textarea>
        </div>
      </div>
    </div>

    <!-- General Settings Section -->
    <div style="margin-bottom: 24px; border-bottom: 1px solid var(--border-color); padding-bottom: 20px;">
      <h3 class="card-title" style="font-size: 16px; color: #38bdf8; margin-bottom: 16px;">
        <i class="fas fa-sliders-h"></i> General Settings
      </h3>
      
      <div class="form-group">
        <label for="site_name" class="form-label">Site Name</label>
        <input type="text" name="site_name" id="site_name" class="form-control" value="{{ old('site_name', $settings['site_name'] ?? 'News 1') }}" required>
      </div>

      <div class="form-group">
        <label for="footer_about" class="form-label">About Us (Footer Description)</label>
        <textarea name="footer_about" id="footer_about" class="form-control">{{ old('footer_about', $settings['footer_about'] ?? '') }}</textarea>
      </div>

      <div class="form-group">
        <label for="default_author_name" class="form-label">Default Author / Reporter Name (ডিফল্ট রিপোর্টার / লেখকের নাম)</label>
        <input type="text" name="default_author_name" id="default_author_name" class="form-control" value="{{ old('default_author_name', $settings['default_author_name'] ?? (auth()->user()->name ?? 'নিউজ১ এডমিন')) }}" placeholder="যেমন: নিউজ১ স্পেশাল ডেস্ক / আপনার নাম">
        <small style="color: #94a3b8; display: block; margin-top: 4px;">এডমিন প্যানেলে সংবাদ সেভ করলে বা ফ্রন্টএন্ডে দেখানোর সময় এই রিপোর্টার/লেখকের নাম প্রদর্শিত হবে।</small>
      </div>

      <div class="form-group">
        <label for="hero_video_url" class="form-label">Hero Live Bulletin Video URL (হোমপেজ প্রধান ভিডিও লিংক)</label>
        <input type="url" name="hero_video_url" id="hero_video_url" class="form-control" value="{{ old('hero_video_url', $settings['hero_video_url'] ?? 'https://www.youtube.com/embed/dQw4w9WgXcQ') }}" placeholder="https://www.youtube.com/watch?v=... বা https://www.youtube.com/embed/...">
        <small style="color: #94a3b8; display: block; margin-top: 4px;">যেকোনো সাধারণ ইউটিউব খবরের ভিডিও বা লাইভ ভিডিও লিংক দেওয়া যাবে (যেমন: https://www.youtube.com/watch?v=VIDEO_ID)।</small>
      </div>
    </div>


    <!-- Social Links Section -->
    <div style="margin-bottom: 24px; border-bottom: 1px solid var(--border-color); padding-bottom: 20px;">
      <h3 class="card-title" style="font-size: 16px; color: #38bdf8; margin-bottom: 16px;">
        <i class="fas fa-share-nodes"></i> Social Network Links
      </h3>
      
      <div class="form-row form-row-2">
        <div class="form-group">
          <label for="facebook_url" class="form-label">Facebook URL</label>
          <input type="url" name="facebook_url" id="facebook_url" class="form-control" value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}">
        </div>
        <div class="form-group">
          <label for="youtube_url" class="form-label">YouTube URL</label>
          <input type="url" name="youtube_url" id="youtube_url" class="form-control" value="{{ old('youtube_url', $settings['youtube_url'] ?? '') }}">
        </div>
      </div>

      <div class="form-row form-row-3">
        <div class="form-group">
          <label for="twitter_url" class="form-label">Twitter / X URL</label>
          <input type="url" name="twitter_url" id="twitter_url" class="form-control" value="{{ old('twitter_url', $settings['twitter_url'] ?? '') }}">
        </div>
        <div class="form-group">
          <label for="instagram_url" class="form-label">Instagram URL</label>
          <input type="url" name="instagram_url" id="instagram_url" class="form-control" value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}">
        </div>
        <div class="form-group">
          <label for="whatsapp_url" class="form-label">WhatsApp Channel URL</label>
          <input type="url" name="whatsapp_url" id="whatsapp_url" class="form-control" value="{{ old('whatsapp_url', $settings['whatsapp_url'] ?? '') }}">
        </div>
      </div>
    </div>

    <!-- Weather configurations -->
    <div style="margin-bottom: 24px; padding-bottom: 10px;">
      <h3 class="card-title" style="font-size: 16px; color: #38bdf8; margin-bottom: 16px;">
        <i class="fas fa-cloud-sun-rain"></i> Weather Widget Settings
      </h3>

      <div class="form-row form-row-2" style="margin-bottom: 15px;">
        <div class="form-group">
          <label class="form-check" style="margin-top: 15px;">
            <input type="checkbox" name="weather_auto_fetch" id="weather_auto_fetch" value="1" {{ ($settings['weather_auto_fetch'] ?? '0') === '1' ? 'checked' : '' }}>
            <span>Auto-fetch Weather (ওয়েদার অটো-ফেচ করুন)</span>
          </label>
        </div>
        
        <div class="form-group">
          <label for="weather_api_key" class="form-label">WeatherAPI API Key (ওয়েদার এপিআই কী)</label>
          <div style="display: flex; gap: 10px;">
            <input type="text" name="weather_api_key" id="weather_api_key" class="form-control" value="{{ old('weather_api_key', $settings['weather_api_key'] ?? '') }}" placeholder="Enter WeatherAPI Key">
            <button type="button" id="test-weather-api" class="btn-admin btn-admin-secondary" style="white-space: nowrap; padding: 0 16px;">Test Connection</button>
          </div>
        </div>
      </div>
      
      <div class="form-row form-row-3">
        <div class="form-group">
          <label for="weather_location" class="form-label">Weather Location</label>
          <input type="text" name="weather_location" id="weather_location" class="form-control" value="{{ old('weather_location', $settings['weather_location'] ?? 'Kolkata, West Bengal') }}">
        </div>
        
        <div class="form-group">
          <label for="weather_temp" class="form-label">Temperature (°C - numbers only)</label>
          <input type="text" name="weather_temp" id="weather_temp" class="form-control" value="{{ old('weather_temp', $settings['weather_temp'] ?? '38') }}">
        </div>
        
        <div class="form-group">
          <label for="weather_desc" class="form-label">Weather Condition</label>
          <input type="text" name="weather_desc" id="weather_desc" class="form-control" value="{{ old('weather_desc', $settings['weather_desc'] ?? 'Partly Cloudy') }}">
        </div>
      </div>

      <div class="form-row form-row-2">
        <div class="form-group">
          <label for="weather_humidity" class="form-label">Humidity (e.g. 72%)</label>
          <input type="text" name="weather_humidity" id="weather_humidity" class="form-control" value="{{ old('weather_humidity', $settings['weather_humidity'] ?? '') }}">
        </div>
        
        <div class="form-group">
          <label for="weather_wind" class="form-label">Wind Speed (e.g. 18 km/h)</label>
          <input type="text" name="weather_wind" id="weather_wind" class="form-control" value="{{ old('weather_wind', $settings['weather_wind'] ?? '') }}">
        </div>
      </div>

      <div class="form-row form-row-2">
        <div class="form-group">
          <label for="weather_high" class="form-label">Max Temperature (e.g. 41°C)</label>
          <input type="text" name="weather_high" id="weather_high" class="form-control" value="{{ old('weather_high', $settings['weather_high'] ?? '') }}">
        </div>
        
        <div class="form-group">
          <label for="weather_low" class="form-label">Min Temperature (e.g. 28°C)</label>
          <input type="text" name="weather_low" id="weather_low" class="form-control" value="{{ old('weather_low', $settings['weather_low'] ?? '') }}">
        </div>
      </div>
    </div>

    <!-- Article View Count Settings Section -->
    <div id="article-view-settings" style="margin-bottom: 24px; border-bottom: 1px solid var(--border-color); padding-bottom: 20px;">
      <h3 class="card-title" style="font-size: 16px; color: #38bdf8; margin-bottom: 16px;">
        <i class="fas fa-eye"></i> Article View Count Settings
      </h3>
      
      <div class="form-row form-row-2">
        <div class="form-group">
          <label class="form-check" style="margin-top: 15px;">
            <input type="checkbox" name="hide_view_count" id="hide_view_count" value="1" {{ ($settings['hide_view_count'] ?? '0') === '1' ? 'checked' : '' }}>
            <span>Hide View Count on Frontend (ফ্রন্টএন্ডে ভিউ কাউন্ট লুকান)</span>
          </label>
        </div>
        
        <div class="form-group">
          <label for="view_count_offset" class="form-label">View Count Increase Offset (ভিউ কাউন্ট বৃদ্ধির মান)</label>
          <input type="number" name="view_count_offset" id="view_count_offset" class="form-control" value="{{ old('view_count_offset', $settings['view_count_offset'] ?? '200') }}" min="0" placeholder="e.g. 200">
        </div>
      </div>
    </div>

    <!-- Gold, Silver & Sensex Market Rates Settings Section -->
    <div id="market-rates-settings" style="margin-bottom: 24px; border-bottom: 1px solid var(--border-color); padding-bottom: 20px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
        <div>
          <h3 class="card-title" style="font-size: 16px; color: #eab308; margin: 0; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-coins"></i> সোনা, রূপো ও সেনসেক্স বাজার দর (Market Rates)
          </h3>
          <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px; margin-bottom: 0;">
            প্রতিদিন সকাল ০৯:৩০ ও বিকাল ০৪:৪৫ মিনিটে স্বয়ংক্রিয়ভাবে সোনার দর, রূপোর দর ও সেনসেক্স-নিফটি লাইভ ডাটা ফেচ হয়।
          </p>
        </div>
        <div style="display: flex; gap: 8px;">
          <a href="{{ route('market.rates') }}" target="_blank" class="btn-admin btn-admin-secondary" style="padding: 6px 14px; font-size: 12px;">
            <i class="fas fa-external-link-alt"></i> লাইভ পেজ দেখুন
          </a>
        </div>
      </div>

      @php
        $mRates = \App\Http\Controllers\MarketController::getMarketData();
      @endphp

      <!-- Current Rates Quick Badge Grid -->
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; margin-bottom: 16px; background: rgba(0, 0, 0, 0.25); padding: 14px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.08);">
        <div>
          <div style="font-size: 11px; color: var(--text-muted);">২৪K সোনা (১০ গ্রাম)</div>
          <div style="font-size: 18px; font-weight: 800; color: #eab308; margin-top: 2px;">₹{{ $mRates['gold_24k_formatted'] ?? '৭৬,৮৫০' }}</div>
          <div style="font-size: 11px; color: #94a3b8;">{{ $mRates['gold_change_formatted'] ?? '+৩৫০' }}</div>
        </div>
        <div>
          <div style="font-size: 11px; color: var(--text-muted);">২২K সোনা (১০ গ্রাম)</div>
          <div style="font-size: 18px; font-weight: 800; color: #f59e0b; margin-top: 2px;">₹{{ $mRates['gold_22k_formatted'] ?? '৭০,৪৫০' }}</div>
          <div style="font-size: 11px; color: #94a3b8;">১ ভরি: ₹{{ $mRates['gold_22k_bhori_formatted'] ?? '৫৬,৩৬০' }}</div>
        </div>
        <div>
          <div style="font-size: 11px; color: var(--text-muted);">রূপো (১ কেজি)</div>
          <div style="font-size: 18px; font-weight: 800; color: #cbd5e1; margin-top: 2px;">₹{{ $mRates['silver_1kg_formatted'] ?? '৯৪,৫০০' }}</div>
          <div style="font-size: 11px; color: #94a3b8;">১০ গ্রাম: ₹{{ $mRates['silver_10g_formatted'] ?? '৯৪৫' }}</div>
        </div>
        <div>
          <div style="font-size: 11px; color: var(--text-muted);">BSE সেনসেক্স</div>
          <div style="font-size: 18px; font-weight: 800; color: #60a5fa; margin-top: 2px;">{{ $mRates['sensex']['price_formatted'] ?? '৭৭,১৫০' }}</div>
          <div style="font-size: 11px; color: {{ ($mRates['sensex']['is_positive'] ?? true) ? '#34d399' : '#f87171' }};">{{ $mRates['sensex']['change_percent_formatted'] ?? '+০.৪৪%' }}</div>
        </div>
        <div>
          <div style="font-size: 11px; color: var(--text-muted);">সর্বশেষ আপডেট</div>
          <div style="font-size: 13px; font-weight: 700; color: #ffffff; margin-top: 4px;">{{ $mRates['last_updated_bn'] ?? 'আজ' }}</div>
          <div style="font-size: 11px; color: #34d399;">ডেলি রুটিন সক্রিয়</div>
        </div>
      </div>

      <div class="form-row form-row-2">
        <div class="form-group">
          <label for="market_base_gold_24k" class="form-label">২৪K সোনার বেস রেট (১০ গ্রাম)</label>
          <input type="number" name="market_base_gold_24k" id="market_base_gold_24k" class="form-control" value="{{ old('market_base_gold_24k', $settings['market_base_gold_24k'] ?? '76850') }}" placeholder="76850">
          <div style="font-size: 11px; color: var(--text-muted); margin-top: 3px;">কলকাতার স্থানীয় জুয়েলার্স বেঞ্চমার্ক। লাইভ মার্কেট ওঠানামা এই মূল্যের সাথে সিঙ্ক হয়।</div>
        </div>

        <div class="form-group">
          <label for="market_base_silver_1kg" class="form-label">রূপোর বেস রেট (১ কেজি)</label>
          <input type="number" name="market_base_silver_1kg" id="market_base_silver_1kg" class="form-control" value="{{ old('market_base_silver_1kg', $settings['market_base_silver_1kg'] ?? '94500') }}" placeholder="94500">
          <div style="font-size: 11px; color: var(--text-muted); margin-top: 3px;">১ কেজি রূপোর বারের বেস রেট।</div>
        </div>
      </div>
    </div>

    <!-- Submit Form -->
    <div style="margin-top: 24px; display: flex; gap: 12px; justify-content: space-between; align-items: center; flex-wrap: wrap; border-top: 1px solid var(--border-color); padding-top: 20px;">
      <div>
        <!-- Separate form for immediate market refresh -->
        <button type="submit" form="refresh-market-form" class="btn-admin btn-admin-secondary" style="background: rgba(234, 179, 8, 0.15); border-color: rgba(234, 179, 8, 0.4); color: #facc15;">
          <i class="fas fa-sync-alt"></i> এখনই মার্কেট রেট ফেচ করুন (Fetch Live Now)
        </button>
      </div>
      <div>
        <button type="submit" class="btn-admin btn-admin-primary">Save All Settings</button>
      </div>
    </div>
  </form>

  <!-- Hidden form to trigger market rates refresh without altering other unsaved fields -->
  <form id="refresh-market-form" action="{{ route('admin.settings.refresh_market') }}" method="POST" style="display: none;">
    @csrf
  </form>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Live mode toggle interaction
    const liveModeSelect = document.getElementById('site_live_mode');
    const comingSoonOptions = document.getElementById('coming-soon-options');
    if (liveModeSelect && comingSoonOptions) {
      liveModeSelect.addEventListener('change', () => {
        if (liveModeSelect.value === '0') {
          comingSoonOptions.style.display = 'block';
          liveModeSelect.style.borderColor = '#ef4444';
          liveModeSelect.style.color = '#f87171';
        } else {
          comingSoonOptions.style.display = 'none';
          liveModeSelect.style.borderColor = '#22c55e';
          liveModeSelect.style.color = '#4ade80';
        }
      });
    }

    const toggle = document.getElementById('weather_auto_fetch');
    const manualFields = [
      document.getElementById('weather_temp'),
      document.getElementById('weather_desc'),
      document.getElementById('weather_humidity'),
      document.getElementById('weather_wind'),
      document.getElementById('weather_high'),
      document.getElementById('weather_low')
    ];
    
    const updateFieldsState = () => {
      if (!toggle) return;
      const isAuto = toggle.checked;
      manualFields.forEach(field => {
        if (field) {
          field.readOnly = isAuto;
          if (isAuto) {
            field.style.opacity = '0.5';
            field.style.cursor = 'not-allowed';
          } else {
            field.style.opacity = '1';
            field.style.cursor = 'text';
          }
        }
      });
    };
    
    if (toggle) {
      toggle.addEventListener('change', updateFieldsState);
      updateFieldsState();
    }

    const showToast = (type, title, message) => {
      const existing = document.getElementById('alert-toast');
      if (existing) {
        existing.remove();
      }
      
      const toast = document.createElement('div');
      toast.className = 'alert-toast';
      toast.id = 'alert-toast';
      if (type === 'error') {
        toast.style.borderLeftColor = 'var(--danger)';
      }
      
      const icon = document.createElement('i');
      icon.className = type === 'error' ? 'fas fa-exclamation-circle' : 'fas fa-check-circle';
      icon.style.color = type === 'error' ? 'var(--danger)' : 'var(--success)';
      icon.style.fontSize = '20px';
      
      const content = document.createElement('div');
      content.innerHTML = `
        <div style="font-weight: 600; font-size: 14px;">${title}</div>
        <div style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">${message}</div>
      `;
      
      toast.appendChild(icon);
      toast.appendChild(content);
      document.body.appendChild(toast);
      
      setTimeout(() => {
        toast.style.animation = 'slideIn 0.3s ease reverse forwards';
        setTimeout(() => toast.remove(), 300);
      }, 4000);
    };

    const testBtn = document.getElementById('test-weather-api');
    if (testBtn) {
      testBtn.addEventListener('click', () => {
        const apiKey = document.getElementById('weather_api_key').value;
        const location = document.getElementById('weather_location').value;
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

        if (!apiKey) {
          showToast('error', 'Error!', 'Please enter an API Key first.');
          return;
        }

        testBtn.disabled = true;
        testBtn.innerText = 'Testing...';

        fetch('{{ route("admin.settings.test_weather_api") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({ api_key: apiKey, location: location })
        })
        .then(response => response.json())
        .then(data => {
          testBtn.disabled = false;
          testBtn.innerText = 'Test Connection';

          if (data.success) {
            showToast('success', 'Success!', data.message);
            if (data.data) {
              document.getElementById('weather_temp').value = data.data.temp;
              document.getElementById('weather_desc').value = data.data.desc;
              document.getElementById('weather_humidity').value = data.data.humidity;
              document.getElementById('weather_wind').value = data.data.wind;
              document.getElementById('weather_high').value = data.data.high;
              document.getElementById('weather_low').value = data.data.low;
              
              updateFieldsState();
            }
          } else {
            showToast('error', 'Error!', data.message);
          }
        })
        .catch(error => {
          testBtn.disabled = false;
          testBtn.innerText = 'Test Connection';
          showToast('error', 'Error!', 'An error occurred during verification.');
          console.error(error);
        });
      });
    }
  });
</script>
@endsection
