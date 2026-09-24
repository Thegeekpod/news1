@extends('layouts.admin')

@section('title', 'Sitemap Control')
@section('header_title', 'Sitemap Control Module')

@section('styles')
<style>
  .sitemap-tab-btn {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: var(--text-muted);
    padding: 10px 18px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }
  .sitemap-tab-btn:hover {
    background: rgba(255, 255, 255, 0.09);
    color: #ffffff;
  }
  .sitemap-tab-btn.active {
    background: linear-gradient(135deg, #4f46e5, #3b82f6);
    border-color: #6366f1;
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
  }
  .sitemap-tab-pane {
    display: none;
    animation: fadeIn 0.2s ease-in-out;
  }
  .sitemap-tab-pane.active {
    display: block;
  }
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .toggle-switch {
    position: relative;
    display: inline-block;
    width: 46px;
    height: 24px;
    flex-shrink: 0;
  }
  .toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }
  .toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #334155;
    transition: .3s;
    border-radius: 24px;
  }
  .toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .3s;
    border-radius: 50%;
  }
  input:checked + .toggle-slider {
    background-color: #10b981;
  }
  input:checked + .toggle-slider:before {
    transform: translateX(22px);
  }
  .control-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.07);
    border-radius: 10px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    transition: border-color 0.2s;
  }
  .control-card:hover {
    border-color: rgba(99, 102, 241, 0.3);
  }
</style>
@endsection

@section('content')

<!-- Header Overview Card -->
<div class="admin-card" style="margin-bottom: 24px; background: linear-gradient(145deg, rgba(30, 41, 59, 0.9), rgba(15, 23, 42, 0.95)); border: 1px solid rgba(99, 102, 241, 0.35);">
  <div class="card-header-flex" style="margin-bottom: 20px; align-items: flex-start;">
    <div>
      <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
        <h2 class="card-title" style="margin: 0; display: flex; align-items: center; gap: 10px; font-size: 22px;">
          <i class="fas fa-sitemap" style="color: #818cf8;"></i> Single Sitemap Control Panel
        </h2>
        @if(($settings['sitemap_enabled'] ?? '1') === '1')
          <span style="background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.4); font-size: 11px; padding: 4px 10px; border-radius: 999px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
            <span style="width: 7px; height: 7px; background: #10b981; border-radius: 50%; display: inline-block; box-shadow: 0 0 8px #10b981;"></span>
            Sitemap Active &amp; Dynamic
          </span>
        @else
          <span style="background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.4); font-size: 11px; padding: 4px 10px; border-radius: 999px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
            <span style="width: 7px; height: 7px; background: #ef4444; border-radius: 50%; display: inline-block;"></span>
            Sitemap Disabled
          </span>
        @endif
        <span style="background: rgba(59, 130, 246, 0.15); color: #93c5fd; border: 1px solid rgba(59, 130, 246, 0.3); font-size: 11px; padding: 4px 10px; border-radius: 999px; font-weight: 600;">
          <i class="fas fa-check"></i> Single Unified XML (/sitemap-1.xml)
        </span>
      </div>
      <p style="font-size: 13px; color: var(--text-muted); margin-top: 8px; max-width: 750px; line-height: 1.5;">
        All website URLs (articles, categories, tags, and custom pages) are unified into one single XML sitemap at <code>/sitemap-1.xml</code>. No multiple sitemaps needed.
      </p>
    </div>

    <!-- Quick action buttons -->
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
      <button type="button" onclick="testSitemap()" class="btn-admin btn-admin-primary" style="background: #2563eb; border-color: #3b82f6;">
        <i class="fas fa-stethoscope"></i> Test &amp; Validate XML
      </button>

      <form action="{{ route('admin.sitemap.clear_cache') }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" class="btn-admin btn-admin-secondary" title="Flush sitemap cache">
          <i class="fas fa-sync-alt"></i> Clear Cache
        </button>
      </form>

      <a href="{{ route('sitemap.index') }}" target="_blank" class="btn-admin btn-admin-secondary">
        <i class="fas fa-external-link-alt"></i> View XML
      </a>
    </div>
  </div>

  <!-- Real-time Stats Grid -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 14px;">
    <div style="background: rgba(255, 255, 255, 0.04); padding: 14px 16px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.08);">
      <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Indexed Articles</div>
      <div style="font-size: 24px; font-weight: 800; color: #60a5fa; margin-top: 4px;">{{ number_format($articlesCount) }}</div>
      <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">{{ $articlesWithImageCount }} with image tags</div>
    </div>

    <div style="background: rgba(255, 255, 255, 0.04); padding: 14px 16px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.08);">
      <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Categories</div>
      <div style="font-size: 24px; font-weight: 800; color: #f59e0b; margin-top: 4px;">{{ number_format($categoriesCount) }}</div>
      <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">{{ count($excludedCategories) }} Excluded</div>
    </div>

    <div style="background: rgba(255, 255, 255, 0.04); padding: 14px 16px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.08);">
      <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Tags &amp; SEO URLs</div>
      <div style="font-size: 24px; font-weight: 800; color: #a855f7; margin-top: 4px;">{{ number_format($tagsCount + $seoCount + count($customUrls)) }}</div>
      <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">{{ $tagsCount }} tags, {{ $seoCount }} SEO, {{ count($customUrls) }} custom</div>
    </div>

    <div style="background: rgba(255, 255, 255, 0.04); padding: 14px 16px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.08);">
      <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Cache Duration</div>
      <div style="font-size: 24px; font-weight: 800; color: #38bdf8; margin-top: 4px;">
        {{ ($settings['sitemap_cache_duration'] ?? '60') === '0' ? 'Real-time' : ($settings['sitemap_cache_duration'] ?? '60') . 'm' }}
      </div>
      <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">
        {{ ($settings['sitemap_cache_duration'] ?? '60') === '0' ? 'No cache overhead' : 'Fast cached XML' }}
      </div>
    </div>
  </div>
