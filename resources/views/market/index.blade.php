@extends('layouts.app')

@php
    use App\Helpers\BengaliHelper;
    $todayDateBn = BengaliHelper::toBengaliDate(now());
    $updatedTimeBn = $marketData['last_updated_bn'] ?? ($todayDateBn . ', ' . BengaliHelper::toBengaliTime(now()));
@endphp

@section('title', 'আজকের সোনার দাম (' . $todayDateBn . ') কলকাতা | 22 ও 24 ক্যারেট সোনা, রূপো ও সেনসেক্স লাইভ দর')
@section('meta_description', 'আজকের সোনার দাম কলকাতায় (' . $todayDateBn . '): 24 ক্যারেট ও 22 ক্যারেট সোনার দাম, ১ কেজি রূপোর রেট এবং বিএসই সেনসেক্স ও নিফটি ৫০ লাইভ শেয়ার বাজার আপডেট। সম্পূর্ণ রেট চার্ট ও FAQ।')

@section('styles')
<style>
  html {
    scroll-behavior: smooth;
  }
  .market-page-container {
    width: 100%;
    max-width: 1280px;
    margin: 20px auto 40px auto;
    padding: 0 16px;
    box-sizing: border-box;
    overflow-x: hidden;
  }

  /* Hero Section */
  .market-hero {
    background: linear-gradient(135deg, #0b1329 0%, #1e1b4b 50%, #0f172a 100%);
    border-radius: 16px;
    padding: 28px 24px;
    margin-bottom: 24px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
    position: relative;
    overflow: hidden;
    width: 100%;
    box-sizing: border-box;
  }
  .market-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 320px;
    height: 320px;
    background: radial-gradient(circle, rgba(234, 179, 8, 0.15), transparent 70%);
    pointer-events: none;
  }
  .market-header-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 22px;
  }
  .market-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: #ffffff;
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0;
    line-height: 1.3;
  }
  .market-live-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(16, 185, 129, 0.15);
    color: #34d399;
    border: 1px solid rgba(16, 185, 129, 0.35);
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 0.8rem;
    font-weight: 700;
    white-space: nowrap;
  }
  .market-live-pill span.dot {
    width: 8px;
    height: 8px;
    background: #10b981;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 8px #10b981;
    animation: livePulse 2s infinite ease-in-out;
  }
  @keyframes livePulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.2); }
  }

  /* Metric Cards Grid */
  .rate-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
    gap: 14px;
    width: 100%;
  }
  .rate-card {
    background: rgba(255, 255, 255, 0.04);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 18px;
    transition: transform 0.2s ease, border-color 0.2s ease;
    box-sizing: border-box;
  }
  .rate-card:hover {
    transform: translateY(-3px);
    border-color: rgba(255, 255, 255, 0.25);
  }
  .rate-card.gold-24k {
    border-top: 4px solid #eab308;
    background: linear-gradient(180deg, rgba(234, 179, 8, 0.07) 0%, rgba(255, 255, 255, 0.02) 100%);
  }
  .rate-card.gold-22k {
    border-top: 4px solid #f59e0b;
    background: linear-gradient(180deg, rgba(245, 158, 11, 0.07) 0%, rgba(255, 255, 255, 0.02) 100%);
  }
  .rate-card.silver {
    border-top: 4px solid #94a3b8;
    background: linear-gradient(180deg, rgba(148, 163, 184, 0.07) 0%, rgba(255, 255, 255, 0.02) 100%);
  }
  .rate-card.sensex {
    border-top: 4px solid #3b82f6;
    background: linear-gradient(180deg, rgba(59, 130, 246, 0.07) 0%, rgba(255, 255, 255, 0.02) 100%);
  }
  .rate-card.nifty {
    border-top: 4px solid #8b5cf6;
    background: linear-gradient(180deg, rgba(139, 92, 246, 0.07) 0%, rgba(255, 255, 255, 0.02) 100%);
  }
  .card-top-label {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.85rem;
    font-weight: 600;
    color: #94a3b8;
    margin-bottom: 8px;
  }
  .card-main-price {
    font-size: 1.65rem;
    font-weight: 800;
    color: #ffffff;
    margin-bottom: 6px;
    letter-spacing: -0.5px;
  }
  .card-change-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 0.78rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 6px;
    white-space: nowrap;
  }
  .card-change-badge.positive {
    background: rgba(16, 185, 129, 0.18);
    color: #34d399;
  }
  .card-change-badge.negative {
    background: rgba(239, 68, 68, 0.18);
    color: #f87171;
  }

  /* Quick Navigation Bar */
  .market-quick-nav {
    position: sticky;
    top: 65px;
    z-index: 40;
    background: var(--bg-card, #ffffff);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 12px;
    padding: 8px 12px;
    margin-bottom: 24px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
    gap: 8px;
    overflow-x: auto;
    scrollbar-width: none;
    -webkit-overflow-scrolling: touch;
  }
  .market-quick-nav::-webkit-scrollbar {
    display: none;
  }
  body.dark-mode .market-quick-nav {
    background: #1e293b;
    border-color: rgba(255, 255, 255, 0.08);
  }
  .quick-nav-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    border-radius: 8px;
    font-size: 0.88rem;
    font-weight: 700;
    color: var(--text-main, #334155);
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.2s ease;
    background: rgba(0, 0, 0, 0.03);
  }
  body.dark-mode .quick-nav-pill {
    color: #e2e8f0;
    background: rgba(255, 255, 255, 0.04);
  }
  .quick-nav-pill:hover,
  .quick-nav-pill.active {
    background: var(--brand-red, #dc2626);
    color: #ffffff;
  }

  /* Sections */
  .market-section {
    background: var(--bg-card, #ffffff);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 14px;
    padding: 26px 24px;
    margin-bottom: 28px;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.03);
  }
  body.dark-mode .market-section {
    background: #1e293b;
    border-color: rgba(255, 255, 255, 0.08);
  }
  .section-main-heading {
    font-size: 1.55rem;
    font-weight: 800;
    color: var(--text-main, #0f172a);
    margin: 0 0 10px 0;
    line-height: 1.35;
    display: flex;
    align-items: center;
    gap: 10px;
    border-left: 5px solid var(--brand-red, #dc2626);
    padding-left: 12px;
  }
  body.dark-mode .section-main-heading {
    color: #f8fafc;
  }
  .update-pill-bar {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(59, 130, 246, 0.08);
    color: #2563eb;
    border: 1px solid rgba(59, 130, 246, 0.2);
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 0.82rem;
    font-weight: 600;
    margin-bottom: 16px;
  }
  body.dark-mode .update-pill-bar {
    background: rgba(59, 130, 246, 0.18);
    color: #93c5fd;
    border-color: rgba(147, 197, 253, 0.25);
  }
  .section-intro-text {
    font-size: 0.98rem;
    line-height: 1.75;
    color: var(--text-main, #334155);
    margin-bottom: 22px;
  }
  body.dark-mode .section-intro-text {
    color: #cbd5e1;
  }

  /* Table of Contents (TOC) */
  .toc-box {
    background: rgba(0, 0, 0, 0.02);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 10px;
    padding: 16px 20px;
    margin-bottom: 24px;
  }
  body.dark-mode .toc-box {
    background: rgba(255, 255, 255, 0.03);
    border-color: rgba(255, 255, 255, 0.08);
  }
  .toc-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--text-main, #0f172a);
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  body.dark-mode .toc-title {
    color: #f8fafc;
  }
  .toc-list {
    margin: 0;
    padding-left: 20px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 8px 20px;
  }
  .toc-list li a {
    color: #2563eb;
    text-decoration: none;
    font-size: 0.92rem;
    font-weight: 600;
  }
  body.dark-mode .toc-list li a {
    color: #60a5fa;
  }
  .toc-list li a:hover {
    text-decoration: underline;
  }

  /* Summary callout bar */
  .summary-bar {
    background: rgba(16, 185, 129, 0.08);
    border-left: 4px solid #10b981;
    padding: 12px 18px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.95rem;
    color: #065f46;
    margin: 18px 0;
  }
  body.dark-mode .summary-bar {
    background: rgba(16, 185, 129, 0.15);
    color: #6ee7b7;
  }
  .summary-bar.down {
    background: rgba(239, 68, 68, 0.08);
    border-left-color: #ef4444;
    color: #991b1b;
  }
  body.dark-mode .summary-bar.down {
    background: rgba(239, 68, 68, 0.15);
    color: #fca5a5;
  }

  /* Custom Tables */
  .market-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border-radius: 8px;
    margin-bottom: 16px;
    scrollbar-width: thin;
  }
  .custom-market-table {
    width: 100%;
    min-width: 520px;
    border-collapse: collapse;
    font-size: 0.94rem;
  }
  .custom-market-table th {
    background: rgba(0, 0, 0, 0.04);
    padding: 12px 16px;
    text-align: left;
    font-weight: 700;
    color: var(--text-main, #1e293b);
    border-bottom: 2px solid var(--border-color, #cbd5e1);
    white-space: nowrap;
  }
  body.dark-mode .custom-market-table th {
    background: rgba(255, 255, 255, 0.05);
    color: #f8fafc;
    border-bottom-color: rgba(255, 255, 255, 0.1);
  }
  .custom-market-table td {
    padding: 12px 16px;
    border-bottom: 1px solid var(--border-color, #e2e8f0);
    color: var(--text-main, #334155);
    white-space: nowrap;
  }
  body.dark-mode .custom-market-table td {
    border-bottom-color: rgba(255, 255, 255, 0.06);
    color: #cbd5e1;
  }
  .custom-market-table tr:hover td {
    background: rgba(99, 102, 241, 0.04);
  }

  /* Cards Grid (Investment, Guides, Reasons) */
  .feature-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 16px;
    margin: 20px 0;
  }
  .feature-card-item {
    background: rgba(0, 0, 0, 0.02);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 10px;
    padding: 18px;
    box-sizing: border-box;
    transition: transform 0.2s ease;
  }
  .feature-card-item:hover {
    transform: translateY(-2px);
  }
  body.dark-mode .feature-card-item {
    background: rgba(255, 255, 255, 0.03);
    border-color: rgba(255, 255, 255, 0.08);
  }
  .feature-card-title {
    font-size: 1.05rem;
    font-weight: 700;
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .feature-card-desc {
    font-size: 0.9rem;
    line-height: 1.6;
    color: var(--text-muted, #64748b);
    margin: 0;
  }
  body.dark-mode .feature-card-desc {
    color: #94a3b8;
  }

  /* Info Notes */
  .info-box {
    background: rgba(59, 130, 246, 0.06);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 8px;
    padding: 14px 18px;
    font-size: 0.9rem;
    color: var(--text-muted, #64748b);
    line-height: 1.6;
    margin: 16px 0;
  }
  body.dark-mode .info-box {
    color: #cbd5e1;
  }

  /* FAQ Items */
  .faq-item {
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 10px;
    padding: 16px 18px;
    margin-bottom: 12px;
    background: var(--bg-card, #ffffff);
  }
  body.dark-mode .faq-item {
    background: rgba(255, 255, 255, 0.02);
    border-color: rgba(255, 255, 255, 0.08);
  }
  .faq-item-q {
    font-size: 1.02rem;
    font-weight: 700;
    color: var(--text-main, #0f172a);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  body.dark-mode .faq-item-q {
    color: #f8fafc;
  }
  .faq-item-a {
    font-size: 0.92rem;
    color: var(--text-muted, #64748b);
    line-height: 1.65;
    margin: 0;
  }
  body.dark-mode .faq-item-a {
    color: #94a3b8;
  }

  /* Subheadings inside sections */
  .section-subheading {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-main, #0f172a);
    margin: 28px 0 14px 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  body.dark-mode .section-subheading {
    color: #f8fafc;
  }

  /* Disclaimer Box */
  .disclaimer-card {
    background: rgba(245, 158, 11, 0.08);
    border: 1px solid rgba(245, 158, 11, 0.25);
    border-radius: 10px;
    padding: 14px 18px;
    font-size: 0.88rem;
    color: #92400e;
    margin: 24px 0 10px 0;
    line-height: 1.6;
  }
  body.dark-mode .disclaimer-card {
    background: rgba(245, 158, 11, 0.12);
    color: #fde68a;
    border-color: rgba(245, 158, 11, 0.25);
  }

  /* Responsive styles */
  @media (max-width: 768px) {
    .market-page-container {
      padding: 0 12px;
      margin-top: 14px;
    }
    .market-hero {
      padding: 18px 14px;
      border-radius: 12px;
      margin-bottom: 16px;
    }
    .market-title {
      font-size: 1.25rem;
    }
    .market-quick-nav {
      top: 55px;
      padding: 6px 8px;
      margin-bottom: 16px;
    }
    .quick-nav-pill {
      font-size: 0.82rem;
      padding: 6px 10px;
    }
    .rate-cards-grid {
      grid-template-columns: 1fr;
      gap: 10px;
    }
    .market-section {
      padding: 18px 14px;
      border-radius: 12px;
      margin-bottom: 20px;
    }
    .section-main-heading {
      font-size: 1.2rem;
      padding-left: 8px;
    }
    .section-subheading {
      font-size: 1.08rem;
      margin-top: 22px;
    }
    .feature-cards-grid {
      grid-template-columns: 1fr;
      gap: 12px;
    }
    .custom-market-table {
      font-size: 0.86rem;
      min-width: 480px;
    }
    .custom-market-table th,
    .custom-market-table td {
      padding: 10px 10px;
    }
  }
</style>
@endsection

@section('content')
<div class="container market-page-container">

  <!-- Breadcrumbs -->
  <nav style="margin-bottom: 14px; font-size: 0.85rem; color: #94a3b8;">
    <a href="{{ route('home') }}" style="color: inherit; text-decoration: none;">প্রচ্ছদ</a>
    <span style="margin: 0 6px;">/</span>
    <span style="color: var(--brand-red, #dc2626); font-weight: 600;">আজকের বাজার দর ও সোনা-রূপো</span>
  </nav>

  <!-- Hero Banner with Live Metric Cards -->
  <div class="market-hero">
    <div class="market-header-flex">
      <div>
        <h1 class="market-title">
          <i class="fas fa-coins" style="color: #eab308;"></i>
          আজকের সোনা, রূপো ও সেনসেক্স বাজার দর
        </h1>
        <p style="color: #94a3b8; font-size: 0.9rem; margin-top: 6px; margin-bottom: 0;">
          কলকাতা ও পশ্চিমবঙ্গের আজকের সর্বশেষ সোনার দাম, রূপোর দর এবং বিএসই সেনসেক্স ও নিফটি ৫০ সূচক।
        </p>
      </div>

      <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
        <span class="market-live-pill">
          <span class="dot"></span> লাইভ রেট
        </span>
        <span style="color: #cbd5e1; font-size: 0.85rem;">
          <i class="far fa-clock" style="margin-right: 4px;"></i> {{ $updatedTimeBn }}
        </span>
      </div>
    </div>

    <!-- Metric Cards Grid -->
    <div class="rate-cards-grid">
      <!-- 24K Gold Card -->
      <div class="rate-card gold-24k">
        <div class="card-top-label">
          <span>২৪ ক্যারেট সোনা (১০ গ্রাম)</span>
          <i class="fas fa-certificate" style="color: #eab308;"></i>
        </div>
        <div class="card-main-price">
          ₹{{ $marketData['gold_24k_formatted'] ?? '৭৭,২৬০' }}
        </div>
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <span class="card-change-badge {{ ($marketData['gold_is_positive'] ?? true) ? 'positive' : 'negative' }}">
            <i class="fas {{ ($marketData['gold_is_positive'] ?? true) ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
            {{ $marketData['gold_change_formatted'] ?? '+৪১০' }} ({{ $marketData['gold_change_percent_formatted'] ?? '+০.৫৪%' }})
          </span>
          <span style="font-size: 0.75rem; color: #94a3b8;">৯৯.৯% বিশুদ্ধ</span>
        </div>
      </div>

      <!-- 22K Gold Card -->
      <div class="rate-card gold-22k">
        <div class="card-top-label">
          <span>২২ ক্যারেট গহনা সোনা (১০ গ্রাম)</span>
          <i class="fas fa-gem" style="color: #f59e0b;"></i>
        </div>
        <div class="card-main-price">
          ₹{{ $marketData['gold_22k_formatted'] ?? '৭০,৮২০' }}
        </div>
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <span style="font-size: 0.8rem; color: #cbd5e1;">
            ১ ভরি (৮ গ্রাম): <strong>₹{{ $marketData['gold_22k_bhori_formatted'] ?? '৫৬,৬৬০' }}</strong>
          </span>
          <span style="font-size: 0.75rem; color: #94a3b8;">হলমার্ক ৯১৬</span>
        </div>
      </div>

      <!-- Silver Card -->
      <div class="rate-card silver">
        <div class="card-top-label">
          <span>রূপোর দর (১ কেজি)</span>
          <i class="fas fa-ring" style="color: #cbd5e1;"></i>
        </div>
        <div class="card-main-price">
          ₹{{ $marketData['silver_1kg_formatted'] ?? '৯৫,৬৮০' }}
        </div>
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <span class="card-change-badge {{ ($marketData['silver_is_positive'] ?? true) ? 'positive' : 'negative' }}">
            <i class="fas {{ ($marketData['silver_is_positive'] ?? true) ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
            {{ $marketData['silver_change_formatted'] ?? '+১,১৮০' }}
          </span>
          <span style="font-size: 0.75rem; color: #94a3b8;">১০ গ্রাম: ₹{{ $marketData['silver_10g_formatted'] ?? '৯৫৭' }}</span>
        </div>
      </div>

      <!-- Sensex Card -->
      <div class="rate-card sensex">
        <div class="card-top-label">
          <span>বিএসই সেনসেক্স (Sensex)</span>
          <i class="fas fa-chart-line" style="color: #60a5fa;"></i>
        </div>
        <div class="card-main-price" style="color: {{ ($marketData['sensex']['is_positive'] ?? true) ? '#4ade80' : '#f87171' }};">
          {{ $marketData['sensex']['price_formatted'] ?? '৭৩,৮৯৫.৭৪' }}
        </div>
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <span class="card-change-badge {{ ($marketData['sensex']['is_positive'] ?? true) ? 'positive' : 'negative' }}">
            <i class="fas {{ ($marketData['sensex']['is_positive'] ?? true) ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
            {{ $marketData['sensex']['change_formatted'] ?? '+৩১৫.২০' }} ({{ $marketData['sensex']['change_percent_formatted'] ?? '+০.৪৩%' }})
          </span>
          <span style="font-size: 0.75rem; color: #94a3b8;">BSE লাইভ</span>
        </div>
      </div>

      <!-- Nifty 50 Card -->
      <div class="rate-card nifty">
        <div class="card-top-label">
          <span>এনএসই নিফটি ৫০ (Nifty 50)</span>
          <i class="fas fa-chart-area" style="color: #c084fc;"></i>
        </div>
        <div class="card-main-price" style="color: {{ ($marketData['nifty']['is_positive'] ?? true) ? '#4ade80' : '#f87171' }};">
          {{ $marketData['nifty']['price_formatted'] ?? '২৩,১৪০.৫০' }}
        </div>
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <span class="card-change-badge {{ ($marketData['nifty']['is_positive'] ?? true) ? 'positive' : 'negative' }}">
            <i class="fas {{ ($marketData['nifty']['is_positive'] ?? true) ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
            {{ $marketData['nifty']['change_formatted'] ?? '+৭৭.৪০' }} ({{ $marketData['nifty']['change_percent_formatted'] ?? '+০.৩৪%' }})
          </span>
          <span style="font-size: 0.75rem; color: #94a3b8;">NSE লাইভ</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Sticky In-Page Navigation Bar -->
  <div class="market-quick-nav">
    <a href="#gold-section" class="quick-nav-pill active">
      <i class="fas fa-coins" style="color: #eab308;"></i> আজকের সোনার দাম
    </a>
    <a href="#silver-section" class="quick-nav-pill">
      <i class="fas fa-gem" style="color: #94a3b8;"></i> আজকের রূপোর দাম
    </a>
    <a href="#stocks-section" class="quick-nav-pill">
      <i class="fas fa-chart-line" style="color: #3b82f6;"></i> সেনসেক্স ও নিফটি ৫০
    </a>
    <a href="#stocks-table" class="quick-nav-pill">
      <i class="fas fa-arrow-trend-up" style="color: #10b981;"></i> শীর্ষ ১০ কোম্পানি
    </a>
    <a href="#faq-section" class="quick-nav-pill">
      <i class="fas fa-question-circle" style="color: #06b6d4;"></i> সাধারণ প্রশ্নোত্তর (FAQ)
    </a>
  </div>

  <!-- ========================================== -->
  <!-- SECTION 1: আজকের সোনার দাম (কলকাতা)       -->
  <!-- ========================================== -->
  <section id="gold-section" class="market-section">
    <h2 class="section-main-heading">
      আজকের সোনার দাম ({{ $todayDateBn }}): ২৪ ক্যারেট ও ২২ ক্যারেট সোনার সম্পূর্ণ আপডেট (কলকাতা)
    </h2>
    <div class="update-pill-bar">
      <i class="far fa-clock"></i> সর্বশেষ আপডেট: {{ $updatedTimeBn }}
    </div>

    <p class="section-intro-text">
      আজকের সোনার দাম জানতে চাইলে আপনি সঠিক জায়গায় এসেছেন। কলকাতা তথা সমগ্র পশ্চিমবঙ্গে সোনার দাম প্রতিদিন সকালে আন্তর্জাতিক বুলিয়ন রেট ও স্থানীয় জুয়েলার্স অ্যাসোসিয়েশনের ঘোষণার ভিত্তিতে নির্ধারিত হয়। এই পেজে ২৪ ক্যারেট সোনার দাম, ২২ ক্যারেট সোনার দাম, ১৮ ক্যারেট সোনার দাম, ওজন অনুযায়ী সম্পূর্ণ রেট চার্ট, শহরভিত্তিক তুলনা, বিনিয়োগের উপায় এবং প্রয়োজনীয় সব তথ্য একসঙ্গে দেওয়া হলো।
    </p>

    <!-- আজকের সোনার লাইভ রেট টেবিল -->
    <div class="section-subheading" style="margin-top: 10px;">
      <i class="fas fa-coins" style="color: #eab308;"></i> আজকের সোনার লাইভ রেট
    </div>
    <div class="market-table-wrapper">
      <table class="custom-market-table">
        <thead>
          <tr>
            <th>বিষয়</th>
            <th>আজকের দর</th>
            <th>পরিবর্তন</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>২৪ ক্যারেট সোনা (১০ গ্রাম)</strong></td>
            <td><strong style="color: #eab308; font-size: 1.05rem;">₹{{ $marketData['gold_24k_formatted'] ?? '৭৭,২৬০' }}</strong></td>
            <td style="color: {{ ($marketData['gold_is_positive'] ?? true) ? '#10b981' : '#ef4444' }}; font-weight: 700;">
              {{ $marketData['gold_change_formatted'] ?? '+৪১০' }} ({{ $marketData['gold_change_percent_formatted'] ?? '+০.৫৪%' }})
            </td>
          </tr>
          <tr>
            <td><strong>২২ ক্যারেট গহনা সোনা (১০ গ্রাম)</strong></td>
            <td><strong style="color: #f59e0b; font-size: 1.05rem;">₹{{ $marketData['gold_22k_formatted'] ?? '৭০,৮২০' }}</strong></td>
            <td>১ ভরি (৮ গ্রাম): <strong>₹{{ $marketData['gold_22k_bhori_formatted'] ?? '৫৬,৬৬০' }}</strong></td>
          </tr>
          <tr>
            <td><strong>১৮ ক্যারেট সোনা (১০ গ্রাম)</strong></td>
            <td><strong>₹{{ $marketData['gold_18k_formatted'] ?? '৫৭,৯৫০' }}</strong></td>
            <td style="color: var(--text-muted);">দৈনিক বুলিয়ন রেট অনুযায়ী</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="summary-bar {{ ($marketData['gold_is_positive'] ?? true) ? '' : 'down' }}">
      <i class="fas {{ ($marketData['gold_is_positive'] ?? true) ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
      <strong>সারসংক্ষেপ:</strong> আজ সোনার দাম আগের দিনের তুলনায় {{ ($marketData['gold_is_positive'] ?? true) ? 'ঊর্ধ্বমুখী' : 'নিম্নমুখী' }}।
    </div>

    <!-- ২৪ ক্যারেট সোনার দাম আজ -->
    <div class="section-subheading">
      <i class="fas fa-check-circle" style="color: #eab308;"></i> ২৪ ক্যারেট সোনার দাম আজ
    </div>
    <p style="font-size: 1.05rem; font-weight: 700; color: #eab308; margin-bottom: 12px;">
      আজ ২৪ ক্যারেট সোনার দাম: ১০ গ্রাম প্রতি ₹{{ $marketData['gold_24k_formatted'] ?? '৭৭,২৬০' }} (৯৯.৯% বিশুদ্ধ)
    </p>

    <div class="feature-cards-grid">
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #eab308;"><i class="fas fa-atom"></i> ২৪ ক্যারেট সোনা কী?</h3>
        <p class="feature-card-desc">
          ২৪ ক্যারেট সোনা সবচেয়ে বিশুদ্ধ রূপ, এতে কোনও বাড়তি ধাতু মেশানো থাকে না। এই কারণে ২৪ ক্যারেট সোনার দাম বাজারের প্রকৃত বুলিয়ন রেট প্রতিফলিত করে।
        </p>
      </div>

      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #10b981;"><i class="fas fa-piggy-bank"></i> কেন কেনা হয়?</h3>
        <p class="feature-card-desc">
          • সোনার কয়েন ও বার আকারে বিনিয়োগের জন্য<br>
          • ব্যাংক থেকে বিনিয়োগ-উদ্দেশ্যে সোনার বার কেনার জন্য<br>
          • ডিজিটাল গোল্ড ও গোল্ড ইটিএফ-এর দামের রেফারেন্স হিসেবে
        </p>
      </div>

      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #ef4444;"><i class="fas fa-circle-exclamation"></i> কেন গহনা তৈরি হয় না?</h3>
        <p class="feature-card-desc">
          ২৪ ক্যারেট সোনা অত্যন্ত নরম ও নমনীয় হওয়ায় টেকসই গহনা তৈরি করা কঠিন, তাই গহনার জন্য সাধারণত ২২ বা ১৮ ক্যারেট ব্যবহার করা হয়।
        </p>
      </div>
    </div>

    <!-- ২২ ক্যারেট সোনার দাম আজ -->
    <div class="section-subheading">
      <i class="fas fa-gem" style="color: #f59e0b;"></i> ২২ ক্যারেট সোনার দাম আজ
    </div>
    <p style="font-size: 1.05rem; font-weight: 700; color: #f59e0b; margin-bottom: 12px;">
      আজ ২২ ক্যারেট সোনার দাম: ১০ গ্রাম প্রতি ₹{{ $marketData['gold_22k_formatted'] ?? '৭০,৮২০' }} | ১ ভরি (৮ গ্রাম): ₹{{ $marketData['gold_22k_bhori_formatted'] ?? '৫৬,৬৬০' }}
    </p>

    <div class="feature-cards-grid">
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #f59e0b;"><i class="fas fa-ring"></i> ২২ ক্যারেট সোনা কী?</h3>
        <p class="feature-card-desc">
          ২২ ক্যারেট সোনায় ৯১.৬% বিশুদ্ধ সোনা থাকে, বাকি ৮.৪% তামা বা দস্তা মেশানো থাকে। এই মিশ্রণে সোনা মজবুত হয় ও সূক্ষ্ম কারুকাজ সহজ হয়, তাই বাংলার গহনার দোকানে ২২ ক্যারেট সোনাই সবচেয়ে বেশি বিক্রি হয়।
        </p>
      </div>

      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #3b82f6;"><i class="fas fa-certificate"></i> হলমার্ক তথ্য</h3>
        <p class="feature-card-desc">
          BIS ২২ ক্যারেট সোনার গহনায় ৯১৬ নম্বর হলমার্ক দেয়, যা বিশুদ্ধতার সরকারি প্রমাণ। এটি সরকার দ্বারা বাধ্যতামূলক করা হয়েছে।
        </p>
      </div>
    </div>

    <!-- ২২ বনাম ২৪ ক্যারেট তুলনা টেবিল -->
    <div class="section-subheading">
      <i class="fas fa-scale-balanced" style="color: #3b82f6;"></i> ২২ বনাম ২৪ ক্যারেট তুলনা
    </div>
    <div class="market-table-wrapper">
      <table class="custom-market-table">
        <thead>
          <tr>
            <th>বিষয়</th>
            <th>২৪ ক্যারেট</th>
            <th>২২ ক্যারেট</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>বিশুদ্ধতা</strong></td>
            <td>৯৯.৯%</td>
            <td>৯১.৬%</td>
          </tr>
          <tr>
            <td><strong>ব্যবহার</strong></td>
            <td>কয়েন, বার, নিরাপদ বিনিয়োগ</td>
            <td>গহনা ও অলঙ্কার তৈরিতে</td>
          </tr>
          <tr>
            <td><strong>মজবুতি</strong></td>
            <td>কম (নরম)</td>
            <td>তুলনামূলক বেশি মজবুত</td>
          </tr>
          <tr>
            <td><strong>দাম</strong></td>
            <td>তুলনামূলক বেশি</td>
            <td>তুলনামূলক কম</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- ১৮ ক্যারেট সোনার দাম আজ -->
    <div class="section-subheading">
      <i class="fas fa-medal" style="color: #8b5cf6;"></i> ১৮ ক্যারেট সোনার দাম আজ
    </div>
    <p style="font-size: 1.05rem; font-weight: 700; color: #8b5cf6; margin-bottom: 8px;">
      আজ ১৮ ক্যারেট সোনার দাম: ১০ গ্রাম প্রতি ₹{{ $marketData['gold_18k_formatted'] ?? '৫৭,৯৫০' }}
    </p>
    <p class="section-intro-text" style="margin-bottom: 20px;">
      ১৮ ক্যারেট সোনায় ৭৫% বিশুদ্ধ সোনা থাকে, বাকি ২৫% অন্যান্য ধাতু। এটি টেকসই ও হালকা ডিজাইনার গহনা, নাকছাবি বা দৈনন্দিন ব্যবহারের হালকা অলঙ্কারের জন্য বিশেষভাবে জনপ্রিয়।
    </p>

    <!-- কলকাতায় ওজন অনুযায়ী সোনার দর তালিকা -->
    <div class="section-subheading">
      <i class="fas fa-table" style="color: #eab308;"></i> কলকাতায় ওজন অনুযায়ী সোনার দর তালিকা
      <span style="font-size: 0.8rem; font-weight: normal; color: var(--text-muted); margin-left: 8px;">(জিএসটি ও মেকিং চার্জ বাদে)</span>
    </div>
    <div class="market-table-wrapper">
      <table class="custom-market-table">
        <thead>
          <tr>
            <th>ওজন</th>
            <th>২৪ ক্যারেট (৯৯.৯%)</th>
            <th>২২ ক্যারেট (৯১.৬%)</th>
            <th>১৮ ক্যারেট (৭৫.০%)</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>১ গ্রাম</strong></td>
            <td>₹{{ BengaliHelper::toBengaliNumerals(number_format(round($marketData['gold_24k'] / 10))) }}</td>
            <td>₹{{ BengaliHelper::toBengaliNumerals(number_format(round($marketData['gold_22k'] / 10))) }}</td>
            <td>₹{{ BengaliHelper::toBengaliNumerals(number_format(round($marketData['gold_18k'] / 10))) }}</td>
          </tr>
          <tr>
            <td><strong>৮ গ্রাম (১ ভরি/তোলা)</strong></td>
            <td>₹{{ BengaliHelper::toBengaliNumerals(number_format(round(($marketData['gold_24k'] / 10) * 8))) }}</td>
            <td>₹{{ $marketData['gold_22k_bhori_formatted'] ?? '৫৬,৬৬০' }}</td>
            <td>₹{{ BengaliHelper::toBengaliNumerals(number_format(round(($marketData['gold_18k'] / 10) * 8))) }}</td>
          </tr>
          <tr>
            <td><strong>১০ গ্রাম (স্ট্যান্ডার্ড)</strong></td>
            <td><strong style="color: #eab308;">₹{{ $marketData['gold_24k_formatted'] ?? '৭৭,২৬০' }}</strong></td>
            <td><strong style="color: #f59e0b;">₹{{ $marketData['gold_22k_formatted'] ?? '৭০,৮২০' }}</strong></td>
            <td><strong>₹{{ $marketData['gold_18k_formatted'] ?? '৫৭,৯৫০' }}</strong></td>
          </tr>
          <tr>
            <td><strong>১০০ গ্রাম</strong></td>
            <td>₹{{ BengaliHelper::toBengaliNumerals(number_format($marketData['gold_24k'] * 10)) }}</td>
            <td>₹{{ BengaliHelper::toBengaliNumerals(number_format($marketData['gold_22k'] * 10)) }}</td>
            <td>₹{{ BengaliHelper::toBengaliNumerals(number_format($marketData['gold_18k'] * 10)) }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="info-box">
      <i class="fas fa-info-circle" style="color: #3b82f6;"></i>
      <strong>মনে রাখবেন:</strong> উপরের দর খাঁটি বুলিয়ন রেটের ভিত্তিতে। গহনা কেনার সময় ৩% জিএসটি এবং মেকিং চার্জ (গড় ১০%–১৫%) বাড়তি প্রযোজ্য হবে।
    </div>

    <!-- ভারতের প্রধান শহরে সোনার দর তুলনা -->
    <div class="section-subheading">
      <i class="fas fa-city" style="color: #ef4444;"></i> ভারতের প্রধান শহরে সোনার দর তুলনা
      <span style="font-size: 0.8rem; font-weight: normal; color: var(--text-muted); margin-left: 8px;">(প্রতি ১০ গ্রাম হিসেবে)</span>
    </div>
    <div class="market-table-wrapper">
      <table class="custom-market-table">
        <thead>
          <tr>
            <th>শহর</th>
            <th>২৪ ক্যারেট (১০ গ্রাম)</th>
            <th>২২ ক্যারেট (১০ গ্রাম)</th>
          </tr>
        </thead>
        <tbody>
          @foreach($cityRates as $city)
            <tr>
              <td><strong>{{ $city['city_bn'] }}</strong> ({{ $city['city_en'] }})</td>
              <td>₹{{ BengaliHelper::toBengaliNumerals(number_format($city['gold_24k'])) }}</td>
              <td>₹{{ BengaliHelper::toBengaliNumerals(number_format($city['gold_22k'])) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <p style="font-size: 0.88rem; color: var(--text-muted); margin-top: 6px;">
      *শহরভেদে দামের সামান্য পার্থক্যের কারণ: স্থানীয় করব্যবস্থা, পরিবহন খরচ এবং চাহিদা-জোগানের তারতম্য।
    </p>

    <!-- সোনার দাম ওঠানামার কারণ -->
    <div class="section-subheading">
      <i class="fas fa-chart-line" style="color: #10b981;"></i> সোনার দাম ওঠানামার কারণ
    </div>
    <div class="feature-cards-grid">
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #3b82f6;"><i class="fas fa-globe"></i> আন্তর্জাতিক বুলিয়ন মার্কেট</h3>
        <p class="feature-card-desc">লন্ডন বুলিয়ন মার্কেট (London Bullion Market) বিশ্বব্যাপী সোনার আন্তর্জাতিক দর নির্ধারণে সবচেয়ে প্রভাবশালী ভূমিকা রাখে।</p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #10b981;"><i class="fas fa-dollar-sign"></i> ডলার-টাকার বিনিময় হার</h3>
        <p class="feature-card-desc">মার্কিন ডলারের বিপরীতে ভারতীয় টাকার দরপতন হলে সোনা আমদানির খরচ বৃদ্ধি পায় এবং ভারতে সোনার দাম বাড়ে।</p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #ef4444;"><i class="fas fa-shield-halved"></i> আন্তর্জাতিক যুদ্ধ ও ভূরাজনীতি</h3>
        <p class="feature-card-desc">বিশ্বজুড়ে যুদ্ধ বা অর্থনৈতিক অনিশ্চয়তার পরিস্থিতিতে নিরাপদ সম্পদ হিসেবে সোনায় বিনিয়োগকারীদের আগ্রহ বাড়ে।</p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #f59e0b;"><i class="fas fa-arrow-trend-up"></i> মুদ্রাস্ফীতি (Inflation Hedge)</h3>
        <p class="feature-card-desc">মূল্যস্ফীতির সময় মুদ্রার ক্রয়ক্ষমতা কমলেও সোনার মূল্য সংরক্ষিত থাকে, ফলে সোনাকে মুদ্রাস্ফীতির বিরুদ্ধে সুরক্ষা হিসেবে গণ্য করা হয়।</p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #ec4899;"><i class="fas fa-heart"></i> উৎসব ও বিয়ের মরসুম</h3>
        <p class="feature-card-desc">দুর্গাপুজো, ধনতেরাস, অক্ষয় তৃতীয়া এবং বিয়ের মরসুমে ভারতে সোনার অভ্যন্তরীণ চাহিদা তীব্রভাবে বৃদ্ধি পায়।</p>
      </div>
    </div>

    <!-- সোনায় বিনিয়োগের উপায় -->
    <div class="section-subheading">
      <i class="fas fa-sack-dollar" style="color: #eab308;"></i> সোনায় বিনিয়োগের উপায়
    </div>
    <div class="feature-cards-grid">
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #eab308;"><i class="fas fa-coins"></i> ফিজিক্যাল গোল্ড (কয়েন ও বার)</h3>
        <p class="feature-card-desc">ব্যাংক বা জুয়েলার্স থেকে সরাসরি কেনা যায়, তবে এর সঠিক সংরক্ষণ ও সুরক্ষার ব্যবস্থা রাখতে হয়।</p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #10b981;"><i class="fas fa-file-contract"></i> সভরেন গোল্ড বন্ড (SGB)</h3>
        <p class="feature-card-desc">ভারত সরকার ও RBI কর্তৃক জারিকৃত, নিশ্চিত বার্ষিক সুদের হার এবং সোনার মূলধন বৃদ্ধির সুবিধা একসঙ্গে পাওয়া যায়।</p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #3b82f6;"><i class="fas fa-chart-pie"></i> গোল্ড ইটিএফ (Gold ETF)</h3>
        <p class="feature-card-desc">স্টক এক্সচেঞ্জে তালিকাভুক্ত, ডিম্যাট (Demat) অ্যাকাউন্টের মাধ্যমে সহজে শেয়ারের মতোই ক্রয়-বিক্রয় করা যায়।</p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #8b5cf6;"><i class="fas fa-mobile-screen"></i> ডিজিটাল গোল্ড</h3>
        <p class="feature-card-desc">বিভিন্ন অনুমোদিত পেমেন্ট অ্যাপের মাধ্যমে মাত্র ₹১০ থেকেও শুরু করা যায় এবং সোনা নিরাপদ ভল্টে সংরক্ষিত থাকে।</p>
      </div>
    </div>

    <!-- সোনা কেনার আগে যাচাইয়ের তালিকা -->
    <div class="section-subheading">
      <i class="fas fa-list-check" style="color: #10b981;"></i> সোনা কেনার আগে যাচাইয়ের তালিকা
    </div>
    <div class="feature-cards-grid">
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #eab308;"><i class="fas fa-award"></i> BIS ৯১৬ হলমার্কিং দেখে নিন</h3>
        <p class="feature-card-desc">সোনার গহনা কেনার সময় অবশ্যই BIS ত্রিভুজ লোগো এবং ৯১৬ (২২ ক্যারেট) অথবা ৭৫০ (১৮ ক্যারেট) চিহ্ন রয়েছে কিনা তা যাচাই করে নিন। সরকার কর্তৃক এটি বাধ্যতামূলক।</p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #3b82f6;"><i class="fas fa-barcode"></i> ৬ সংখ্যার HUID কোড</h3>
        <p class="feature-card-desc">প্রতিটি হলমার্কযুক্ত সোনার গহনায় ৬ সংখ্যার আলফানিউমেরিক HUID (Hallmark Unique Identification) কোড থাকে। BIS Care অ্যাপের মাধ্যমে আপনি এই কোডটি দিয়ে সত্যতা যাচাই করতে পারেন।</p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #10b981;"><i class="fas fa-receipt"></i> আসল পাকা বিল সংগ্রহ করুন</h3>
        <p class="feature-card-desc">সোনার ওজন, ক্যারেটের বিশুদ্ধতা, মেকিং চার্জ এবং ৩% জিএসটি উল্লেখ থাকা অফিশিয়াল পাকা ট্যাক্স ইনভয়েস বিল বাধ্যতামূলকভাবে জুয়েলার্সের কাছ থেকে সংগ্রহ করুন।</p>
      </div>
    </div>

    <!-- শব্দকোষ -->
    <div class="section-subheading">
      <i class="fas fa-book" style="color: #6366f1;"></i> সোনার পরিভাষা ও শব্দকোষ
    </div>
    <div class="market-table-wrapper">
      <table class="custom-market-table">
        <thead>
          <tr>
            <th>শব্দ</th>
            <th>অর্থ</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>ক্যারেট (Karat)</strong></td>
            <td>সোনার বিশুদ্ধতা মাপার আন্তর্জাতিক একক (সর্বোচ্চ ২৪ ক্যারেট)।</td>
          </tr>
          <tr>
            <td><strong>হলমার্ক (Hallmark)</strong></td>
            <td>BIS (Bureau of Indian Standards) কর্তৃক প্রদত্ত সরকারি বিশুদ্ধতার সিলমোহর।</td>
          </tr>
          <tr>
            <td><strong>HUID</strong></td>
            <td>Hallmark Unique Identification: প্রতিটি হলমার্কযুক্ত গহনার অনন্য ৬ সংখ্যার সরকারি কোড।</td>
          </tr>
          <tr>
            <td><strong>ভরি / তোলা</strong></td>
            <td>বাংলার ঐতিহ্যবাহী সোনার ওজন একক (আন্তর্জাতিক মানদণ্ডে ≈ ১১.৬৬ গ্রাম; বাজারে ১০ গ্রামকেও তোলা ধরা হয়)।</td>
          </tr>
          <tr>
            <td><strong>SGB</strong></td>
            <td>Sovereign Gold Bond: রিজার্ভ ব্যাংক অফ ইন্ডিয়া পরিচালিত সরকারি সুদযুক্ত সোনা বিনিয়োগ প্রকল্প।</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- প্রায়শই জিজ্ঞাসিত প্রশ্ন (FAQ - Gold) -->
    <div class="section-subheading">
      <i class="fas fa-circle-question" style="color: #06b6d4;"></i> প্রায়শই জিজ্ঞাসিত প্রশ্ন (FAQ) - সোনা
    </div>
    <div class="faq-item">
      <div class="faq-item-q"><i class="fas fa-caret-right" style="color: #eab308;"></i> ২৪ ক্যারেট ও ২২ ক্যারেট সোনার মধ্যে পার্থক্য কী?</div>
      <p class="faq-item-a">২৪ ক্যারেট সোনা ৯৯.৯% খাঁটি সোনা, যা খুব নরম হওয়ায় সাধারণত কয়েন ও বার তৈরিতে ব্যবহৃত হয়। অন্যদিকে ২২ ক্যারেট সোনায় ৯১.৬% সোনা এবং বাকি অংশ তামা বা দস্তা মিশ্রিত থাকে, যা গহনা তৈরির জন্য মজবুত ও আদর্শ।</p>
    </div>
    <div class="faq-item">
      <div class="faq-item-q"><i class="fas fa-caret-right" style="color: #eab308;"></i> ১ ভরি বা ১ তোলা সোনা মানে কত গ্রাম?</div>
      <p class="faq-item-a">ঐতিহ্যগতভাবে বাংলায় সোনার হিসেব ভরিতে করা হয়। আন্তর্জাতিক মানদণ্ডে ১ ভরি সোনা সমান ১১.৬৬ গ্রাম। তবে ভারতীয় সাধারণ জুয়েলারি বাজারে সুবিধার জন্য ১০ গ্রামকে ১ তোলা হিসেবেও অনেক সময় গণ্য করা হয়।</p>
    </div>
    <div class="faq-item">
      <div class="faq-item-q"><i class="fas fa-caret-right" style="color: #eab308;"></i> সোনার দাম প্রতিদিন কেন পরিবর্তন হয়?</div>
      <p class="faq-item-a">আন্তর্জাতিক বাজারে অপরিশোধিত সোনার দাম (London Bullion Market), মার্কিন ডলারের বিপরীতে ভারতীয় টাকার বিনিময় হার, আন্তর্জাতিক যুদ্ধ ও অর্থনৈতিক অনিশ্চয়তা, মুদ্রাস্ফীতি এবং ভারতের অভ্যন্তরীণ উৎসব ও বিয়ের মরসুমে চাহিদার ওপর নির্ভর করে প্রতিদিন সোনা ও রূপোর দাম পরিবর্তিত হয়।</p>
    </div>
    <div class="faq-item">
      <div class="faq-item-q"><i class="fas fa-caret-right" style="color: #eab308;"></i> সোনা কেনার সময় কত শতাংশ জিএসটি (GST) দিতে হয়?</div>
      <p class="faq-item-a">ভারতে সোনা ও রূপোর বার এবং গহনা ক্রয়ের ক্ষেত্রে সরকার কর্তৃক ৩% পণ্য ও পরিষেবা কর (GST) আরোপিত হয়। এছাড়া গহনার মেকিং চার্জের ওপর আলাদাভাবে ৫% জিএসটি প্রযোজ্য হয়।</p>
    </div>
    <div class="faq-item">
      <div class="faq-item-q"><i class="fas fa-caret-right" style="color: #eab308;"></i> সোনায় বিনিয়োগের সবচেয়ে ভালো উপায় কী?</div>
      <p class="faq-item-a">দীর্ঘমেয়াদে সভরেন গোল্ড বন্ড (SGB), দ্রুত কেনাবেচার জন্য গোল্ড ইটিএফ (Gold ETF), আর অল্প পরিমাণে জমাতে ডিজিটাল গোল্ড সবচেয়ে উপযোগী।</p>
    </div>
  </section>

  <!-- ========================================== -->
  <!-- SECTION 2: আজকের রূপোর দাম (কলকাতা)       -->
  <!-- ========================================== -->
  <section id="silver-section" class="market-section">
    <h2 class="section-main-heading">
      আজকের রূপোর দাম ({{ $todayDateBn }}): কলকাতায় প্রতি গ্রাম থেকে প্রতি কেজি সম্পূর্ণ রেট
    </h2>
    <div class="update-pill-bar">
      <i class="far fa-clock"></i> সর্বশেষ আপডেট: {{ $updatedTimeBn }}
    </div>

    <p class="section-intro-text">
      আজকের রূপোর দাম জানা প্রয়োজন যাঁদের রূপোর গহনা, বাসনপত্র, কয়েন বা বিনিয়োগের জন্য রূপোর বার কিনতে চান তাঁদের জন্য। সোনার তুলনায় রূপো তুলনামূলক সাশ্রয়ী হওয়ায় এটি সাধারণ মানুষের কাছে জনপ্রিয় একটি ধাতু, বিশেষত পুজো-পার্বণ ও উপহার দেওয়ার ক্ষেত্রে। এই পেজে কলকাতার আজকের রূপোর দাম, ওজন অনুযায়ী সম্পূর্ণ রেট চার্ট, শহরভিত্তিক তুলনা এবং রূপোয় বিনিয়োগের খুঁটিনাটি দেওয়া হলো।
    </p>

    <!-- সূচিপত্র -->
    <div class="toc-box">
      <div class="toc-title"><i class="fas fa-list-ol" style="color: #94a3b8;"></i> সূচিপত্র - রূপোর বাজার দর</div>
      <ol class="toc-list">
        <li><a href="#silver-live">আজকের রূপোর লাইভ রেট</a></li>
        <li><a href="#silver-weights">কলকাতায় ওজন অনুযায়ী রূপোর দর তালিকা</a></li>
        <li><a href="#silver-cities">ভারতের প্রধান শহরে রূপোর দর তুলনা</a></li>
        <li><a href="#silver-factors">রূপোর দাম ওঠানামার কারণ</a></li>
        <li><a href="#silver-why-buy">রূপো কেন কেনা হয়?</a></li>
        <li><a href="#silver-invest">রূপোয় বিনিয়োগের উপায়</a></li>
        <li><a href="#silver-checklist">রূপো কেনার আগে যা যাচাই করবেন</a></li>
        <li><a href="#silver-faq">প্রায়শই জিজ্ঞাসিত প্রশ্ন (FAQ)</a></li>
      </ol>
    </div>

    <!-- ১. আজকের রূপোর লাইভ রেট -->
    <div id="silver-live" class="section-subheading">
      <i class="fas fa-ring" style="color: #94a3b8;"></i> ১. আজকের রূপোর লাইভ রেট
    </div>
    <div class="market-table-wrapper">
      <table class="custom-market-table">
        <thead>
          <tr>
            <th>বিষয়</th>
            <th>আজকের দর</th>
            <th>পরিবর্তন</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>রূপোর দর (১ কেজি)</strong></td>
            <td><strong style="color: #38bdf8; font-size: 1.05rem;">₹{{ $marketData['silver_1kg_formatted'] ?? '৯৫,৬৮০' }}</strong></td>
            <td style="color: {{ ($marketData['silver_is_positive'] ?? true) ? '#10b981' : '#ef4444' }}; font-weight: 700;">
              {{ $marketData['silver_change_formatted'] ?? '+১,১৮০' }}
            </td>
          </tr>
          <tr>
            <td><strong>রূপোর দর (১০ গ্রাম)</strong></td>
            <td><strong>₹{{ $marketData['silver_10g_formatted'] ?? '৯৫৭' }}</strong></td>
            <td style="color: {{ ($marketData['silver_is_positive'] ?? true) ? '#10b981' : '#ef4444' }};">
              {{ ($marketData['silver_is_positive'] ?? true) ? '+' : '' }}₹{{ BengaliHelper::toBengaliNumerals(number_format(abs($marketData['silver_change']) / 100, 1)) }}
            </td>
          </tr>
          <tr>
            <td><strong>রূপোর দর (১ গ্রাম)</strong></td>
            <td><strong>₹{{ BengaliHelper::toBengaliNumerals(number_format($marketData['silver_1kg'] / 1000, 1)) }}</strong></td>
            <td style="color: {{ ($marketData['silver_is_positive'] ?? true) ? '#10b981' : '#ef4444' }};">
              {{ ($marketData['silver_is_positive'] ?? true) ? '+' : '' }}₹{{ BengaliHelper::toBengaliNumerals(number_format(abs($marketData['silver_change']) / 1000, 2)) }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="summary-bar {{ ($marketData['silver_is_positive'] ?? true) ? '' : 'down' }}">
      <i class="fas {{ ($marketData['silver_is_positive'] ?? true) ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
      <strong>সারসংক্ষেপ:</strong> আজ রূপোর দাম আগের দিনের তুলনায় প্রতি কেজিতে ₹{{ BengaliHelper::toBengaliNumerals(number_format(abs($marketData['silver_change']))) }} {{ ($marketData['silver_is_positive'] ?? true) ? 'বেড়েছে' : 'কমেছে' }}, অর্থাৎ প্রবণতা {{ ($marketData['silver_is_positive'] ?? true) ? 'ঊর্ধ্বমুখী' : 'নিম্নমুখী' }}।
    </div>

    <!-- ২. কলকাতায় ওজন অনুযায়ী রূপোর দর তালিকা -->
    <div id="silver-weights" class="section-subheading">
      <i class="fas fa-weight-hanging" style="color: #94a3b8;"></i> ২. কলকাতায় ওজন অনুযায়ী রূপোর দর তালিকা
      <span style="font-size: 0.8rem; font-weight: normal; color: var(--text-muted); margin-left: 8px;">(জিএসটি বাদে)</span>
    </div>
    <div class="market-table-wrapper">
      <table class="custom-market-table">
        <thead>
          <tr>
            <th>ওজন (পরিমাণ)</th>
            <th>আজকের রূপোর দাম</th>
            <th>গত দিনের তুলনায় পরিবর্তন</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>১ গ্রাম রূপো</strong></td>
            <td>₹{{ BengaliHelper::toBengaliNumerals(number_format($marketData['silver_1kg'] / 1000, 1)) }}</td>
            <td style="color: {{ ($marketData['silver_is_positive'] ?? true) ? '#10b981' : '#ef4444' }};">
              {{ ($marketData['silver_is_positive'] ?? true) ? '+' : '' }}₹{{ BengaliHelper::toBengaliNumerals(number_format(abs($marketData['silver_change']) / 1000, 2)) }}
            </td>
          </tr>
          <tr>
            <td><strong>১০ গ্রাম রূপো</strong></td>
            <td>₹{{ $marketData['silver_10g_formatted'] ?? '৯৫৭' }}</td>
            <td style="color: {{ ($marketData['silver_is_positive'] ?? true) ? '#10b981' : '#ef4444' }};">
              {{ ($marketData['silver_is_positive'] ?? true) ? '+' : '' }}₹{{ BengaliHelper::toBengaliNumerals(number_format(abs($marketData['silver_change']) / 100, 1)) }}
            </td>
          </tr>
          <tr>
            <td><strong>১০০ গ্রাম রূপো</strong></td>
            <td>₹{{ BengaliHelper::toBengaliNumerals(number_format(round($marketData['silver_1kg'] / 10))) }}</td>
            <td style="color: {{ ($marketData['silver_is_positive'] ?? true) ? '#10b981' : '#ef4444' }};">
              {{ ($marketData['silver_is_positive'] ?? true) ? '+' : '' }}₹{{ BengaliHelper::toBengaliNumerals(number_format(round(abs($marketData['silver_change']) / 10))) }}
            </td>
          </tr>
          <tr>
            <td><strong>৫০০ গ্রাম রূপো</strong></td>
            <td>₹{{ BengaliHelper::toBengaliNumerals(number_format(round($marketData['silver_1kg'] / 2))) }}</td>
            <td style="color: {{ ($marketData['silver_is_positive'] ?? true) ? '#10b981' : '#ef4444' }};">
              {{ ($marketData['silver_is_positive'] ?? true) ? '+' : '' }}₹{{ BengaliHelper::toBengaliNumerals(number_format(round(abs($marketData['silver_change']) / 2))) }}
            </td>
          </tr>
          <tr>
            <td><strong>১ কেজি রূপো (বার)</strong></td>
            <td><strong style="color: #38bdf8;">₹{{ $marketData['silver_1kg_formatted'] ?? '৯৫,৬৮০' }}</strong></td>
            <td style="color: {{ ($marketData['silver_is_positive'] ?? true) ? '#10b981' : '#ef4444' }}; font-weight: 700;">
              {{ $marketData['silver_change_formatted'] ?? '+১,১৮০' }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="info-box">
      <i class="fas fa-info-circle" style="color: #3b82f6;"></i>
      <strong>মনে রাখবেন:</strong> এই দর খাঁটি রূপোর বুলিয়ন রেটের ভিত্তিতে। রূপোর গহনা বা বাসনপত্র কেনার সময় মেকিং চার্জ ও ৩% জিএসটি বাড়তি প্রযোজ্য হবে।
    </div>

    <!-- ৩. ভারতের প্রধান শহরে রূপোর দর তুলনা -->
    <div id="silver-cities" class="section-subheading">
      <i class="fas fa-map-location-dot" style="color: #ef4444;"></i> ৩. ভারতের প্রধান শহরে রূপোর দর তুলনা
      <span style="font-size: 0.8rem; font-weight: normal; color: var(--text-muted); margin-left: 8px;">(১ কেজি রূপোর হিসেবে)</span>
    </div>
    <div class="market-table-wrapper">
      <table class="custom-market-table">
        <thead>
          <tr>
            <th>শহর</th>
            <th>১ কেজি রূপোর দাম</th>
          </tr>
        </thead>
        <tbody>
          @foreach($cityRates as $city)
            <tr>
              <td><strong>{{ $city['city_bn'] }}</strong> ({{ $city['city_en'] }})</td>
              <td>₹{{ BengaliHelper::toBengaliNumerals(number_format($city['silver_1kg'])) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <p style="font-size: 0.88rem; color: var(--text-muted); margin-top: 6px;">
      *দক্ষিণ ভারতের শহরগুলিতে (যেমন চেন্নাই) রূপোর দাম তুলনামূলক বেশি থাকে, কারণ সেখানে রূপোর বাসনপত্র ও গহনার ঐতিহ্যগত চাহিদা বেশি। শহরভেদে পার্থক্যের অন্য কারণ হলো স্থানীয় করব্যবস্থা ও পরিবহন খরচ।
    </p>

    <!-- ৪. রূপোর দাম ওঠানামার কারণ -->
    <div id="silver-factors" class="section-subheading">
      <i class="fas fa-chart-pie" style="color: #10b981;"></i> ৪. রূপোর দাম ওঠানামার কারণ
    </div>
    <div class="feature-cards-grid">
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #3b82f6;"><i class="fas fa-earth-americas"></i> আন্তর্জাতিক বুলিয়ন মার্কেট</h3>
        <p class="feature-card-desc">সোনার মতোই রূপোর দামও আন্তর্জাতিক বাজারের অপরিশোধিত ধাতুর কেনাবেচার ওপর সরাসরি নির্ভরশীল।</p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #10b981;"><i class="fas fa-industry"></i> শিল্পক্ষেত্রে ব্যাপক ব্যবহার</h3>
        <p class="feature-card-desc">ইলেকট্রনিক্স, সোলার প্যানেল, বৈদ্যুতিক গাড়ি (EV) এবং ওষুধ শিল্পে রূপার বিপুল ব্যবহার থাকায় শিল্প-চাহিদা দামে বড় প্রভাব ফেলে।</p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #f59e0b;"><i class="fas fa-money-bill-transfer"></i> ডলার-টাকার বিনিময় হার</h3>
        <p class="feature-card-desc">ডলারের বিপরীতে টাকার দরপতন হলে আমদানি ব্যয় বৃদ্ধি পায়, যার ফলে দেশীয় বাজারে রূপোর দাম বাড়ে।</p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #ec4899;"><i class="fas fa-hands-praying"></i> উৎসবের চাহিদা</h3>
        <p class="feature-card-desc">ধনতেরাস, লক্ষ্মীপুজো, কালীপুজো এবং বিয়ের মরসুমে রূপোর কয়েন, বাসনপত্র ও উপহারের সামগ্রীর চাহিদা বাড়ে।</p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #8b5cf6;"><i class="fas fa-shield-alt"></i> মুদ্রাস্ফীতি ও নিরাপদ বিনিয়োগ</h3>
        <p class="feature-card-desc">সোনার মতো রূপোকেও বহু ক্ষুদ্র ও মাঝারি বিনিয়োগকারী দীর্ঘমেয়াদে মুদ্রাস্ফীতির বিরুদ্ধে সুরক্ষা হিসেবে বেছে নেন।</p>
      </div>
    </div>

    <!-- ৫. রূপো কেন কেনা হয়? -->
    <div id="silver-why-buy" class="section-subheading">
      <i class="fas fa-shopping-basket" style="color: #6366f1;"></i> ৫. রূপো কেন কেনা হয়?
    </div>
    <div class="feature-cards-grid">
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #94a3b8;"><i class="fas fa-gem"></i> গহনা</h3>
        <p class="feature-card-desc">রূপোর নূপুর, চুড়ি, আংটি, ব্রেসলেট ইত্যাদি ফ্যাশনেবল ও দৈনন্দিন ব্যবহারের জন্য অত্যন্ত জনপ্রিয়।</p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #eab308;"><i class="fas fa-utensils"></i> বাসনপত্র ও পুজোর সামগ্রী</h3>
        <p class="feature-card-desc">রূপোর থালা, গ্লাস, চামচ, প্রদীপ ও প্রতিমা পুজোর কাজে এবং নবজাতক বা বিয়েতে উপহার হিসেবে ব্যবহৃত হয়।</p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #10b981;"><i class="fas fa-circle-dot"></i> শুভ কয়েন</h3>
        <p class="feature-card-desc">ধনতেরাস, দিওয়ালি ও লক্ষ্মীপুজোয় মা লক্ষ্মী ও গণেশ খোদাই করা রূপোর কয়েন কেনা শুভ ও মঙ্গলজনক বলে গণ্য করা হয়।</p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #3b82f6;"><i class="fas fa-vault"></i> বিকল্প বিনিয়োগ</h3>
        <p class="feature-card-desc">রূপোর বার ও সিলভার ইটিএফ সোনার তুলনায় কম দামে কেনা যায় বলে ছোট ও মাঝারি বিনিয়োগকারীরা রূপোকে প্রাধান্য দেন।</p>
      </div>
    </div>

    <!-- ৬. রূপোয় বিনিয়োগের উপায় -->
    <div id="silver-invest" class="section-subheading">
      <i class="fas fa-chart-simple" style="color: #10b981;"></i> ৬. রূপোয় বিনিয়োগের উপায়
    </div>
    <div class="feature-cards-grid">
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #94a3b8;"><i class="fas fa-cubes"></i> ফিজিক্যাল সিলভার (কয়েন ও বার)</h3>
        <p class="feature-card-desc">জুয়েলার্স বা অনুমোদিত প্রতিষ্ঠান থেকে সরাসরি কেনা যায়, তবে নিরাপদে সংরক্ষণে লকার বা সুরক্ষার প্রয়োজন হয়।</p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #3b82f6;"><i class="fas fa-chart-line"></i> সিলভার ইটিএফ (Silver ETF)</h3>
        <p class="feature-card-desc">স্টক এক্সচেঞ্জে সরাসরি কেনাবেচা করা যায়, ডিম্যাট অ্যাকাউন্টে নিরাপদ থাকে এবং চুরি বা শারীরিক সংরক্ষণের ঝামেলা নেই।</p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #8b5cf6;"><i class="fas fa-mobile-button"></i> ডিজিটাল সিলভার</h3>
        <p class="feature-card-desc">অনলাইন ইনভেস্টমেন্ট অ্যাপের মাধ্যমে অল্প টাকায় ডিজিটাল রূপো কেনা যায়, যা নিরাপদ ভল্টে সংরক্ষিত থাকে।</p>
      </div>
    </div>

    <!-- ৭. রূপো কেনার আগে যা যাচাই করবেন -->
    <div id="silver-checklist" class="section-subheading">
      <i class="fas fa-clipboard-check" style="color: #ef4444;"></i> ৭. রূপো কেনার আগে যা যাচাই করবেন
    </div>
    <div class="feature-cards-grid">
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #10b981;"><i class="fas fa-stamp"></i> বিশুদ্ধতার সার্টিফিকেট</h3>
        <p class="feature-card-desc">রূপোর বার বা কয়েনের ক্ষেত্রে ৯৯৯ বা ৯২৫ (স্টার্লিং সিলভার) বিশুদ্ধতার অফিশিয়াল হলমার্ক সিলমোহর যাচাই করুন।</p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #3b82f6;"><i class="fas fa-scale-unbalanced"></i> ওজন যাচাই করুন</h3>
        <p class="feature-card-desc">কেনার সময় ইলেকট্রনিক ওজন স্কেলে জুয়েলারির সঠিক গ্রাম এবং মিলিগ্রাম ওজন নিশ্চিত করে নিন।</p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #eab308;"><i class="fas fa-receipt"></i> পাকা ট্যাক্স বিল সংগ্রহ করুন</h3>
        <p class="feature-card-desc">ওজন, বিশুদ্ধতা, মেকিং চার্জ এবং ৩% জিএসটি উল্লেখ থাকা সরকারি ট্যাক্স ইনভয়েস বিল অবশ্যই সাথে নিন।</p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #f59e0b;"><i class="fas fa-shield"></i> কালচে দাগ সম্পর্কে জানুন</h3>
        <p class="feature-card-desc">রূপো বাতাসের সালফারের সংস্পর্শে কালচে হয়ে যেতে পারে, এটি একটি স্বাভাবিক রাসায়নিক প্রক্রিয়া (Oxidation) এবং এতে বিশুদ্ধতা নষ্ট হয় না।</p>
      </div>
    </div>

    <!-- ৮. প্রায়শই জিজ্ঞাসিত প্রশ্ন (FAQ - Silver) -->
    <div id="silver-faq" class="section-subheading">
      <i class="fas fa-circle-question" style="color: #06b6d4;"></i> ৮. প্রায়শই জিজ্ঞাসিত প্রশ্ন (FAQ) - রূপো
    </div>
    <div class="faq-item">
      <div class="faq-item-q"><i class="fas fa-caret-right" style="color: #94a3b8;"></i> রূপোর দাম প্রতিদিন কেন পরিবর্তন হয়?</div>
      <p class="faq-item-a">আন্তর্জাতিক বুলিয়ন বাজারের দর, ডলারের বিপরীতে ভারতীয় টাকার বিনিময় হার, শিল্পক্ষেত্রে রূপোর ব্যবহার এবং দেশীয় উৎসব-বিয়ের মরসুমের চাহিদার ওপর নির্ভর করে প্রতিদিন রূপোর দাম পরিবর্তিত হয়।</p>
    </div>
    <div class="faq-item">
      <div class="faq-item-q"><i class="fas fa-caret-right" style="color: #94a3b8;"></i> ১ কেজি রূপোর দাম আজ কত?</div>
      <p class="faq-item-a">আজ ১ কেজি রূপোর দাম ₹{{ $marketData['silver_1kg_formatted'] ?? '৯৫,৬৮০' }} (জিএসটি বাদে), যা আগের দিনের তুলনায় {{ $marketData['silver_change_formatted'] ?? '+১,১৮০' }} টাকা {{ ($marketData['silver_is_positive'] ?? true) ? 'বেশি' : 'কম' }}।</p>
    </div>
    <div class="faq-item">
      <div class="faq-item-q"><i class="fas fa-caret-right" style="color: #94a3b8;"></i> রূপো কেনার সময় কত শতাংশ জিএসটি দিতে হয়?</div>
      <p class="faq-item-a">ভারতে রূপোর বার, কয়েন ও গহনা ক্রয়ের ক্ষেত্রে সরকার কর্তৃক ৩% পণ্য ও পরিষেবা কর (GST) আরোপিত হয়। এছাড়া মেকিং চার্জের ওপর আলাদাভাবে ৫% জিএসটি ধার্য হয়।</p>
    </div>
    <div class="faq-item">
      <div class="faq-item-q"><i class="fas fa-caret-right" style="color: #94a3b8;"></i> সোনা নাকি রূপো, কোনটায় বিনিয়োগ করা ভালো?</div>
      <p class="faq-item-a">সোনা তুলনামূলকভাবে বেশি স্থিতিশীল এবং দীর্ঘমেয়াদে নিরাপদ বিনিয়োগ হিসেবে বিবেচিত। অন্যদিকে রূপো কম দামে পাওয়া যায় বলে ছোট বিনিয়োগকারীদের জন্য সুবিধাজনক, তবে শিল্প-চাহিদার ওপর বেশি নির্ভরশীল হওয়ায় রূপোর দামে কিছুটা বেশি ওঠানামা দেখা যেতে পারে।</p>
    </div>
    <div class="faq-item">
      <div class="faq-item-q"><i class="fas fa-caret-right" style="color: #94a3b8;"></i> রূপোর গহনা কালচে হয়ে গেলে কী করব?</div>
      <p class="faq-item-a">এটি বাতাসের সংস্পর্শে আসা একটি সম্পূর্ণ স্বাভাবিক অক্সিডেশন প্রক্রিয়া, বিশুদ্ধতার সমস্যা নয়। নরম সুতি কাপড় ও উপযুক্ত সিলভার ক্লিনিং তরল বা বেকিং সোডার সাহায্যে খুব সহজেই তা পুনরায় পরিষ্কার ও চকচকে করা যায়।</p>
    </div>
  </section>

  <!-- ========================================== -->
  <!-- SECTION 3: সেনসেক্স ও নিফটি ৫০ (NSE / BSE)  -->
  <!-- ========================================== -->
  <section id="stocks-section" class="market-section">
    <h2 class="section-main-heading">
      আজকের সেনসেক্স ও নিফটি ৫০ ({{ $todayDateBn }}): বিএসই-এনএসই সূচকের সম্পূর্ণ আপডেট
    </h2>
    <div class="update-pill-bar">
      <i class="far fa-clock"></i> সর্বশেষ আপডেট: {{ $updatedTimeBn }}
    </div>

    <p class="section-intro-text">
      আজকের সেনসেক্স ও নিফটি ৫০ সূচকের সর্বশেষ অবস্থা, শীর্ষস্থানীয় ভারতীয় কোম্পানিগুলোর শেয়ারের দর এবং বাজারের সার্বিক গতিপ্রকৃতি জানতে এই পেজটি দেখুন। শেয়ার বাজারের ওঠানামা শুধু শেয়ার বিনিয়োগকারীদের জন্যই নয়, সোনা-রূপোর মতো বিকল্প নিরাপদ বিনিয়োগের সিদ্ধান্তের ক্ষেত্রেও অত্যন্ত গুরুত্বপূর্ণ একটি সূচক।
    </p>

    <!-- সূচিপত্র -->
    <div class="toc-box">
      <div class="toc-title"><i class="fas fa-list-ol" style="color: #3b82f6;"></i> সূচিপত্র - শেয়ার বাজার ও সেনসেক্স</div>
      <ol class="toc-list">
        <li><a href="#stocks-status">আজকের সূচকের অবস্থা</a></li>
        <li><a href="#stocks-table">শীর্ষ ১০টি কোম্পানির শেয়ারের দর</a></li>
        <li><a href="#stocks-meaning">সেনসেক্স ও নিফটি ৫০ কী?</a></li>
        <li><a href="#stocks-factors">বাজারের ওঠানামার কারণ</a></li>
        <li><a href="#stocks-gold-relation">সোনা ও শেয়ার বাজারের সম্পর্ক</a></li>
        <li><a href="#stocks-glossary">শব্দকোষ</a></li>
        <li><a href="#stocks-faq">প্রায়শই জিজ্ঞাসিত প্রশ্ন (FAQ)</a></li>
      </ol>
    </div>

    <!-- ১. আজকের সূচকের অবস্থা -->
    <div id="stocks-status" class="section-subheading">
      <i class="fas fa-chart-line" style="color: #3b82f6;"></i> ১. আজকের সূচকের অবস্থা
    </div>
    <div class="market-table-wrapper">
      <table class="custom-market-table">
        <thead>
          <tr>
            <th>সূচক</th>
            <th>আজকের মান</th>
            <th>পরিবর্তন</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>বিএসই সেনসেক্স (BSE Sensex)</strong></td>
            <td><strong style="color: {{ ($marketData['sensex']['is_positive'] ?? true) ? '#10b981' : '#ef4444' }}; font-size: 1.05rem;">{{ $marketData['sensex']['price_formatted'] ?? '৭৩,৮৯৫.৭৪' }}</strong></td>
            <td style="color: {{ ($marketData['sensex']['is_positive'] ?? true) ? '#10b981' : '#ef4444' }}; font-weight: 700;">
              {{ $marketData['sensex']['change_formatted'] ?? '+৩১৫.২০' }} ({{ $marketData['sensex']['change_percent_formatted'] ?? '+০.৪৩%' }})
            </td>
          </tr>
          <tr>
            <td><strong>এনএসই নিফটি ৫০ (Nifty 50)</strong></td>
            <td><strong style="color: {{ ($marketData['nifty']['is_positive'] ?? true) ? '#10b981' : '#ef4444' }}; font-size: 1.05rem;">{{ $marketData['nifty']['price_formatted'] ?? '২৩,১৪০.৫০' }}</strong></td>
            <td style="color: {{ ($marketData['nifty']['is_positive'] ?? true) ? '#10b981' : '#ef4444' }}; font-weight: 700;">
              {{ $marketData['nifty']['change_formatted'] ?? '+৭৭.৪০' }} ({{ $marketData['nifty']['change_percent_formatted'] ?? '+০.৩৪%' }})
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    @php
      $sensexPositive = $marketData['sensex']['is_positive'] ?? true;
      $niftyPositive = $marketData['nifty']['is_positive'] ?? true;
      $marketTrend = ($sensexPositive && $niftyPositive) ? 'সবুজ জোনে অর্থাৎ ঊর্ধ্বমুখী প্রবণতায় রয়েছে' : ((!$sensexPositive && !$niftyPositive) ? 'লাল জোনে অর্থাৎ নিম্নমুখী প্রবণতায় রয়েছে' : 'মিশ্র প্রবণতায় রয়েছে');
    @endphp
    <div class="summary-bar {{ ($sensexPositive || $niftyPositive) ? '' : 'down' }}">
      <i class="fas {{ ($sensexPositive || $niftyPositive) ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
      <strong>সারসংক্ষেপ:</strong> আজ সূচক {{ $marketTrend }}।
    </div>

    <!-- ২. শীর্ষ ১০টি কোম্পানির শেয়ারের দর -->
    <div id="stocks-table" class="section-subheading">
      <i class="fas fa-building" style="color: #10b981;"></i> ২. শীর্ষ ১০টি কোম্পানির শেয়ারের দর (NSE / BSE)
      <span style="font-size: 0.8rem; font-weight: 600; color: #10b981; margin-left: 8px;"><i class="fas fa-bolt"></i> লাইভ আপডেট</span>
    </div>
    <div class="market-table-wrapper">
      <table class="custom-market-table">
        <thead>
          <tr>
            <th>র‍্যাঙ্ক</th>
            <th>কোম্পানির নাম</th>
            <th>স্টক সিম্বল</th>
            <th>বর্তমান মূল্য (LTP)</th>
            <th>পরিবর্তন</th>
            <th>শতাংশ (%)</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($topStocksData['stocks']))
            @foreach($topStocksData['stocks'] as $stock)
              <tr>
                <td><strong>#{{ $stock['rank_bn'] }}</strong></td>
                <td><strong>{{ $stock['name_bn'] }}</strong> <span style="font-size: 0.8rem; color: var(--text-muted);">({{ $stock['name_en'] }})</span></td>
                <td><code>{{ $stock['symbol'] }}</code></td>
                <td><strong>{{ $stock['price_formatted'] }}</strong></td>
                <td style="color: {{ $stock['is_positive'] ? '#10b981' : '#ef4444' }}; font-weight: 700;">
                  {{ $stock['change_formatted'] }}
                </td>
                <td>
                  <span class="card-change-badge {{ $stock['is_positive'] ? 'positive' : 'negative' }}">
                    {{ $stock['change_percent_formatted'] }}
                  </span>
                </td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>

    <div class="info-box">
      <i class="fas fa-chart-simple" style="color: #3b82f6;"></i>
      <strong>বাজার বিশ্লেষণ:</strong>
      @if(!empty($topGainer))
        আজকের বাজারে সবচেয়ে উল্লেখযোগ্য উত্থান দেখা গেছে <strong>{{ $topGainer['name_bn'] }}</strong> ({{ $topGainer['change_percent_formatted'] }})@if(!empty($topStocksData['stocks'][3]) && $topStocksData['stocks'][3]['symbol'] !== $topGainer['symbol']) এবং <strong>{{ $topStocksData['stocks'][3]['name_bn'] }}</strong> ({{ $topStocksData['stocks'][3]['change_percent_formatted'] }})@endif, অন্যদিকে @if(!empty($topLoser)) <strong>{{ $topLoser['name_bn'] }}</strong> ({{ $topLoser['change_percent_formatted'] }})@endif-এর শেয়ারে সংশোধন বা পতন লক্ষ করা গেছে।
      @else
        আজকের বাজারে শীর্ষ ব্লু-চিপ শেয়ারগুলোতে সক্রিয় লেনদেন পরিলক্ষিত হচ্ছে।
      @endif
    </div>

    <!-- ৩. সেনসেক্স ও নিফটি ৫০ কী? -->
    <div id="stocks-meaning" class="section-subheading">
      <i class="fas fa-circle-info" style="color: #6366f1;"></i> ৩. সেনসেক্স ও নিফটি ৫০ কী?
    </div>
    <div class="feature-cards-grid">
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #3b82f6;"><i class="fas fa-chart-column"></i> বিএসই সেনসেক্স (BSE Sensex)</h3>
        <p class="feature-card-desc">
          বম্বে স্টক এক্সচেঞ্জ (BSE)-এর ৩০টি সবচেয়ে বড়, প্রতিষ্ঠিত ও আর্থিকভাবে শক্তিশালী কোম্পানির শেয়ার নিয়ে গঠিত একটি প্রধান বেঞ্চমার্ক সূচক, যা ভারতীয় অর্থনীতির সার্বিক স্বাস্থ্যের অন্যতম প্রতিফলন।
        </p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #8b5cf6;"><i class="fas fa-chart-line"></i> এনএসই নিফটি ৫০ (Nifty 50)</h3>
        <p class="feature-card-desc">
          ন্যাশনাল স্টক এক্সচেঞ্জ (NSE)-এর ৫০টি শীর্ষস্থানীয় ব্লু-চিপ কোম্পানির শেয়ার নিয়ে গঠিত জাতীয় সূচক, যা ভারতীয় অর্থনীতির বিভিন্ন গুরুত্বপূর্ণ সেক্টরের প্রতিনিধিত্ব করে।
        </p>
      </div>
    </div>

    <!-- ৪. বাজারের ওঠানামার কারণ -->
    <div id="stocks-factors" class="section-subheading">
      <i class="fas fa-arrows-split-up-and-left" style="color: #ef4444;"></i> ৪. বাজারের ওঠানামার কারণ
    </div>
    <div class="feature-cards-grid">
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #3b82f6;"><i class="fas fa-globe"></i> আন্তর্জাতিক বাজারের প্রভাব</h3>
        <p class="feature-card-desc">মার্কিন শেয়ার বাজার (ওয়াল স্ট্রিট) এবং এশীয় শেয়ার বাজারের গতিবিধি ভারতীয় বাজারকেও দারুণভাবে প্রভাবিত করে।</p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #10b981;"><i class="fas fa-users-viewfinder"></i> প্রাতিষ্ঠানিক বিনিয়োগকারী (FII/DII)</h3>
        <p class="feature-card-desc">বিদেশি প্রাতিষ্ঠানিক বিনিয়োগকারী (FII) এবং দেশীয় ফান্ড হাউজগুলোর কেনাবেচার পরিমাণের ওপর সূচকে বড় ধরনের প্রভাব পড়ে।</p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #f59e0b;"><i class="fas fa-file-invoice"></i> কোম্পানির ত্রৈমাসিক আর্থিক ফলাফল</h3>
        <p class="feature-card-desc">ভারী ওজনের শীর্ষ কোম্পানিগুলোর কোয়ার্টারলি আয়ের রিপোর্ট ও মুনাফার ঘোষণা সংশ্লিষ্ট শেয়ার এবং সমগ্র সূচককে নাড়া দেয়।</p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #8b5cf6;"><i class="fas fa-building-columns"></i> সরকারি নীতি ও রিজার্ভ ব্যাংক (RBI)</h3>
        <p class="feature-card-desc">সুদের হার (রেপো রেট) পরিবর্তন, মুদ্রানীতি বা নতুন সরকারি অর্থনৈতিক নীতি ঘোষণা বাজারের তারল্য ও সেন্টিমেন্ট বদলে দেয়।</p>
      </div>
      <div class="feature-card-item">
        <h3 class="feature-card-title" style="color: #ef4444;"><i class="fas fa-triangle-exclamation"></i> বৈশ্বিক ভূরাজনৈতিক অনিশ্চয়তা</h3>
        <p class="feature-card-desc">ভূরাজনৈতিক উত্তেজনা বা অপরিশোধিত ক্রুড অয়েলের দামের আকস্মিক ওঠানামা ভারতীয় বাজারকে সরাসরি স্পর্শ করে।</p>
      </div>
    </div>

    <!-- ৫. সোনা ও শেয়ার বাজারের সম্পর্ক -->
    <div id="stocks-gold-relation" class="section-subheading">
      <i class="fas fa-scale-balanced" style="color: #eab308;"></i> ৫. সোনা ও শেয়ার বাজারের সম্পর্ক
    </div>
    <div class="info-box" style="background: rgba(234, 179, 8, 0.07); border-color: rgba(234, 179, 8, 0.25);">
      <p style="margin: 0; color: var(--text-main); font-size: 0.94rem; line-height: 1.75;">
        শেয়ার বাজার অস্থির বা মন্দাক্রান্ত থাকলে বিনিয়োগকারীরা প্রায়ই তাদের মূলধনের সুরক্ষার জন্য নিরাপদ আশ্রয় হিসেবে সোনার দিকে ঝোঁকেন, অর্থনীতিতে যাকে বলা হয় <strong>“সেফ হেভেন ডিমান্ড” (Safe Haven Demand)</strong>। এই কারণে সেনসেক্স বা নিফটিতে বড় পতন বা বৈশ্বিক মন্দার সময় প্রায়শই সোনার দামে বিপরীত ঊর্ধ্বমুখী প্রবণতা লক্ষ করা যায়। অপরদিকে, যখন শেয়ার বাজার চাঙ্গা থাকে ও অর্থনীতি প্রবৃদ্ধির ধারায় থাকে, তখন বিনিয়োগকারীরা ইক্যুইটিতে বেশি মনোযোগ দেন, যা স্বল্পমেয়াদে সোনার মূল্যের গতি শ্লথ করতে পারে।
      </p>
    </div>

    <!-- ৬. শব্দকোষ -->
    <div id="stocks-glossary" class="section-subheading">
      <i class="fas fa-book-bookmark" style="color: #3b82f6;"></i> ৬. শেয়ার বাজারের শব্দকোষ
    </div>
    <div class="market-table-wrapper">
      <table class="custom-market-table">
        <thead>
          <tr>
            <th>শব্দ</th>
            <th>অর্থ ও বিবরণ</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>LTP</strong></td>
            <td>Last Traded Price: শেয়ারের এক্সচেঞ্জে সর্বশেষ লেনদেন হওয়া বাস্তব বাজারদর।</td>
          </tr>
          <tr>
            <td><strong>BSE</strong></td>
            <td>Bombay Stock Exchange: এশিয়ার প্রাচীনতম স্টক এক্সচেঞ্জ (১৮৭৫ সালে প্রতিষ্ঠিত)।</td>
          </tr>
          <tr>
            <td><strong>NSE</strong></td>
            <td>National Stock Exchange: ভারতের বৃহত্তম অত্যাধুনিক ইলেকট্রনিক স্টক এক্সচেঞ্জ।</td>
          </tr>
          <tr>
            <td><strong>FII</strong></td>
            <td>Foreign Institutional Investor: ভারতে বিনিয়োগকারী বিদেশি প্রাতিষ্ঠানিক তহবিল ও সংস্থা।</td>
          </tr>
          <tr>
            <td><strong>DII</strong></td>
            <td>Domestic Institutional Investor: ভারতীয় মিউচুয়াল ফান্ড, এলআইসি বা আর্থিক প্রতিষ্ঠান।</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- ৭. প্রায়শই জিজ্ঞাসিত প্রশ্ন (FAQ - Share Market) -->
    <div id="stocks-faq" class="section-subheading">
      <i class="fas fa-circle-question" style="color: #06b6d4;"></i> ৭. প্রায়শই জিজ্ঞাসিত প্রশ্ন (FAQ) - শেয়ার বাজার
    </div>
    <div class="faq-item">
      <div class="faq-item-q"><i class="fas fa-caret-right" style="color: #3b82f6;"></i> সেনসেক্স ও নিফটি ৫০-এর মধ্যে পার্থক্য কী?</div>
      <p class="faq-item-a">সেনসেক্স হলো বম্বে স্টক এক্সচেঞ্জের ৩০টি বড় কোম্পানির সূচক, আর নিফটি ৫০ হলো ন্যাশনাল স্টক এক্সচেঞ্জের ৫০টি শীর্ষস্থানীয় কোম্পানির সূচক। দুটিই ভারতীয় শেয়ার বাজারের সার্বিক প্রবণতা ও স্বাস্থ্য প্রতিফলিত করে।</p>
    </div>
    <div class="faq-item">
      <div class="faq-item-q"><i class="fas fa-caret-right" style="color: #3b82f6;"></i> আজ শেয়ার বাজার কেমন যাচ্ছে?</div>
      <p class="faq-item-a">আজ বিএসই সেনসেক্স {{ $marketData['sensex']['change_formatted'] ?? '+৩১৫.২০' }} পয়েন্ট ({{ $marketData['sensex']['change_percent_formatted'] ?? '+০.৪৩%' }}) এবং এনএসই নিফটি ৫০ {{ $marketData['nifty']['change_formatted'] ?? '+৭৭.৪০' }} পয়েন্ট ({{ $marketData['nifty']['change_percent_formatted'] ?? '+০.৩৪%' }}) {{ ($sensexPositive || $niftyPositive) ? 'বেড়ে ঊর্ধ্বমুখী প্রবণতায় রয়েছে' : 'কমে সংশোধন প্রবণতায় রয়েছে' }}।</p>
    </div>
    <div class="faq-item">
      <div class="faq-item-q"><i class="fas fa-caret-right" style="color: #3b82f6;"></i> শেয়ার বাজারের ওঠানামা সোনার দামকে কীভাবে প্রভাবিত করে?</div>
      <p class="faq-item-a">শেয়ার বাজার অস্থির বা নিম্নমুখী থাকলে বিনিয়োগকারীরা নিরাপদ বিনিয়োগ হিসেবে সোনার দিকে ঝোঁকেন, যার ফলে সোনার চাহিদা ও দাম বাড়তে পারে।</p>
    </div>
    <div class="faq-item">
      <div class="faq-item-q"><i class="fas fa-caret-right" style="color: #3b82f6;"></i> LTP মানে কী?</div>
      <p class="faq-item-a">LTP বা Last Traded Price হলো কোনও শেয়ারের এক্সচেঞ্জে সর্বশেষ সফল লেনদেন হওয়া ক্রয়-বিক্রয় মূল্য, যা শেয়ারের বর্তমান বাজারদর নির্দেশ করে।</p>
    </div>
  </section>

  <!-- ========================================== -->
  <!-- SECTION 4: সার্বিক FAQ ও ডিসক্লেইমার        -->
  <!-- ========================================== -->
  <section id="faq-section" class="market-section" style="margin-bottom: 10px;">
    <h2 class="section-main-heading">
      <i class="fas fa-circle-question" style="color: #06b6d4;"></i> সোনা, রূপো ও বাজার দর সম্পর্কিত শীর্ষ প্রশ্নোত্তর
    </h2>
    <p class="section-intro-text">
      সোনা ও রূপো কেনাকাটা, বিনিয়োগ এবং শেয়ার বাজারের লাইভ আপডেট সংক্রান্ত সবচেয়ে গুরুত্বপূর্ণ জিজ্ঞাসা ও নির্ভুল তথ্য একনজরে জেনে নিন।
    </p>

    <div class="faq-item">
      <div class="faq-item-q"><i class="fas fa-coins" style="color: #eab308;"></i> আজকের ২৪ ক্যারেট ও ২২ ক্যারেট সোনার দর কত?</div>
      <p class="faq-item-a">আজ কলকাতায় ১০ গ্রাম ২৪ ক্যারেট সোনার দর ₹{{ $marketData['gold_24k_formatted'] ?? '৭৭,২৬০' }} এবং ১০ গ্রাম ২২ ক্যারেট গহনা সোনার দর ₹{{ $marketData['gold_22k_formatted'] ?? '৭০,৮২০' }} (১ ভরি: ₹{{ $marketData['gold_22k_bhori_formatted'] ?? '৫৬,৬৬০' }})।</p>
    </div>

    <div class="faq-item">
      <div class="faq-item-q"><i class="fas fa-ring" style="color: #94a3b8;"></i> আজকের ১ কেজি রূপোর দর কত?</div>
      <p class="faq-item-a">আজ কলকাতায় ১ কেজি খাঁটি রূপোর দর ₹{{ $marketData['silver_1kg_formatted'] ?? '৯৫,৬৮০' }} (১০ গ্রাম: ₹{{ $marketData['silver_10g_formatted'] ?? '৯৫৭' }})।</p>
    </div>

    <div class="faq-item">
      <div class="faq-item-q"><i class="fas fa-receipt" style="color: #10b981;"></i> সোনা বা রূপো কেনার সময় সরকারি ট্যাক্স কত প্রযোজ্য?</div>
      <p class="faq-item-a">ভারতে সোনা ও রূপার বার, কয়েন বা গহনার ক্ষেত্রে কেন্দ্রীয় সরকার কর্তৃক ৩% জিএসটি (GST) ধার্য হয় এবং অলঙ্কারের মেকিং চার্জের ওপর আলাদাভাবে ৫% জিএসটি প্রযোজ্য হয়।</p>
    </div>

    <div class="faq-item">
      <div class="faq-item-q"><i class="fas fa-chart-line" style="color: #3b82f6;"></i> সেনসেক্স ও নিফটির আজকের অবস্থান কী?</div>
      <p class="faq-item-a">আজ বিএসই সেনসেক্সের মান {{ $marketData['sensex']['price_formatted'] ?? '৭৩,৮৯৫.৭৪' }} এবং এনএসই নিফটি ৫০-এর মান {{ $marketData['nifty']['price_formatted'] ?? '২৩,১৪০.৫০' }}।</p>
    </div>

    <!-- আইনি ডিসক্লেইমার -->
    <div class="disclaimer-card">
      <i class="fas fa-triangle-exclamation" style="margin-right: 6px;"></i>
      <strong>দ্রষ্টব্য ও আইনি সতর্কতা:</strong> এই পেজে প্রদর্শিত সোনা, রূপো ও শেয়ারের সমস্ত দর ও তথ্য কেবল তথ্যগত ও সাধারণ সচেতনতামূলক উদ্দেশ্যে পরিবেশিত। প্রকৃত ক্রয়ের আগে স্থানীয় অনুমোদিত জুয়েলার্সের সাথে দর নিশ্চিত করুন এবং শেয়ার বা ফান্ডে বিনিয়োগের সিদ্ধান্ত গ্রহণের পূর্বে একজন পেশাদার সার্টিফাইড আর্থিক উপদেষ্টার পরামর্শ নিন।
    </div>
  </section>

</div>

<!-- JSON-LD SEO FAQ Schema -->
@php
  $faqSchemaData = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => [
      [
        '@type' => 'Question',
        'name' => '২৪ ক্যারেট ও ২২ ক্যারেট সোনার মধ্যে পার্থক্য কী?',
        'acceptedAnswer' => [
          '@type' => 'Answer',
          'text' => '২৪ ক্যারেট সোনা ৯৯.৯% খাঁটি সোনা, যা খুব নরম হওয়ায় সাধারণত কয়েন ও বার তৈরিতে ব্যবহৃত হয়। ২২ ক্যারেট সোনায় ৯১.৬% সোনা এবং বাকি অংশ তামা বা দস্তা মিশ্রিত থাকে, যা গহনা তৈরির জন্য মজবুত ও আদর্শ।'
        ]
      ],
      [
        '@type' => 'Question',
        'name' => '১ ভরি বা ১ তোলা সোনা মানে কত গ্রাম?',
        'acceptedAnswer' => [
          '@type' => 'Answer',
          'text' => 'আন্তর্জাতিক মানদণ্ডে ১ ভরি সোনা সমান ১১.৬৬ গ্রাম। ভারতীয় জুয়েলারি বাজারে সুবিধার জন্য ১০ গ্রামকে ১ তোলা হিসেবেও ধরা হয়।'
        ]
      ],
      [
        '@type' => 'Question',
        'name' => 'সোনার দাম প্রতিদিন কেন পরিবর্তন হয়?',
        'acceptedAnswer' => [
          '@type' => 'Answer',
          'text' => 'আন্তর্জাতিক বুলিয়ন মার্কেটের দাম, ডলারের বিপরীতে টাকার বিনিময় হার, অর্থনৈতিক অনিশ্চয়তা, মুদ্রাস্ফীতি এবং উৎসব-বিয়ের মরসুমে চাহিদার ওপর নির্ভর করে সোনার দাম প্রতিদিন পরিবর্তিত হয়।'
        ]
      ],
      [
        '@type' => 'Question',
        'name' => 'সোনা কেনার সময় কত শতাংশ জিএসটি দিতে হয়?',
        'acceptedAnswer' => [
          '@type' => 'Answer',
          'text' => 'সোনা ও রূপোর বার এবং গহনার ওপর ৩% জিএসটি প্রযোজ্য, এবং মেকিং চার্জের ওপর আলাদাভাবে ৫% জিএসটি ধার্য হয়।'
        ]
      ],
      [
        '@type' => 'Question',
        'name' => 'রূপোর দাম প্রতিদিন কেন পরিবর্তন হয়?',
        'acceptedAnswer' => [
          '@type' => 'Answer',
          'text' => 'আন্তর্জাতিক বুলিয়ন বাজারের দর, ডলারের বিপরীতে টাকার বিনিময় হার, শিল্পক্ষেত্রে রূপোর ব্যবহার এবং দেশীয় উৎসবের চাহিদার ওপর নির্ভর করে রূপোর দাম প্রতিদিন পরিবর্তিত হয়।'
        ]
      ],
      [
        '@type' => 'Question',
        'name' => '১ কেজি রূপোর দাম আজ কত?',
        'acceptedAnswer' => [
          '@type' => 'Answer',
          'text' => 'আজ ১ কেজি রূপোর দাম ৯৫,৬৮০ টাকা (জিএসটি বাদে), যা আগের দিনের তুলনায় ১,১৮০ টাকা বেশি।'
        ]
      ],
      [
        '@type' => 'Question',
        'name' => 'রূপো কেনার সময় কত শতাংশ জিএসটি দিতে হয়?',
        'acceptedAnswer' => [
          '@type' => 'Answer',
          'text' => 'রূপোর বার ও গহনার ওপর ৩% জিএসটি প্রযোজ্য, এবং মেকিং চার্জের ওপর আলাদাভাবে ৫% জিএসটি ধার্য হয়।'
        ]
      ],
      [
        '@type' => 'Question',
        'name' => 'সোনা নাকি রূপো, কোনটায় বিনিয়োগ করা ভালো?',
        'acceptedAnswer' => [
          '@type' => 'Answer',
          'text' => 'সোনা তুলনামূলকভাবে বেশি স্থিতিশীল ও দীর্ঘমেয়াদে নিরাপদ, আর রূপো কম দামে পাওয়া যায় বলে ছোট বিনিয়োগকারীদের জন্য সুবিধাজনক, তবে দামে ওঠানামা তুলনামূলক বেশি হতে পারে।'
        ]
      ],
      [
        '@type' => 'Question',
        'name' => 'সেনসেক্স ও নিফটি ৫০-এর মধ্যে পার্থক্য কী?',
        'acceptedAnswer' => [
          '@type' => 'Answer',
          'text' => 'সেনসেক্স হলো বম্বে স্টক এক্সচেঞ্জের ৩০টি বড় কোম্পানির সূচক, আর নিফটি ৫০ হলো ন্যাশনাল স্টক এক্সচেঞ্জের ৫০টি শীর্ষস্থানীয় কোম্পানির সূচক।'
        ]
      ],
      [
        '@type' => 'Question',
        'name' => 'আজ শেয়ার বাজার কেমন যাচ্ছে?',
        'acceptedAnswer' => [
          '@type' => 'Answer',
          'text' => 'আজ বিএসই সেনসেক্স ৩১৫.২০ পয়েন্ট এবং এনএসই নিফটি ৫০ ৭৭.৪০ পয়েন্ট বেড়ে ঊর্ধ্বমুখী প্রবণতায় রয়েছে।'
        ]
      ],
      [
        '@type' => 'Question',
        'name' => 'শেয়ার বাজারের ওঠানামা সোনার দামকে কীভাবে প্রভাবিত করে?',
        'acceptedAnswer' => [
          '@type' => 'Answer',
          'text' => 'শেয়ার বাজার অস্থির বা নিম্নমুখী থাকলে বিনিয়োগকারীরা নিরাপদ বিনিয়োগ হিসেবে সোনার দিকে ঝোঁকেন, যার ফলে সোনার চাহিদা ও দাম বাড়তে পারে।'
        ]
      ]
    ]
  ];
@endphp
<script type="application/ld+json">
{!! json_encode($faqSchemaData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endsection
