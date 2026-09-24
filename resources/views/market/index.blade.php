@extends('layouts.app')

@php
    use App\Helpers\BengaliHelper;
@endphp

@section('title', 'আজকের সোনা, রূপো ও সেনসেক্স দর | আজকের সোনার দাম ও শেয়ার বাজার | ' . ($g_settings['site_name'] ?? 'নিউজ১'))
@section('meta_description', 'আজকের ২৪ ক্যারেট ও ২২ ক্যারেট সোনার দাম, ১ কেজি রূপোর দর এবং বিএসই সেনসেক্স ও নিফটি ৫০ লাইভ শেয়ার বাজার আপডেট জানুন। কলকাতা ও পশ্চিমবঙ্গের সর্বশেষ বাজার দর।')

@section('styles')
<style>
  .market-page-container {
    width: 100%;
    max-width: 1280px;
    margin: 24px auto 40px auto;
    padding: 0 16px;
    box-sizing: border-box;
    overflow-x: hidden;
  }
  .market-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
    border-radius: 16px;
    padding: 28px 24px;
    margin-bottom: 28px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    position: relative;
    overflow: hidden;
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
  }
  .market-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(234, 179, 8, 0.15), transparent 70%);
    pointer-events: none;
  }
  .market-header-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 24px;
    width: 100%;
    box-sizing: border-box;
  }
  .market-title {
    font-size: 1.8rem;
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
  }
  .rate-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
    gap: 16px;
    width: 100%;
    max-width: 100%;
    min-width: 0;
  }
  .rate-card {
    background: rgba(255, 255, 255, 0.04);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 14px;
    padding: 20px;
    transition: transform 0.2s, border-color 0.2s;
    position: relative;
    min-width: 0;
    box-sizing: border-box;
    overflow: hidden;
  }
  .rate-card:hover {
    transform: translateY(-3px);
    border-color: rgba(255, 255, 255, 0.2);
  }
  .rate-card.gold-24k {
    border-top: 4px solid #eab308;
    background: linear-gradient(180deg, rgba(234, 179, 8, 0.06) 0%, rgba(255, 255, 255, 0.02) 100%);
  }
  .rate-card.gold-22k {
    border-top: 4px solid #f59e0b;
    background: linear-gradient(180deg, rgba(245, 158, 11, 0.06) 0%, rgba(255, 255, 255, 0.02) 100%);
  }
  .rate-card.silver {
    border-top: 4px solid #94a3b8;
    background: linear-gradient(180deg, rgba(148, 163, 184, 0.06) 0%, rgba(255, 255, 255, 0.02) 100%);
  }
  .rate-card.sensex {
    border-top: 4px solid #3b82f6;
    background: linear-gradient(180deg, rgba(59, 130, 246, 0.06) 0%, rgba(255, 255, 255, 0.02) 100%);
  }
  .rate-card.nifty {
    border-top: 4px solid #8b5cf6;
    background: linear-gradient(180deg, rgba(139, 92, 246, 0.06) 0%, rgba(255, 255, 255, 0.02) 100%);
  }
  .card-top-label {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-muted, #94a3b8);
    margin-bottom: 10px;
    gap: 6px;
  }
  .card-main-price {
    font-size: 1.75rem;
    font-weight: 800;
    color: #ffffff;
    margin-bottom: 6px;
    letter-spacing: -0.5px;
    word-break: break-word;
  }
  .card-change-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 0.8rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 6px;
    white-space: nowrap;
  }
  .card-change-badge.positive {
    background: rgba(16, 185, 129, 0.15);
    color: #34d399;
  }
  .card-change-badge.negative {
    background: rgba(239, 68, 68, 0.15);
    color: #f87171;
  }
  .market-content-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr);
    gap: 28px;
    width: 100%;
    max-width: 100%;
    min-width: 0;
  }
  .market-table-section {
    background: var(--bg-card, #ffffff);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 14px;
    padding: 24px;
    margin-bottom: 0;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    width: 100%;
    max-width: 100%;
    min-width: 0;
    box-sizing: border-box;
    overflow: hidden;
  }
  body.dark-mode .market-table-section {
    background: #1e293b;
    border-color: rgba(255, 255, 255, 0.08);
  }
  .section-heading-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
    border-bottom: 2px solid var(--brand-red, #dc2626);
    padding-bottom: 10px;
    gap: 12px;
    flex-wrap: wrap;
  }
  .section-heading-wrap h3 {
    font-size: 1.3rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .table-scroll-hint {
    display: none;
    font-size: 0.74rem;
    font-weight: 600;
    background: rgba(59, 130, 246, 0.1);
    color: #2563eb;
    border: 1px solid rgba(59, 130, 246, 0.25);
    padding: 3px 9px;
    border-radius: 999px;
    align-items: center;
    gap: 5px;
    white-space: nowrap;
  }
  body.dark-mode .table-scroll-hint {
    background: rgba(59, 130, 246, 0.2);
    color: #60a5fa;
    border-color: rgba(96, 165, 250, 0.3);
  }
  .market-table-wrapper {
    width: 100%;
    max-width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border-radius: 8px;
    margin-bottom: 6px;
    scrollbar-width: thin;
  }
  .market-table-wrapper::-webkit-scrollbar {
    height: 5px;
  }
  .market-table-wrapper::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.03);
    border-radius: 10px;
  }
  .market-table-wrapper::-webkit-scrollbar-thumb {
    background: rgba(148, 163, 184, 0.4);
    border-radius: 10px;
  }
  body.dark-mode .market-table-wrapper::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.04);
  }
  body.dark-mode .market-table-wrapper::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
  }
  .custom-market-table {
    width: 100%;
    min-width: 520px;
    border-collapse: collapse;
    font-size: 0.95rem;
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
    background: rgba(255, 255, 255, 0.04);
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
  .info-box {
    background: rgba(59, 130, 246, 0.06);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 10px;
    padding: 16px 20px;
    margin-top: 18px;
    font-size: 0.9rem;
    color: var(--text-muted, #64748b);
    line-height: 1.6;
    word-break: break-word;
  }
  .guide-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 18px;
    width: 100%;
    max-width: 100%;
    min-width: 0;
  }
  .guide-card-item {
    background: rgba(0, 0, 0, 0.02);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 10px;
    padding: 18px;
    min-width: 0;
    box-sizing: border-box;
  }
  body.dark-mode .guide-card-item {
    background: rgba(255, 255, 255, 0.02);
    border-color: rgba(255, 255, 255, 0.08);
  }
  .faq-card {
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 10px;
    padding: 18px 20px;
    margin-bottom: 14px;
    background: var(--bg-card, #ffffff);
    min-width: 0;
    box-sizing: border-box;
  }
  body.dark-mode .faq-card {
    background: #1e293b;
    border-color: rgba(255, 255, 255, 0.08);
  }
  .faq-q {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--text-main, #0f172a);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  body.dark-mode .faq-q {
    color: #f8fafc;
  }
  .faq-a {
    font-size: 0.92rem;
    color: var(--text-muted, #64748b);
    line-height: 1.6;
    margin: 0;
  }
  body.dark-mode .faq-a {
    color: #94a3b8;
  }

  /* Responsive Media Queries */
  @media (max-width: 768px) {
    .market-page-container {
      padding: 0 12px;
      margin-top: 16px;
      margin-bottom: 28px;
    }
    .market-hero {
      padding: 18px 14px;
      border-radius: 12px;
      margin-bottom: 20px;
    }
    .market-title {
      font-size: 1.25rem;
      gap: 8px;
    }
    .market-header-flex {
      gap: 12px;
      margin-bottom: 16px;
    }
    .rate-cards-grid {
      grid-template-columns: 1fr;
      gap: 12px;
    }
    .rate-card {
      padding: 14px 14px;
      border-radius: 12px;
    }
    .card-top-label {
      font-size: 0.8rem;
      margin-bottom: 6px;
    }
    .card-main-price {
      font-size: 1.45rem;
      margin-bottom: 4px;
    }
    .card-change-badge {
      font-size: 0.74rem;
      padding: 1px 6px;
    }
    .market-content-grid {
      gap: 20px;
    }
    .market-table-section {
      padding: 16px 12px;
      border-radius: 12px;
    }
    .section-heading-wrap {
      flex-direction: column;
      align-items: flex-start;
      gap: 8px;
      margin-bottom: 14px;
    }
    .section-heading-wrap h3 {
      font-size: 1.08rem;
    }
    .table-scroll-hint {
      display: inline-flex;
    }
    .custom-market-table {
      font-size: 0.85rem;
      min-width: 480px;
    }
    .custom-market-table th,
    .custom-market-table td {
      padding: 10px 10px;
    }
    .guide-cards-grid {
      grid-template-columns: 1fr;
      gap: 12px;
    }
    .info-box {
      padding: 12px 14px;
      font-size: 0.82rem;
      margin-top: 14px;
    }
    .faq-card {
      padding: 14px 14px;
    }
    .faq-q {
      font-size: 0.95rem;
    }
    .faq-a {
      font-size: 0.86rem;
    }
  }

  @media (max-width: 480px) {
    .custom-market-table {
      min-width: 440px;
    }
  }
</style>
@endsection

@section('content')
<div class="container market-page-container">

  <!-- Breadcrumbs -->
  <nav style="margin-bottom: 18px; font-size: 0.85rem; color: #94a3b8;">
    <a href="{{ route('home') }}" style="color: inherit; text-decoration: none;">প্রচ্ছদ</a>
    <span style="margin: 0 6px;">/</span>
    <span style="color: var(--brand-red, #dc2626); font-weight: 600;">বাজার দর ও সোনা-রূপো</span>
  </nav>

  <!-- Hero Banner with Today's Highlight Rates -->
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

      <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
        <span class="market-live-pill">
          <span class="dot"></span> লাইভ রেট
        </span>
        <span style="color: #94a3b8; font-size: 0.85rem;">
          <i class="far fa-clock" style="margin-right: 4px;"></i> {{ $marketData['last_updated_bn'] ?? BengaliHelper::toBengaliDate(now()) }}
        </span>
      </div>
    </div>

    <!-- Rate Cards Grid -->
    <div class="rate-cards-grid">
      <!-- 24K Gold Card -->
      <div class="rate-card gold-24k">
        <div class="card-top-label">
          <span>২৪ ক্যারেট সোনা (১০ গ্রাম)</span>
          <i class="fas fa-certificate" style="color: #eab308;"></i>
        </div>
        <div class="card-main-price">
          ₹{{ $marketData['gold_24k_formatted'] ?? '৭৬,৮৫০' }}
        </div>
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <span class="card-change-badge {{ ($marketData['gold_is_positive'] ?? true) ? 'positive' : 'negative' }}">
            <i class="fas {{ ($marketData['gold_is_positive'] ?? true) ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
            {{ $marketData['gold_change_formatted'] ?? '+৩৫০' }} ({{ $marketData['gold_change_percent_formatted'] ?? '+০.৪৬%' }})
          </span>
          <span style="font-size: 0.75rem; color: #94a3b8;">বিশুদ্ধ ৯৯.৯%</span>
        </div>
      </div>

      <!-- 22K Gold Card -->
      <div class="rate-card gold-22k">
        <div class="card-top-label">
          <span>২২ ক্যারেট গহনা সোনা (১০ গ্রাম)</span>
          <i class="fas fa-gem" style="color: #f59e0b;"></i>
        </div>
        <div class="card-main-price">
          ₹{{ $marketData['gold_22k_formatted'] ?? '৭০,৪৫০' }}
        </div>
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <span style="font-size: 0.8rem; color: #cbd5e1;">
            ১ ভরি (৮ গ্রাম): <strong>₹{{ $marketData['gold_22k_bhori_formatted'] ?? '৫৬,৩৬০' }}</strong>
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
          ₹{{ $marketData['silver_1kg_formatted'] ?? '৯৪,৫০০' }}
        </div>
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <span class="card-change-badge {{ ($marketData['silver_is_positive'] ?? true) ? 'positive' : 'negative' }}">
            <i class="fas {{ ($marketData['silver_is_positive'] ?? true) ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
            {{ $marketData['silver_change_formatted'] ?? '+৪৫০' }}
          </span>
          <span style="font-size: 0.75rem; color: #94a3b8;">১০ গ্রাম: ₹{{ $marketData['silver_10g_formatted'] ?? '৯৪৫' }}</span>
        </div>
      </div>

      <!-- Sensex Card -->
      <div class="rate-card sensex">
        <div class="card-top-label">
          <span>বিএসই সেনসেক্স (BSE Sensex)</span>
          <i class="fas fa-chart-line" style="color: #60a5fa;"></i>
        </div>
        <div class="card-main-price" style="color: {{ ($marketData['sensex']['is_positive'] ?? true) ? '#4ade80' : '#f87171' }};">
          {{ $marketData['sensex']['price_formatted'] ?? '৭৭,১৫০' }}
        </div>
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <span class="card-change-badge {{ ($marketData['sensex']['is_positive'] ?? true) ? 'positive' : 'negative' }}">
            <i class="fas {{ ($marketData['sensex']['is_positive'] ?? true) ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
            {{ $marketData['sensex']['change_formatted'] ?? '+৩৪০.২০' }} ({{ $marketData['sensex']['change_percent_formatted'] ?? '+০.৪৪%' }})
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
          {{ $marketData['nifty']['price_formatted'] ?? '২৩,৪৩০' }}
        </div>
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <span class="card-change-badge {{ ($marketData['nifty']['is_positive'] ?? true) ? 'positive' : 'negative' }}">
            <i class="fas {{ ($marketData['nifty']['is_positive'] ?? true) ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
            {{ $marketData['nifty']['change_formatted'] ?? '+১১০.৫০' }} ({{ $marketData['nifty']['change_percent_formatted'] ?? '+০.৪৭%' }})
          </span>
          <span style="font-size: 0.75rem; color: #94a3b8;">NSE লাইভ</span>
        </div>
      </div>
    </div>
  </div>

  <div class="row" style="display: grid; grid-template-columns: 1fr; gap: 30px;">
    <!-- Gold Rates Breakdown Table -->
    <div class="market-table-section">
      <div class="section-heading-wrap">
        <h3><i class="fas fa-coins" style="color: #eab308;"></i> কলকাতায় আজকের সোনার দর তালিকা</h3>
        <span style="font-size: 0.85rem; color: var(--text-muted, #64748b);">*জিএসটি ও মেকিং চার্জ বাদে</span>
      </div>

      <div style="overflow-x: auto;">
        <table class="custom-market-table">
          <thead>
            <tr>
              <th>ওজন (পরিমাণ)</th>
              <th>২৪ ক্যারেট সোনা (৯৯.৯%)</th>
              <th>২২ ক্যারেট গহনা সোনা (৯১.৬%)</th>
              <th>১৮ ক্যারেট সোনা (৭৫.০%)</th>
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
              <td><strong>৮ গ্রাম (১ ভরি / তোলা)</strong></td>
              <td>₹{{ BengaliHelper::toBengaliNumerals(number_format(round(($marketData['gold_24k'] / 10) * 8))) }}</td>
              <td>₹{{ $marketData['gold_22k_bhori_formatted'] ?? '৫৬,৩৬০' }}</td>
              <td>₹{{ BengaliHelper::toBengaliNumerals(number_format(round(($marketData['gold_18k'] / 10) * 8))) }}</td>
            </tr>
            <tr>
              <td><strong>১০ গ্রাম (স্ট্যান্ডার্ড)</strong></td>
              <td><strong style="color: #eab308;">₹{{ $marketData['gold_24k_formatted'] ?? '৭৬,৮৫০' }}</strong></td>
              <td><strong style="color: #f59e0b;">₹{{ $marketData['gold_22k_formatted'] ?? '৭০,৪৫০' }}</strong></td>
              <td><strong>₹{{ $marketData['gold_18k_formatted'] ?? '৫৭,৬৪০' }}</strong></td>
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
        <i class="fas fa-info-circle" style="color: #3b82f6; margin-right: 6px;"></i>
        <strong>মনে রাখবেন:</strong> উল্লেখিত সোনার দর খাঁটি বুলিয়ন রেটের উপর ভিত্তি করে তৈরি। স্থানীয় জুয়েলার্স থেকে সোনার গহনা কেনার সময় ৩% জিএসটি (GST) এবং মেকিং চার্জেস (গড় ১০% থেকে ১৫%) অতিরিক্ত প্রযোজ্য হয়।
      </div>
    </div>

    <!-- Silver Rates Breakdown Table -->
    <div class="market-table-section">
      <div class="section-heading-wrap">
        <h3><i class="fas fa-gem" style="color: #94a3b8;"></i> কলকাতায় আজকের রূপোর দর তালিকা</h3>
        <span style="font-size: 0.85rem; color: var(--text-muted, #64748b);">*জিএসটি বাদে</span>
      </div>

      <div style="overflow-x: auto;">
        <table class="custom-market-table">
          <thead>
            <tr>
              <th>ওজন (পরিমাণ)</th>
              <th>আজকের রূপোর দাম</th>
              <th>গত দিনের তুলনায় পরিবর্তন</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>১ গ্রাম রূপো</strong></td>
              <td>₹{{ BengaliHelper::toBengaliNumerals(number_format($marketData['silver_1kg'] / 1000, 1)) }}</td>
              <td>{{ ($marketData['silver_is_positive'] ?? true) ? '+' : '' }}₹{{ BengaliHelper::toBengaliNumerals(number_format($marketData['silver_change'] / 1000, 2)) }}</td>
            </tr>
            <tr>
              <td><strong>১০ গ্রাম রূপো</strong></td>
              <td>₹{{ $marketData['silver_10g_formatted'] ?? '৯৪৫' }}</td>
              <td>{{ ($marketData['silver_is_positive'] ?? true) ? '+' : '' }}₹{{ BengaliHelper::toBengaliNumerals(number_format($marketData['silver_change'] / 100, 1)) }}</td>
            </tr>
            <tr>
              <td><strong>১০০ গ্রাম রূপো</strong></td>
              <td>₹{{ BengaliHelper::toBengaliNumerals(number_format(round($marketData['silver_1kg'] / 10))) }}</td>
              <td>{{ ($marketData['silver_is_positive'] ?? true) ? '+' : '' }}₹{{ BengaliHelper::toBengaliNumerals(number_format(round($marketData['silver_change'] / 10))) }}</td>
            </tr>
            <tr>
              <td><strong>১ কেজি রূপো (বার)</strong></td>
              <td><strong style="color: #38bdf8;">₹{{ $marketData['silver_1kg_formatted'] ?? '৯৪,৫০০' }}</strong></td>
              <td style="color: {{ ($marketData['silver_is_positive'] ?? true) ? '#10b981' : '#ef4444' }}; font-weight: 700;">
                {{ $marketData['silver_change_formatted'] ?? '+৪৫০' }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Major Cities Comparison Table -->
    <div class="market-table-section">
      <div class="section-heading-wrap">
        <h3><i class="fas fa-map-marker-alt" style="color: #ef4444;"></i> ভারতের বিভিন্ন মেট্রো শহরে সোনার দর তুলনা</h3>
        <span style="font-size: 0.85rem; color: var(--text-muted, #64748b);">প্রতি ১০ গ্রাম হিসেবে</span>
      </div>

      <div style="overflow-x: auto;">
        <table class="custom-market-table">
          <thead>
            <tr>
              <th>শহর</th>
              <th>২৪ ক্যারেট (১০ গ্রাম)</th>
              <th>২২ ক্যারেট (১০ গ্রাম)</th>
              <th>১ কেজি রূপো</th>
            </tr>
          </thead>
          <tbody>
            @foreach($cityRates as $city)
              <tr>
                <td><strong>{{ $city['city_bn'] }}</strong> ({{ $city['city_en'] }})</td>
                <td>₹{{ BengaliHelper::toBengaliNumerals(number_format($city['gold_24k'])) }}</td>
                <td>₹{{ BengaliHelper::toBengaliNumerals(number_format($city['gold_22k'])) }}</td>
                <td>₹{{ BengaliHelper::toBengaliNumerals(number_format($city['silver_1kg'])) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- Top 10 Stocks Live Performance Table -->
    <div class="market-table-section">
      <div class="section-heading-wrap">
        <h3><i class="fas fa-arrow-trend-up" style="color: #10b981;"></i> ভারতের শীর্ষ ১০টি কোম্পানির শেয়ারের দর (NSE / BSE)</h3>
        <span style="font-size: 0.85rem; color: #10b981; font-weight: 600;"><i class="fas fa-bolt"></i> অটো-আপডেট</span>
      </div>

      <div style="overflow-x: auto;">
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
    </div>

    <!-- Gold Buying & BIS Hallmarking Guide -->
    <div class="market-table-section">
      <div class="section-heading-wrap">
        <h3><i class="fas fa-shield-alt" style="color: #8b5cf6;"></i> সোনা কেনার সময় জরুরি নির্দেশিকা ও সতর্কতা</h3>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px;">
        <div style="background: rgba(0,0,0,0.02); border: 1px solid var(--border-color); border-radius: 10px; padding: 18px;">
          <h4 style="font-size: 1.1rem; color: #eab308; margin-bottom: 8px;"><i class="fas fa-award"></i> BIS ৯১৬ হলমার্কিং দেখে নিন</h4>
          <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.6; margin: 0;">
            সোনার গহনা কেনার সময় অবশ্যই BIS ত্রিভুজ লোগো এবং ৯১৬ (২২ ক্যারেট) অথবা ৭৫০ (১৮ ক্যারেট) চিহ্ন রয়েছে কিনা তা যাচাই করে নিন। সরকার কর্তৃক এটি বাধ্যতামূলক।
          </p>
        </div>

        <div style="background: rgba(0,0,0,0.02); border: 1px solid var(--border-color); border-radius: 10px; padding: 18px;">
          <h4 style="font-size: 1.1rem; color: #3b82f6; margin-bottom: 8px;"><i class="fas fa-barcode"></i> ৬ সংখ্যার HUID কোড</h4>
          <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.6; margin: 0;">
            প্রতিটি হলমার্কযুক্ত সোনার গহনায় ৬ সংখ্যার আলফানিউমেরিক HUID (Hallmark Unique Identification) কোড থাকে। BIS Care অ্যাপের মাধ্যমে আপনি এই কোডটি দিয়ে সত্যতা যাচাই করতে পারেন।
          </p>
        </div>

        <div style="background: rgba(0,0,0,0.02); border: 1px solid var(--border-color); border-radius: 10px; padding: 18px;">
          <h4 style="font-size: 1.1rem; color: #10b981; margin-bottom: 8px;"><i class="fas fa-file-invoice-dollar"></i> আসল পাকা বিল সংগ্রহ করুন</h4>
          <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.6; margin: 0;">
            সোনার ওজন, ক্যারেটের বিশুদ্ধতা, মেকিং চার্জ এবং ৩% জিএসটি উল্লেখ থাকা অফিশিয়াল পাকা ট্যাক্স ইনভয়েস বিল বাধ্যতামূলকভাবে জুয়েলার্সের কাছ থেকে সংগ্রহ করুন।
          </p>
        </div>
      </div>
    </div>

    <!-- Frequently Asked Questions (FAQ) -->
    <div class="market-table-section">
      <div class="section-heading-wrap">
        <h3><i class="fas fa-question-circle" style="color: #06b6d4;"></i> সোনা ও রূপোর বাজার দর সম্পর্কে সাধারণ প্রশ্নোত্তর (FAQ)</h3>
      </div>

      <div class="faq-card">
        <div class="faq-q"><i class="fas fa-caret-right" style="color: #eab308;"></i> ২৪ ক্যারেট ও ২২ ক্যারেট সোনার মধ্যে পার্থক্য কী?</div>
        <p class="faq-a">
          ২৪ ক্যারেট সোনা ৯৯.৯% খাঁটি সোনা, যা খুব নরম হওয়ায় সাধারণত কয়েন ও বার তৈরিতে ব্যবহৃত হয়। অন্যদিকে ২২ ক্যারেট সোনায় ৯১.৬% সোনা এবং বাকি অংশ তামা বা দস্তা মিশ্রিত থাকে, যা গহনা তৈরির জন্য মজবুত ও আদর্শ।
        </p>
      </div>

      <div class="faq-card">
        <div class="faq-q"><i class="fas fa-caret-right" style="color: #eab308;"></i> ১ ভরি বা ১ তোলা সোনা মানে কত গ্রাম?</div>
        <p class="faq-a">
          ঐতিহ্যগতভাবে বাংলায় সোনার হিসেব ভরিতে করা হয়। আন্তর্জাতিক মানদণ্ডে ১ ভরি সোনা সমান ১১.৬৬ গ্রাম। তবে ভারতীয় সাধারণ জুয়েলারি বাজারে সুবিধার জন্য ১০ গ্রামকে ১ তোলা হিসেবেও অনেক সময় গণ্য করা হয়।
        </p>
      </div>

      <div class="faq-card">
        <div class="faq-q"><i class="fas fa-caret-right" style="color: #eab308;"></i> সোনার দাম প্রতিদিন কেন পরিবর্তন হয়?</div>
        <p class="faq-a">
          আন্তর্জাতিক বাজারে অপরিশোধিত সোনার দাম (London Bullion Market), মার্কিন ডলারের বিপরীতে ভারতীয় টাকার বিনিময় হার, আন্তর্জাতিক যুদ্ধ ও অর্থনৈতিক অনিশ্চয়তা, মুদ্রাস্ফীতি এবং ভারতের অভ্যন্তরীণ উৎসব ও বিয়ের মরসুমে চাহিদার ওপর নির্ভর করে প্রতিদিন সোনা ও রূপোর দাম পরিবর্তিত হয়।
        </p>
      </div>

      <div class="faq-card" style="margin-bottom: 0;">
        <div class="faq-q"><i class="fas fa-caret-right" style="color: #eab308;"></i> সোনা কেনার সময় কত শতাংশ জিএসটি (GST) দিতে হয়?</div>
        <p class="faq-a">
          ভারতে সোনা ও রূপোর বার এবং গহনা ক্রয়ের ক্ষেত্রে সরকার কর্তৃক ৩% পণ্য ও পরিষেবা কর (GST) আরোপিত হয়। এছাড়া গহনার মেকিং চার্জের ওপর ৫% জিএসটি প্রযোজ্য হয়।
        </p>
      </div>
    </div>

  </div>

</div>
@endsection