</div>

<!-- Single Sitemap Live Endpoint Card -->
<div class="admin-card" style="margin-bottom: 24px;">
  <div class="control-card" style="background: rgba(255, 255, 255, 0.04); border-color: rgba(99, 102, 241, 0.3);">
    <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
      <div style="width: 44px; height: 44px; background: rgba(99, 102, 241, 0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #818cf8; font-size: 20px;">
        <i class="fas fa-sitemap"></i>
      </div>
      <div>
        <div style="display: flex; align-items: center; gap: 8px;">
          <strong style="color: #ffffff; font-size: 15px;">Single Unified Sitemap:</strong>
          @if(($settings['sitemap_enabled'] ?? '1') === '1')
            <span class="badge badge-success">Active</span>
          @else
            <span class="badge badge-danger">Disabled</span>
          @endif
        </div>
        <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">
          <code style="font-size: 13px; color: #93c5fd;">{{ route('sitemap.index') }}</code>
        </div>
      </div>
    </div>
    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
      <button type="button" onclick="testSitemap()" class="btn-admin btn-admin-secondary" style="padding: 7px 14px; font-size: 12px;">
        <i class="fas fa-vial"></i> Test Now
      </button>
      <button type="button" onclick="copyToClipboard('{{ route('sitemap.index') }}')" class="btn-admin btn-admin-secondary" style="padding: 7px 14px; font-size: 12px;">
        <i class="fas fa-copy"></i> Copy URL
      </button>
      <a href="{{ route('sitemap.index') }}" target="_blank" class="btn-admin btn-admin-primary" style="padding: 7px 14px; font-size: 12px;">
        <i class="fas fa-external-link-alt"></i> Open Live XML
      </a>
    </div>
  </div>

  <!-- Test Results Panel -->
  <div id="test-results-panel" style="display: none; margin-top: 18px; padding: 16px; border-radius: 8px; background: rgba(0, 0, 0, 0.4); border: 1px solid rgba(255, 255, 255, 0.1);">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
      <h4 style="margin: 0; font-size: 14px; color: #60a5fa; display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-check-circle"></i> XML Validation &amp; Diagnostic Result
      </h4>
      <button type="button" onclick="document.getElementById('test-results-panel').style.display='none'" class="btn-admin btn-admin-secondary" style="padding: 2px 8px; font-size: 11px;">Close</button>
    </div>
    <div id="test-results-content"></div>
  </div>
</div>

<!-- Main Settings Form & Module Tabs -->
<form action="{{ route('admin.sitemap.update') }}" method="POST" id="sitemap-settings-form">
  @csrf

  <!-- Tab Navigation -->
  <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px;">
    <button type="button" class="sitemap-tab-btn active" data-tab="tab-general">
      <i class="fas fa-toggle-on"></i> Content Inclusions
    </button>
    <button type="button" class="sitemap-tab-btn" data-tab="tab-priorities">
      <i class="fas fa-tachometer-alt"></i> Priorities &amp; Frequencies
    </button>
    <button type="button" class="sitemap-tab-btn" data-tab="tab-exclusions">
      <i class="fas fa-ban"></i> Blacklist &amp; Exclusions
    </button>
    <button type="button" class="sitemap-tab-btn" data-tab="tab-custom-urls">
      <i class="fas fa-link"></i> Custom Extra URLs
    </button>
    <button type="button" class="sitemap-tab-btn" data-tab="tab-robots">
      <i class="fas fa-robot"></i> Robots.txt &amp; Submission
    </button>
  </div>

  <!-- TAB 1: General & Inclusions -->
  <div id="tab-general" class="sitemap-tab-pane active admin-card">
    <div class="card-header-flex" style="margin-bottom: 20px;">
      <div>
        <h3 class="card-title" style="margin: 0; font-size: 18px;">
          <i class="fas fa-toggle-on" style="color: #6366f1;"></i> Content Inclusions in Unified Sitemap
        </h3>
        <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">
          Control which sections and elements are dynamically included in your <code>/sitemap.xml</code>.
        </p>
      </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr; gap: 14px; margin-bottom: 24px;">
      <!-- Master Sitemap Toggle -->
      <div class="control-card">
        <div>
          <strong style="color: #ffffff; font-size: 14px;">Enable Unified Sitemap (/sitemap.xml)</strong>
          <p style="font-size: 12px; color: var(--text-muted); margin: 3px 0 0 0;">
            Master switch for the XML sitemap. If turned off, <code>/sitemap.xml</code> returns 404.
          </p>
        </div>
        <label class="toggle-switch">
          <input type="checkbox" name="sitemap_enabled" value="1" {{ ($settings['sitemap_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
          <span class="toggle-slider"></span>
        </label>
      </div>

      <!-- Articles Inclusion -->
      <div class="control-card">
        <div>
          <strong style="color: #ffffff; font-size: 14px;">Include Published Articles</strong>
          <p style="font-size: 12px; color: var(--text-muted); margin: 3px 0 0 0;">
            Includes all published blog posts and news articles in the sitemap.
          </p>
        </div>
        <label class="toggle-switch">
          <input type="checkbox" name="sitemap_articles_enabled" value="1" {{ ($settings['sitemap_articles_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
          <span class="toggle-slider"></span>
        </label>
      </div>

      <!-- Categories Inclusion -->
      <div class="control-card">
        <div>
          <strong style="color: #ffffff; font-size: 14px;">Include News Categories</strong>
          <p style="font-size: 12px; color: var(--text-muted); margin: 3px 0 0 0;">
            Includes category archive URLs (e.g. <code>/category/politics</code>).
          </p>
        </div>
        <label class="toggle-switch">
          <input type="checkbox" name="sitemap_categories_enabled" value="1" {{ ($settings['sitemap_categories_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
          <span class="toggle-slider"></span>
        </label>
      </div>

      <!-- Tags Inclusion -->
      <div class="control-card">
        <div>
          <strong style="color: #ffffff; font-size: 14px;">Include Tags</strong>
          <p style="font-size: 12px; color: var(--text-muted); margin: 3px 0 0 0;">
            Includes tag archive URLs (e.g. <code>/tag/election</code>).
          </p>
        </div>
        <label class="toggle-switch">
          <input type="checkbox" name="sitemap_tags_enabled" value="1" {{ ($settings['sitemap_tags_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
          <span class="toggle-slider"></span>
        </label>
      </div>

      <!-- Custom SEO Pages Inclusion -->
      <div class="control-card">
        <div>
          <strong style="color: #ffffff; font-size: 14px;">Include Custom SEO Pages</strong>
          <p style="font-size: 12px; color: var(--text-muted); margin: 3px 0 0 0;">
            Includes URLs configured in the SEO Details manager.
          </p>
        </div>
        <label class="toggle-switch">
          <input type="checkbox" name="sitemap_custom_pages_enabled" value="1" {{ ($settings['sitemap_custom_pages_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
          <span class="toggle-slider"></span>
        </label>
      </div>

      <!-- Image Tags in XML -->
      <div class="control-card">
        <div>
          <strong style="color: #ffffff; font-size: 14px;">Include Article Featured Images (&lt;image:image&gt;)</strong>
          <p style="font-size: 12px; color: var(--text-muted); margin: 3px 0 0 0;">
            Embeds article thumbnail images into XML for Google Image Search indexing.
          </p>
        </div>
        <label class="toggle-switch">
          <input type="checkbox" name="sitemap_include_images" value="1" {{ ($settings['sitemap_include_images'] ?? '1') === '1' ? 'checked' : '' }}>
          <span class="toggle-slider"></span>
        </label>
      </div>

      <!-- ASCII Slugs Strict Filter -->
      <div class="control-card">
        <div>
          <strong style="color: #ffffff; font-size: 14px;">Clean ASCII Slugs Only (Recommended)</strong>
          <p style="font-size: 12px; color: var(--text-muted); margin: 3px 0 0 0;">
            Prevents XML encoding conflicts with percent-encoded non-ASCII Bengali slug characters.
          </p>
        </div>
        <label class="toggle-switch">
          <input type="checkbox" name="sitemap_ascii_slugs_only" value="1" {{ ($settings['sitemap_ascii_slugs_only'] ?? '1') === '1' ? 'checked' : '' }}>
          <span class="toggle-slider"></span>
        </label>
      </div>

      <!-- Exclude Empty Categories -->
      <div class="control-card">
        <div>
          <strong style="color: #ffffff; font-size: 14px;">Exclude Empty Categories</strong>
          <p style="font-size: 12px; color: var(--text-muted); margin: 3px 0 0 0;">
            Omit categories that have 0 published articles.
          </p>
        </div>
        <label class="toggle-switch">
          <input type="checkbox" name="sitemap_exclude_empty_categories" value="1" {{ ($settings['sitemap_exclude_empty_categories'] ?? '0') === '1' ? 'checked' : '' }}>
          <span class="toggle-slider"></span>
        </label>
      </div>

      <!-- Exclude Empty Tags -->
      <div class="control-card">
        <div>
          <strong style="color: #ffffff; font-size: 14px;">Exclude Empty Tags</strong>
          <p style="font-size: 12px; color: var(--text-muted); margin: 3px 0 0 0;">
            Omit tags that have 0 published articles.
          </p>
        </div>
        <label class="toggle-switch">
          <input type="checkbox" name="sitemap_exclude_empty_tags" value="1" {{ ($settings['sitemap_exclude_empty_tags'] ?? '0') === '1' ? 'checked' : '' }}>
          <span class="toggle-slider"></span>
        </label>
      </div>
    </div>

    <!-- Caching Duration Section -->
    <div style="background: rgba(0, 0, 0, 0.25); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 18px;">
      <h4 style="margin: 0 0 8px 0; font-size: 14px; color: #38bdf8;">
        <i class="fas fa-bolt"></i> Sitemap Response Caching
      </h4>
      <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 14px;">
        Caches the rendered XML to serve thousands of crawlers with zero database load. Auto-refreshes whenever articles or settings change.
      </p>
      <div class="form-group" style="margin-bottom: 0; max-width: 320px;">
        <label for="sitemap_cache_duration" class="form-label">Cache Lifetime</label>
        <select name="sitemap_cache_duration" id="sitemap_cache_duration" class="form-control">
          <option value="0" {{ ($settings['sitemap_cache_duration'] ?? '60') === '0' ? 'selected' : '' }}>Real-time (0 minutes / No Cache)</option>
          <option value="15" {{ ($settings['sitemap_cache_duration'] ?? '60') === '15' ? 'selected' : '' }}>15 minutes</option>
          <option value="60" {{ ($settings['sitemap_cache_duration'] ?? '60') === '60' ? 'selected' : '' }}>60 minutes (1 Hour - Recommended)</option>
          <option value="360" {{ ($settings['sitemap_cache_duration'] ?? '60') === '360' ? 'selected' : '' }}>360 minutes (6 Hours)</option>
          <option value="1440" {{ ($settings['sitemap_cache_duration'] ?? '60') === '1440' ? 'selected' : '' }}>1440 minutes (24 Hours)</option>
        </select>
      </div>
    </div>
  </div>

  <!-- TAB 2: Priorities & Frequencies -->
  <div id="tab-priorities" class="sitemap-tab-pane admin-card">
    <div class="card-header-flex" style="margin-bottom: 20px;">
      <div>
        <h3 class="card-title" style="margin: 0; font-size: 18px;">
          <i class="fas fa-tachometer-alt" style="color: #f59e0b;"></i> Crawl Priorities &amp; Change Frequencies
        </h3>
        <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">
          Configure priority weight (0.1 to 1.0) and how often search engines should revisit different sections.
        </p>
      </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 18px;">
      <!-- Homepage Priority & Freq -->
      <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 18px;">
        <h4 style="margin: 0 0 14px 0; font-size: 15px; color: #60a5fa; display: flex; align-items: center; gap: 8px;">
          <i class="fas fa-home"></i> Home &amp; Lead News
        </h4>
        <div class="form-group">
          <label class="form-label">Priority (0.1 - 1.0)</label>
          <select name="sitemap_home_priority" class="form-control">
            @foreach(['1.0', '0.9', '0.8', '0.7', '0.6', '0.5'] as $p)
              <option value="{{ $p }}" {{ ($settings['sitemap_home_priority'] ?? '1.0') == $p ? 'selected' : '' }}>{{ $p }} {{ $p == '1.0' ? '(Highest)' : '' }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label">Change Frequency</label>
          <select name="sitemap_home_freq" class="form-control">
            @foreach(['always', 'hourly', 'daily', 'weekly', 'monthly'] as $freq)
              <option value="{{ $freq }}" {{ ($settings['sitemap_home_freq'] ?? 'always') == $freq ? 'selected' : '' }}>{{ ucfirst($freq) }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <!-- Articles Priority & Freq -->
      <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 18px;">
        <h4 style="margin: 0 0 14px 0; font-size: 15px; color: #34d399; display: flex; align-items: center; gap: 8px;">
          <i class="fas fa-file-invoice"></i> Published Articles
        </h4>
        <div class="form-group">
          <label class="form-label">Priority (0.1 - 1.0)</label>
          <select name="sitemap_articles_priority" class="form-control">
            @foreach(['1.0', '0.9', '0.8', '0.7', '0.6', '0.5', '0.4'] as $p)
              <option value="{{ $p }}" {{ ($settings['sitemap_articles_priority'] ?? '0.7') == $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label">Change Frequency</label>
          <select name="sitemap_articles_freq" class="form-control">
            @foreach(['hourly', 'daily', 'weekly', 'monthly', 'never'] as $freq)
              <option value="{{ $freq }}" {{ ($settings['sitemap_articles_freq'] ?? 'weekly') == $freq ? 'selected' : '' }}>{{ ucfirst($freq) }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <!-- Categories Priority & Freq -->
      <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 18px;">
        <h4 style="margin: 0 0 14px 0; font-size: 15px; color: #fbbf24; display: flex; align-items: center; gap: 8px;">
          <i class="fas fa-folder"></i> News Categories
        </h4>
        <div class="form-group">
          <label class="form-label">Priority (0.1 - 1.0)</label>
          <select name="sitemap_categories_priority" class="form-control">
            @foreach(['1.0', '0.9', '0.8', '0.7', '0.6', '0.5', '0.4'] as $p)
              <option value="{{ $p }}" {{ ($settings['sitemap_categories_priority'] ?? '0.8') == $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label">Change Frequency</label>
          <select name="sitemap_categories_freq" class="form-control">
            @foreach(['hourly', 'daily', 'weekly', 'monthly'] as $freq)
              <option value="{{ $freq }}" {{ ($settings['sitemap_categories_freq'] ?? 'daily') == $freq ? 'selected' : '' }}>{{ ucfirst($freq) }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <!-- Tags Priority & Freq -->
      <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 18px;">
        <h4 style="margin: 0 0 14px 0; font-size: 15px; color: #c084fc; display: flex; align-items: center; gap: 8px;">
          <i class="fas fa-hashtag"></i> Tag Archives
        </h4>
        <div class="form-group">
          <label class="form-label">Priority (0.1 - 1.0)</label>
          <select name="sitemap_tags_priority" class="form-control">
            @foreach(['0.9', '0.8', '0.7', '0.6', '0.5', '0.4', '0.3'] as $p)
              <option value="{{ $p }}" {{ ($settings['sitemap_tags_priority'] ?? '0.6') == $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label">Change Frequency</label>
          <select name="sitemap_tags_freq" class="form-control">
            @foreach(['daily', 'weekly', 'monthly', 'yearly'] as $freq)
              <option value="{{ $freq }}" {{ ($settings['sitemap_tags_freq'] ?? 'weekly') == $freq ? 'selected' : '' }}>{{ ucfirst($freq) }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <!-- Custom SEO Pages Priority & Freq -->
      <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 18px;">
        <h4 style="margin: 0 0 14px 0; font-size: 15px; color: #38bdf8; display: flex; align-items: center; gap: 8px;">
          <i class="fas fa-globe"></i> Custom SEO &amp; Static Pages
        </h4>
        <div class="form-group">
          <label class="form-label">Priority (0.1 - 1.0)</label>
          <select name="sitemap_custom_priority" class="form-control">
            @foreach(['1.0', '0.9', '0.8', '0.7', '0.6', '0.5'] as $p)
              <option value="{{ $p }}" {{ ($settings['sitemap_custom_priority'] ?? '0.8') == $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label">Change Frequency</label>
          <select name="sitemap_custom_freq" class="form-control">
            @foreach(['weekly', 'monthly', 'yearly'] as $freq)
              <option value="{{ $freq }}" {{ ($settings['sitemap_custom_freq'] ?? 'monthly') == $freq ? 'selected' : '' }}>{{ ucfirst($freq) }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
  </div>

  <!-- TAB 3: Blacklist & Exclusions -->
  <div id="tab-exclusions" class="sitemap-tab-pane admin-card">
    <div class="card-header-flex" style="margin-bottom: 20px;">
      <div>
        <h3 class="card-title" style="margin: 0; font-size: 18px;">
          <i class="fas fa-ban" style="color: #ef4444;"></i> Sitemap Blacklist &amp; Exclusions
        </h3>
        <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">
          Selectively hide specific categories, sensitive article slugs, or private URL paths from the sitemap.
        </p>
      </div>
    </div>

    <!-- Category Exclusion Checkboxes -->
    <div style="margin-bottom: 24px;">
      <label class="form-label" style="font-size: 15px; color: #f8fafc; margin-bottom: 10px;">
        <i class="fas fa-folder-minus" style="color: #f87171;"></i> Exclude Specific Categories
      </label>
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 10px; background: rgba(0, 0, 0, 0.25); padding: 16px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.08);">
        @foreach($categories as $cat)
          <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; color: #cbd5e1; font-size: 13px; margin: 0;">
            <input type="checkbox" name="sitemap_excluded_categories[]" value="{{ $cat->id }}" 
                   {{ in_array($cat->id, $excludedCategories) ? 'checked' : '' }} 
                   style="width: 16px; height: 16px; accent-color: #ef4444;">
            <span>{{ $cat->name_en }} ({{ $cat->name_bn }})</span>
            <span style="font-size: 11px; color: var(--text-muted); margin-left: auto;">{{ $cat->articles_count }}</span>
          </label>
        @endforeach
      </div>
      <div style="font-size: 12px; color: var(--text-muted); margin-top: 6px;">
        Checked categories and their category archive URLs will be excluded from the sitemap.
      </div>
    </div>

    <!-- Article Slugs Exclusion -->
    <div class="form-group" style="margin-bottom: 20px;">
      <label for="sitemap_excluded_articles" class="form-label">
        <i class="fas fa-file-excel" style="color: #f87171;"></i> Exclude Article Slugs or IDs
      </label>
      <textarea name="sitemap_excluded_articles" id="sitemap_excluded_articles" class="form-control" rows="3" 
                placeholder="Enter slugs or IDs separated by commas or newlines (e.g. test-article, 42, private-post)">{{ old('sitemap_excluded_articles', $settings['sitemap_excluded_articles'] ?? '') }}</textarea>
      <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
        Articles with these slugs or IDs will be excluded from the sitemap.
      </div>
    </div>

    <!-- URL Paths Exclusion -->
    <div class="form-group" style="margin-bottom: 0;">
      <label for="sitemap_excluded_urls" class="form-label">
        <i class="fas fa-link-slash" style="color: #f87171;"></i> Exclude URL Paths
      </label>
      <textarea name="sitemap_excluded_urls" id="sitemap_excluded_urls" class="form-control" rows="3" 
                placeholder="Enter paths separated by commas or newlines (e.g. /coming-soon, /search, /secret-landing)">{{ old('sitemap_excluded_urls', $settings['sitemap_excluded_urls'] ?? '/coming-soon') }}</textarea>
      <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
        Static or SEO pages matching these paths will be omitted.
      </div>
    </div>
  </div>

  <!-- TAB 4: Custom Extra URLs -->
  <div id="tab-custom-urls" class="sitemap-tab-pane admin-card">
    <div class="card-header-flex" style="margin-bottom: 20px;">
      <div>
        <h3 class="card-title" style="margin: 0; font-size: 18px;">
          <i class="fas fa-link" style="color: #a855f7;"></i> Custom Static Additional URLs
        </h3>
        <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">
          Add arbitrary landing pages, special pages, or external links directly into <code>/sitemap.xml</code>.
        </p>
      </div>
      <button type="button" onclick="addCustomUrlRow()" class="btn-admin btn-admin-primary">
        <i class="fas fa-plus"></i> Add URL Row
      </button>
    </div>

    <div class="table-responsive">
      <table class="admin-table" id="custom-urls-table">
        <thead>
          <tr>
            <th>URL / Path</th>
            <th style="width: 140px;">Priority</th>
            <th style="width: 160px;">Frequency</th>
            <th style="width: 170px;">Last Modified</th>
            <th style="width: 70px; text-align: center;">Action</th>
          </tr>
        </thead>
        <tbody id="custom-urls-tbody">
          @forelse($customUrls as $index => $item)
            <tr>
              <td>
                <input type="text" name="custom_urls[{{ $index }}][url]" class="form-control" value="{{ $item['url'] ?? '' }}" placeholder="e.g. /about-us or https://news1.co.in/contact" required>
              </td>
              <td>
                <select name="custom_urls[{{ $index }}][priority]" class="form-control">
                  @foreach(['1.0', '0.9', '0.8', '0.7', '0.6', '0.5', '0.4', '0.3'] as $p)
                    <option value="{{ $p }}" {{ ($item['priority'] ?? '0.8') == $p ? 'selected' : '' }}>{{ $p }}</option>
                  @endforeach
                </select>
              </td>
              <td>
                <select name="custom_urls[{{ $index }}][changefreq]" class="form-control">
                  @foreach(['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly'] as $f)
                    <option value="{{ $f }}" {{ ($item['changefreq'] ?? 'monthly') == $f ? 'selected' : '' }}>{{ ucfirst($f) }}</option>
                  @endforeach
                </select>
              </td>
              <td>
                <input type="date" name="custom_urls[{{ $index }}][lastmod]" class="form-control" value="{{ $item['lastmod'] ?? now()->toDateString() }}">
              </td>
              <td style="text-align: center;">
                <button type="button" onclick="this.closest('tr').remove()" class="btn-admin btn-admin-danger" style="padding: 6px 10px;">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
          @empty
            <tr id="empty-custom-urls-row">
              <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 24px;">
                No custom URLs added yet. Click <strong>"Add URL Row"</strong> above to inject special pages into the sitemap.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- TAB 5: Robots.txt & Submission -->
  <div id="tab-robots" class="sitemap-tab-pane admin-card">
    <div class="card-header-flex" style="margin-bottom: 20px;">
      <div>
        <h3 class="card-title" style="margin: 0; font-size: 18px;">
          <i class="fas fa-robot" style="color: #38bdf8;"></i> Robots.txt &amp; Search Engine Submission
        </h3>
        <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">
          Search engine crawlers read <code>robots.txt</code> first to locate your single sitemap.
        </p>
      </div>

      <div style="display: flex; gap: 8px;">
        <a href="{{ url('/robots.txt') }}" target="_blank" class="btn-admin btn-admin-secondary">
          <i class="fas fa-external-link-alt"></i> View Live robots.txt
        </a>
      </div>
    </div>

    <!-- Robots Status Alert -->
    <div style="margin-bottom: 20px;">
      <div style="background: {{ $robotsHasMainSitemap ? 'rgba(16, 185, 129, 0.15)' : 'rgba(239, 68, 68, 0.15)' }}; border: 1px solid {{ $robotsHasMainSitemap ? '#10b981' : '#ef4444' }}; padding: 12px 16px; border-radius: 8px; font-size: 13px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; color: {{ $robotsHasMainSitemap ? '#34d399' : '#f87171' }};">
        <div style="display: flex; align-items: center; gap: 8px;">
          <i class="fas {{ $robotsHasMainSitemap ? 'fa-check-circle' : 'fa-times-circle' }}" style="font-size: 16px;"></i>
          <span>Sitemap Directive in robots.txt: <strong>{{ $robotsHasMainSitemap ? 'Present (Active)' : 'Missing' }}</strong></span>
        </div>
        @if(!$robotsHasMainSitemap)
          <form action="{{ route('admin.sitemap.sync_robots') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn-admin btn-admin-primary" style="padding: 4px 12px; font-size: 12px;">
              <i class="fas fa-sync"></i> Auto-Insert Sitemap Directive
            </button>
          </form>
        @endif
      </div>
    </div>

    <div class="form-group" style="margin-bottom: 24px;">
      <label for="robots_content" class="form-label">Robots.txt Content (<code>public/robots.txt</code>)</label>
      <textarea name="robots_content" id="robots_content" class="form-control" rows="6" 
                style="font-family: monospace; font-size: 13px; line-height: 1.6; background: #090d16; color: #a5f3fc;">{{ $robotsContent }}</textarea>
    </div>

    <!-- Search Engine Direct Submission Cards -->
    <h4 style="font-size: 15px; color: #ffffff; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
      <i class="fas fa-paper-plane" style="color: #60a5fa;"></i> Submit Sitemap to Search Engines
    </h4>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">
      <!-- Google Search Console -->
      <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 16px;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
          <i class="fab fa-google" style="color: #ea4335; font-size: 20px;"></i>
          <strong style="color: #ffffff; font-size: 14px;">Google Search Console</strong>
        </div>
        <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 12px; line-height: 1.5;">
          Submit <code>sitemap-1.xml</code> in your Google Search Console to index all your news articles.
        </p>
        <a href="https://search.google.com/search-console/sitemaps" target="_blank" class="btn-admin btn-admin-primary" style="font-size: 12px; padding: 6px 14px;">
          <i class="fas fa-external-link-alt"></i> Open Google Sitemaps Console
        </a>
      </div>

      <!-- Bing Webmaster Tools -->
      <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 16px;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
          <i class="fab fa-microsoft" style="color: #00a4ef; font-size: 20px;"></i>
          <strong style="color: #ffffff; font-size: 14px;">Bing &amp; Yahoo Webmaster Tools</strong>
        </div>
        <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 12px; line-height: 1.5;">
          Bing powers Yahoo &amp; Copilot search. Submit <code>sitemap-1.xml</code> directly here.
        </p>
        <a href="https://www.bing.com/webmasters/sitemaps" target="_blank" class="btn-admin btn-admin-primary" style="background: #0284c7; border-color: #0ea5e9; font-size: 12px; padding: 6px 14px;">
          <i class="fas fa-external-link-alt"></i> Open Bing Sitemaps Console
        </a>
      </div>
    </div>
  </div>

  <!-- Bottom Floating Save Bar -->
  <div style="margin-top: 24px; padding: 18px 24px; background: rgba(15, 23, 42, 0.95); border: 1px solid rgba(99, 102, 241, 0.3); border-radius: 10px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
    <div style="display: flex; align-items: center; gap: 10px;">
      <i class="fas fa-save" style="color: #818cf8; font-size: 18px;"></i>
      <span style="font-size: 13px; color: var(--text-muted);">
        Saving changes will instantly update configuration and refresh <code>/sitemap.xml</code>.
      </span>
    </div>

    <div style="display: flex; gap: 12px;">
      <a href="{{ route('admin.dashboard') }}" class="btn-admin btn-admin-secondary">
        Cancel
      </a>
      <button type="submit" class="btn-admin btn-admin-primary" style="padding: 10px 24px; font-size: 14px; font-weight: 700; background: linear-gradient(135deg, #4f46e5, #2563eb);">
        <i class="fas fa-check-circle"></i> Save Sitemap Changes
      </button>
    </div>
  </div>
</form>

@endsection

@section('scripts')
<script>
// Tab Switching Handler
document.querySelectorAll('.sitemap-tab-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    document.querySelectorAll('.sitemap-tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.sitemap-tab-pane').forEach(p => p.classList.remove('active'));

    this.classList.add('active');
    const target = this.getAttribute('data-tab');
    const pane = document.getElementById(target);
    if (pane) {
      pane.classList.add('active');
    }
  });
});

// Clipboard Helper
function copyToClipboard(text) {
  if (navigator.clipboard) {
    navigator.clipboard.writeText(text).then(function() {
      showToast('Sitemap URL copied: ' + text);
    }).catch(function() {
      prompt('Copy this sitemap URL:', text);
    });
  } else {
    prompt('Copy this sitemap URL:', text);
  }
}

// Simple Toast Notification
function showToast(message, isError = false) {
  const existing = document.getElementById('sitemap-ajax-toast');
  if (existing) existing.remove();

  const toast = document.createElement('div');
  toast.id = 'sitemap-ajax-toast';
  toast.className = 'alert-toast';
  if (isError) toast.style.borderLeftColor = '#ef4444';
  toast.innerHTML = `
    <i class="fas ${isError ? 'fa-exclamation-circle' : 'fa-check-circle'}" style="color: ${isError ? '#ef4444' : '#10b981'}; font-size: 20px;"></i>
    <div>
      <div style="font-weight: 600; font-size: 14px;">${isError ? 'Notice' : 'Success'}</div>
      <div style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">${message}</div>
    </div>
  `;
  document.body.appendChild(toast);
  setTimeout(() => {
    toast.style.animation = 'slideIn 0.3s ease reverse forwards';
    setTimeout(() => toast.remove(), 300);
  }, 4000);
}

// Custom URLs dynamic row addition
let customUrlIndex = {{ count($customUrls) + 10 }};
function addCustomUrlRow() {
  const tbody = document.getElementById('custom-urls-tbody');
  const emptyRow = document.getElementById('empty-custom-urls-row');
  if (emptyRow) emptyRow.remove();

  const today = new Date().toISOString().split('T')[0];
  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td>
      <input type="text" name="custom_urls[${customUrlIndex}][url]" class="form-control" placeholder="e.g. /special-page or https://..." required>
    </td>
    <td>
      <select name="custom_urls[${customUrlIndex}][priority]" class="form-control">
        <option value="1.0">1.0</option>
        <option value="0.9">0.9</option>
        <option value="0.8" selected>0.8</option>
        <option value="0.7">0.7</option>
        <option value="0.6">0.6</option>
        <option value="0.5">0.5</option>
      </select>
    </td>
    <td>
      <select name="custom_urls[${customUrlIndex}][changefreq]" class="form-control">
        <option value="daily">Daily</option>
        <option value="weekly">Weekly</option>
        <option value="monthly" selected>Monthly</option>
        <option value="yearly">Yearly</option>
      </select>
    </td>
    <td>
      <input type="date" name="custom_urls[${customUrlIndex}][lastmod]" class="form-control" value="${today}">
    </td>
    <td style="text-align: center;">
      <button type="button" onclick="this.closest('tr').remove()" class="btn-admin btn-admin-danger" style="padding: 6px 10px;">
        <i class="fas fa-trash"></i>
      </button>
    </td>
  `;
  tbody.appendChild(tr);
  customUrlIndex++;
}

// Single Sitemap Test
function testSitemap() {
  const panel = document.getElementById('test-results-panel');
  const content = document.getElementById('test-results-content');
  panel.style.display = 'block';
  content.innerHTML = `<div style="color: #94a3b8; font-size: 13px;"><i class="fas fa-spinner fa-spin"></i> Validating /sitemap-1.xml...</div>`;

  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  fetch('{{ route('admin.sitemap.test') }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrfToken,
      'Accept': 'application/json'
    }
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      content.innerHTML = `
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 10px; margin-bottom: 12px;">
          <div style="background: rgba(255,255,255,0.05); padding: 8px 12px; border-radius: 6px;">
            <div style="font-size: 11px; color: var(--text-muted);">HTTP Status</div>
            <div style="font-weight: 700; color: #10b981; font-size: 16px;">${data.status_code} OK</div>
          </div>
          <div style="background: rgba(255,255,255,0.05); padding: 8px 12px; border-radius: 6px;">
            <div style="font-size: 11px; color: var(--text-muted);">XML Validation</div>
            <div style="font-weight: 700; color: ${data.is_valid_xml ? '#10b981' : '#ef4444'}; font-size: 16px;">
              ${data.is_valid_xml ? 'Valid XML' : 'Parse Error'}
            </div>
          </div>
          <div style="background: rgba(255,255,255,0.05); padding: 8px 12px; border-radius: 6px;">
            <div style="font-size: 11px; color: var(--text-muted);">Total URL Nodes</div>
            <div style="font-weight: 700; color: #60a5fa; font-size: 16px;">${data.url_count} URLs</div>
          </div>
          <div style="background: rgba(255,255,255,0.05); padding: 8px 12px; border-radius: 6px;">
            <div style="font-size: 11px; color: var(--text-muted);">Payload Size</div>
            <div style="font-weight: 700; color: #fbbf24; font-size: 16px;">${data.size}</div>
          </div>
          <div style="background: rgba(255,255,255,0.05); padding: 8px 12px; border-radius: 6px;">
            <div style="font-size: 11px; color: var(--text-muted);">Execution Time</div>
            <div style="font-weight: 700; color: #a855f7; font-size: 16px;">${data.execution_time_ms} ms</div>
          </div>
        </div>
        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 6px;">
          Endpoint: <code>${data.endpoint}</code> | Content-Type: <code>${data.content_type}</code>
        </div>
        <pre style="background: #090d16; border: 1px solid rgba(255,255,255,0.08); padding: 10px; border-radius: 6px; font-size: 11px; color: #94a3b8; overflow-x: auto; max-height: 140px;">${escapeHtml(data.preview_snippet)}...</pre>
      `;
    } else {
      content.innerHTML = `<div style="color: #ef4444;"><i class="fas fa-times-circle"></i> Error: ${data.message}</div>`;
    }
  })
  .catch(err => {
    content.innerHTML = `<div style="color: #ef4444;"><i class="fas fa-times-circle"></i> Request Failed: ${err.message}</div>`;
  });
}

function escapeHtml(string) {
  const entityMap = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#39;',
    '/': '&#x2F;'
  };
  return String(string).replace(/[&<>"'\/]/g, function (s) {
    return entityMap[s];
  });
}
</script>
@endsection
