@php
    use App\Helpers\BengaliHelper;
    $sidebarTags = \App\Models\Tag::take(6)->get();
@endphp

<!-- Sidebar Column -->
<aside class="sidebar">

  <!-- Live Gold & Silver Market Widget (Above Trending) with Auto-Rotation Animation -->
  @php
    $goldSilverData = \App\Http\Controllers\MarketController::getMarketData();
    $metalItems = [
      [
        'id' => 0,
        'type' => 'gold',
        'rank' => '২৪K',
        'rank_bg' => '#eab308',
        'rank_color' => '#000000',
        'border_color' => '#eab308',
        'symbol' => '২৪ ক্যারেট খাঁটি সোনা',
        'name' => '৯৯.৯% বিশুদ্ধ বুলিয়ন রেট',
        'price' => '₹' . ($goldSilverData['gold_24k_formatted'] ?? '৭৭,২৬০'),
        'unit' => '/১০ গ্রাম',
        'sub_label' => '১ গ্রাম: ₹' . BengaliHelper::toBengaliNumerals(number_format(round($goldSilverData['gold_24k'] / 10))),
        'badge' => ($goldSilverData['gold_change_formatted'] ?? '+৪১০') . ' (' . ($goldSilverData['gold_change_percent_formatted'] ?? '+০.৫৪%') . ')',
        'is_positive' => $goldSilverData['gold_is_positive'] ?? true,
      ],
      [
        'id' => 1,
        'type' => 'gold',
        'rank' => '২২K',
        'rank_bg' => '#f59e0b',
        'rank_color' => '#ffffff',
        'border_color' => '#f59e0b',
        'symbol' => '২২ ক্যারেট গহনা সোনা',
        'name' => 'হলমার্ক ৯১৬ সরকারি মানসম্পন্ন',
        'price' => '₹' . ($goldSilverData['gold_22k_formatted'] ?? '৭০,৮২০'),
        'unit' => '/১০ গ্রাম',
        'sub_label' => '১ ভরি (৮ গ্রাম): ₹' . ($goldSilverData['gold_22k_bhori_formatted'] ?? '৫৬,৬৬০'),
        'badge' => ($goldSilverData['gold_change_formatted'] ?? '+৪১০'),
        'is_positive' => $goldSilverData['gold_is_positive'] ?? true,
      ],
      [
        'id' => 2,
        'type' => 'gold',
        'rank' => '১৮K',
        'rank_bg' => '#8b5cf6',
        'rank_color' => '#ffffff',
        'border_color' => '#8b5cf6',
        'symbol' => '১৮ ক্যারেট সোনা',
        'name' => '৭৫.০% বিশুদ্ধ ডিজাইনার অলঙ্কার',
        'price' => '₹' . ($goldSilverData['gold_18k_formatted'] ?? '৫৭,৯৫০'),
        'unit' => '/১০ গ্রাম',
        'sub_label' => '১ গ্রাম: ₹' . BengaliHelper::toBengaliNumerals(number_format(round($goldSilverData['gold_18k'] / 10))),
        'badge' => '৭৫% বিশুদ্ধ',
        'is_positive' => true,
      ],
      [
        'id' => 3,
        'type' => 'silver',
        'rank' => 'কেজি',
        'rank_bg' => '#38bdf8',
        'rank_color' => '#000000',
        'border_color' => '#38bdf8',
        'symbol' => '১ কেজি খাঁটি রূপো (বার)',
        'name' => '৯৯৯ খাঁটি রূপার বুলিয়ন বার',
        'price' => '₹' . ($goldSilverData['silver_1kg_formatted'] ?? '৯৫,৬৮০'),
        'unit' => '/১ কেজি',
        'sub_label' => '১০ গ্রাম: ₹' . ($goldSilverData['silver_10g_formatted'] ?? '৯৫৭'),
        'badge' => ($goldSilverData['silver_change_formatted'] ?? '+১,১৮০'),
        'is_positive' => $goldSilverData['silver_is_positive'] ?? true,
      ],
      [
        'id' => 4,
        'type' => 'silver',
        'rank' => '১০g',
        'rank_bg' => '#94a3b8',
        'rank_color' => '#ffffff',
        'border_color' => '#94a3b8',
        'symbol' => '১০ গ্রাম রূপো',
        'name' => '১ গ্রাম: ₹' . BengaliHelper::toBengaliNumerals(number_format($goldSilverData['silver_1kg'] / 1000, 1)),
        'price' => '₹' . ($goldSilverData['silver_10g_formatted'] ?? '৯৫৭'),
        'unit' => '/১০ গ্রাম',
        'sub_label' => '১ গ্রাম: ₹' . BengaliHelper::toBengaliNumerals(number_format($goldSilverData['silver_1kg'] / 1000, 1)),
        'badge' => (($goldSilverData['silver_is_positive'] ?? true) ? '+' : '') . '₹' . BengaliHelper::toBengaliNumerals(number_format(abs($goldSilverData['silver_change']) / 100, 1)),
        'is_positive' => $goldSilverData['silver_is_positive'] ?? true,
      ],
    ];
    $firstMetal = $metalItems[0];
  @endphp
  <div class="widget-box stock-market-widget gold-silver-widget" id="gold-silver-widget" style="margin-bottom: 20px;">
    <div class="widget-title stock-widget-header">
      <span><i class="fas fa-coins" style="color: #eab308;"></i> সোনা ও রূপোর দর (Gold & Silver)</span>
      <div class="stock-header-controls">
        <span class="stock-live-badge"><span class="stock-live-dot"></span> লাইভ</span>
        <a href="{{ route('market.rates') }}" class="stock-refresh-btn" title="সম্পূর্ণ বাজার দর দেখুন" aria-label="View Full Market Rates">
          <i class="fas fa-arrow-up-right-from-square"></i>
        </a>
      </div>
    </div>

    <!-- Metal Snapshot Mini Cards (Gold 24K & Silver 1kg) -->
    <div class="stock-indices-grid">
      <div class="stock-index-card" style="border-top: 3px solid #eab308;">
        <div class="index-meta">
          <span class="index-name">২৪K সোনা (১০ গ্রাম)</span>
          <span class="index-change {{ ($goldSilverData['gold_is_positive'] ?? true) ? 'positive' : 'negative' }}">
            {{ ($goldSilverData['gold_is_positive'] ?? true) ? '▲' : '▼' }} {{ $goldSilverData['gold_change_percent_formatted'] ?? '+০.৫৪%' }}
          </span>
        </div>
        <div class="index-price" style="color: #eab308;">₹{{ $goldSilverData['gold_24k_formatted'] ?? '৭৭,২৬০' }}</div>
      </div>
      <div class="stock-index-card" style="border-top: 3px solid #94a3b8;">
        <div class="index-meta">
          <span class="index-name">রূপো (১ কেজি)</span>
          <span class="index-change {{ ($goldSilverData['silver_is_positive'] ?? true) ? 'positive' : 'negative' }}">
            {{ ($goldSilverData['silver_is_positive'] ?? true) ? '▲' : '▼' }} {{ $goldSilverData['silver_change_percent_formatted'] ?? '+১.২৫%' }}
          </span>
        </div>
        <div class="index-price" style="color: #38bdf8;">₹{{ $goldSilverData['silver_1kg_formatted'] ?? '৯৫,৬৮০' }}</div>
      </div>
    </div>

    <!-- Animated Spotlight Rotating Featured Metal Card -->
    <div class="stock-spotlight-card metal-spotlight-card" id="metal-spotlight-card" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 60%, #0f172a 100%); border-top: 3px solid {{ $firstMetal['border_color'] }}; margin-bottom: 10px;">
      <div class="spotlight-progress-bar">
        <div class="spotlight-progress-fill" id="metal-spotlight-progress" style="background: linear-gradient(90deg, #eab308, #f59e0b, #38bdf8);"></div>
      </div>
      <div class="spotlight-header" style="margin-bottom: 6px;">
        <div class="spotlight-company">
          <span class="spotlight-rank" id="metal-spotlight-rank" style="background: {{ $firstMetal['rank_bg'] }}; color: {{ $firstMetal['rank_color'] }}; font-weight: 800;">
            {{ $firstMetal['rank'] }}
          </span>
          <div style="min-width: 0;">
            <div class="spotlight-symbol" id="metal-spotlight-symbol" style="color: #ffffff;">{{ $firstMetal['symbol'] }}</div>
            <div class="spotlight-name" id="metal-spotlight-name" style="color: #94a3b8;">{{ $firstMetal['name'] }}</div>
          </div>
        </div>
        <div class="spotlight-badge {{ $firstMetal['is_positive'] ? 'positive' : 'negative' }}" id="metal-spotlight-badge">
          <i class="fas {{ $firstMetal['is_positive'] ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i> {{ $firstMetal['badge'] }}
        </div>
      </div>
      <div class="spotlight-footer" style="padding-top: 6px;">
        <span class="spotlight-label" id="metal-spotlight-footer-label">{!! $firstMetal['sub_label'] !!}</span>
        <span class="spotlight-price" id="metal-spotlight-price" style="font-size: 1.25rem; color: #fbbf24;">
          {{ $firstMetal['price'] }} <span id="metal-spotlight-unit" style="font-size: 0.72rem; font-weight: 500; color: #cbd5e1;">{{ $firstMetal['unit'] }}</span>
        </span>
      </div>
    </div>

    <!-- Filter Tabs (সব ধাতু, সোনা, রূপো) -->
    <div class="stock-filter-tabs metal-filter-tabs">
      <button type="button" class="stock-tab-btn metal-tab-btn active" data-metal-filter="all">সব দর</button>
      <button type="button" class="stock-tab-btn metal-tab-btn" data-metal-filter="gold">সোনা (Gold)</button>
      <button type="button" class="stock-tab-btn metal-tab-btn" data-metal-filter="silver">রূপো (Silver)</button>
    </div>

    <!-- Metal Rates List with synchronized highlight & click/hover -->
    <div class="stock-list-container" id="metal-list-container" style="max-height: 280px;">
      @foreach($metalItems as $idx => $mItem)
        <div class="stock-item-row metal-item-row {{ $idx === 0 ? 'spotlight-active' : '' }}" data-metal-idx="{{ $idx }}" data-metal="{{ $mItem['type'] }}">
          <div class="stock-item-left">
            <span class="stock-item-rank" style="background: {{ $mItem['rank_bg'] }}; color: {{ $mItem['rank_color'] }}; font-weight: 800;">
              {{ $mItem['rank'] }}
            </span>
            <div class="stock-item-info">
              <span class="stock-item-symbol">{{ $mItem['symbol'] }}</span>
              <span class="stock-item-name">{!! $mItem['sub_label'] !!}</span>
            </div>
          </div>
          <div class="stock-item-right">
            <span class="stock-item-price" style="{{ $mItem['type'] === 'silver' ? 'color: #0284c7;' : '' }}">{{ $mItem['price'] }}</span>
            <span class="stock-change-pill {{ $mItem['is_positive'] ? 'positive' : 'negative' }}">
              <i class="fas {{ $mItem['is_positive'] ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i> {{ $mItem['badge'] }}
            </span>
          </div>
        </div>
      @endforeach
    </div>

    <!-- Footer -->
    <div class="stock-widget-footer" style="margin-top: 8px;">
      <span class="stock-status-text" style="color: var(--text-muted);"><i class="fas fa-map-marker-alt" style="color: #ef4444;"></i> কলকাতা ও পশ্চিমবঙ্গ</span>
      <a href="{{ route('market.rates') }}" class="stock-updated-text" style="color: var(--brand-red); font-weight: 700; text-decoration: none;">
        সম্পূর্ণ চার্ট <i class="fas fa-arrow-right" style="font-size: 0.7rem;"></i>
      </a>
    </div>
  </div>

  <!-- Gold & Silver Spotlight Animation Script -->
  <script>
    (function() {
      const metalItemsData = @json($metalItems);
      const spotlightCard = document.getElementById('metal-spotlight-card');
      const spotlightProgress = document.getElementById('metal-spotlight-progress');
      const spotlightRank = document.getElementById('metal-spotlight-rank');
      const spotlightSymbol = document.getElementById('metal-spotlight-symbol');
      const spotlightName = document.getElementById('metal-spotlight-name');
      const spotlightBadge = document.getElementById('metal-spotlight-badge');
      const spotlightFooterLabel = document.getElementById('metal-spotlight-footer-label');
      const spotlightPrice = document.getElementById('metal-spotlight-price');
      const metalTabs = document.querySelectorAll('.metal-tab-btn');
      const metalRows = document.querySelectorAll('.metal-item-row');
      const metalList = document.getElementById('metal-list-container');

      if (!spotlightCard || !metalItemsData || metalItemsData.length === 0) return;

      let currentFilter = 'all';
      let currentIdx = 0;
      let isHovering = false;
      let rotationInterval = null;
      const duration = 3600; // 3.6s per item cycle (exact match to stock widget)
      const step = 60;
      let elapsed = 0;

      function getFilteredMetalItems() {
        if (currentFilter === 'gold') {
          return metalItemsData.filter(m => m.type === 'gold');
        } else if (currentFilter === 'silver') {
          return metalItemsData.filter(m => m.type === 'silver');
        }
        return metalItemsData;
      }

      function updateSpotlight(idx, withAnimation = true) {
        const item = metalItemsData[idx];
        if (!item) return;

        currentIdx = idx;

        if (withAnimation) {
          spotlightCard.style.transition = 'transform 0.25s ease, opacity 0.25s ease';
          spotlightCard.style.opacity = '0.65';
          spotlightCard.style.transform = 'translateY(-2px)';
          setTimeout(() => {
            spotlightCard.style.opacity = '1';
            spotlightCard.style.transform = 'translateY(0)';
          }, 120);
        }

        spotlightCard.style.borderTopColor = item.border_color;
        if (spotlightRank) {
          spotlightRank.textContent = item.rank;
          spotlightRank.style.background = item.rank_bg;
          spotlightRank.style.color = item.rank_color;
        }
        if (spotlightSymbol) spotlightSymbol.textContent = item.symbol;
        if (spotlightName) spotlightName.textContent = item.name;
        if (spotlightPrice) {
          spotlightPrice.innerHTML = item.price + ' <span id="metal-spotlight-unit" style="font-size: 0.72rem; font-weight: 500; color: #cbd5e1;">' + item.unit + '</span>';
        }
        if (spotlightFooterLabel) spotlightFooterLabel.innerHTML = item.sub_label;

        if (spotlightBadge) {
          const arrow = item.is_positive ? '<i class="fas fa-arrow-trend-up"></i>' : '<i class="fas fa-arrow-trend-down"></i>';
          spotlightBadge.innerHTML = arrow + ' ' + item.badge;
          spotlightBadge.className = 'spotlight-badge ' + (item.is_positive ? 'positive' : 'negative');
        }

        // Highlight active row in list
        metalRows.forEach(row => {
          if (parseInt(row.getAttribute('data-metal-idx'), 10) === idx) {
            row.classList.add('spotlight-active');
          } else {
            row.classList.remove('spotlight-active');
          }
        });
      }

      function startRotation() {
        if (rotationInterval) clearInterval(rotationInterval);
        elapsed = 0;
        if (spotlightProgress) spotlightProgress.style.width = '0%';

        rotationInterval = setInterval(() => {
          if (!isHovering) {
            elapsed += step;
            const pct = Math.min((elapsed / duration) * 100, 100);
            if (spotlightProgress) {
              spotlightProgress.style.width = pct + '%';
            }

            if (elapsed >= duration) {
              elapsed = 0;
              const filtered = getFilteredMetalItems();
              if (filtered.length > 0) {
                const currentFilteredPos = filtered.findIndex(m => m.id === currentIdx);
                const nextPos = (currentFilteredPos + 1) % filtered.length;
                const nextItem = filtered[nextPos];
                updateSpotlight(nextItem.id, true);
              }
            }
          }
        }, step);
      }

      // Hover / Click on list items
      if (metalList) {
        metalList.addEventListener('mouseenter', () => { isHovering = true; });
        metalList.addEventListener('mouseleave', () => { isHovering = false; });
      }

      metalRows.forEach(row => {
        row.addEventListener('click', function(e) {
          e.preventDefault();
          const idx = parseInt(this.getAttribute('data-metal-idx'), 10);
          if (!isNaN(idx)) {
            updateSpotlight(idx, false);
            elapsed = 0;
            if (spotlightProgress) spotlightProgress.style.width = '0%';
          }
        });

        row.addEventListener('mouseenter', function() {
          const idx = parseInt(this.getAttribute('data-metal-idx'), 10);
          if (!isNaN(idx)) {
            updateSpotlight(idx, false);
          }
        });
      });

      // Filter Tabs Handling
      metalTabs.forEach(btn => {
        btn.addEventListener('click', function() {
          metalTabs.forEach(t => t.classList.remove('active'));
          this.classList.add('active');
          currentFilter = this.getAttribute('data-metal-filter');

          metalRows.forEach(row => {
            if (currentFilter === 'all' || row.getAttribute('data-metal') === currentFilter) {
              row.style.display = 'flex';
            } else {
              row.style.display = 'none';
            }
          });

          // Switch spotlight to first item of this filter
          const filtered = getFilteredMetalItems();
          if (filtered.length > 0) {
            updateSpotlight(filtered[0].id, true);
            elapsed = 0;
            if (spotlightProgress) spotlightProgress.style.width = '0%';
          }
        });
      });

      // Start the animated rotation on load
      startRotation();
    })();
  </script>

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

  <!-- Live Top 10 Stock Market Widget -->
  @php
    $defaultStocks = \App\Http\Controllers\MarketController::$defaultStocks ?? [];
    $firstStock = $defaultStocks[0] ?? [
        'rank' => 1, 'symbol' => 'RELIANCE', 'name_bn' => 'রিলায়েন্স ইন্ডাস্ট্রিজ', 'base_price' => 1279.00, 'base_percent' => 1.22
    ];
  @endphp
  <div class="widget-box stock-market-widget" id="stock-market-widget">
    <div class="widget-title stock-widget-header">
      <span><i class="fas fa-chart-line" style="color: #10b981;"></i> শেয়ার বাজার (Top 10 Stocks)</span>
      <div class="stock-header-controls">
        <span class="stock-live-badge"><span class="stock-live-dot"></span> লাইভ</span>
        <button id="stock-refresh-btn" class="stock-refresh-btn" title="লাইভ দর রিফ্রেশ করুন" aria-label="Refresh Stock Prices">
          <i class="fas fa-sync-alt"></i>
        </button>
      </div>
    </div>

    <!-- Indices Snapshot (NIFTY & SENSEX) -->
    <div class="stock-indices-grid" id="stock-indices-container">
      <div class="stock-index-card" id="index-nifty">
        <div class="index-meta">
          <span class="index-name">NIFTY 50</span>
          <span class="index-change positive" id="nifty-change">▲ +০.৪৭%</span>
        </div>
        <div class="index-price" id="nifty-price">২৩,৪৩০.০০</div>
      </div>
      <div class="stock-index-card" id="index-sensex">
        <div class="index-meta">
          <span class="index-name">SENSEX</span>
          <span class="index-change positive" id="sensex-change">▲ +০.৪৪%</span>
        </div>
        <div class="index-price" id="sensex-price">৭৭,১৫০.০০</div>
      </div>
    </div>

    <!-- Auto-Changing Featured Stock Spotlight Card -->
    <div class="stock-spotlight-card" id="stock-spotlight-card">
      <div class="spotlight-progress-bar"><div class="spotlight-progress-fill" id="spotlight-progress"></div></div>
      <div class="spotlight-header">
        <div class="spotlight-company">
          <span class="spotlight-rank" id="spotlight-rank">#১</span>
          <div>
            <div class="spotlight-symbol" id="spotlight-symbol">{{ $firstStock['symbol'] }}</div>
            <div class="spotlight-name" id="spotlight-name">{{ $firstStock['name_bn'] }}</div>
          </div>
        </div>
        <div class="spotlight-badge positive" id="spotlight-badge"><i class="fas fa-arrow-trend-up"></i> +{{ BengaliHelper::toBengaliNumerals(number_format($firstStock['base_percent'], 2)) }}%</div>
      </div>
      <div class="spotlight-footer">
        <span class="spotlight-label">বাজার দর:</span>
        <span class="spotlight-price" id="spotlight-price">₹{{ BengaliHelper::toBengaliNumerals(number_format($firstStock['base_price'], 2)) }}</span>
      </div>
    </div>

    <!-- Filter Tabs -->
    <div class="stock-filter-tabs">
      <button type="button" class="stock-tab-btn active" data-filter="all">শীর্ষ ১০</button>
      <button type="button" class="stock-tab-btn" data-filter="gainers">লাভজনক (Gainers)</button>
      <button type="button" class="stock-tab-btn" data-filter="losers">লোকসান (Losers)</button>
    </div>

    <!-- Top 10 Stocks List -->
    <div class="stock-list-container" id="stock-list-container">
      @foreach($defaultStocks as $idx => $stock)
        @php
          $isPos = ($stock['base_percent'] ?? 0) >= 0;
          $pillCls = $isPos ? 'positive' : 'negative';
          $arrow = $isPos ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down';
          $sign = $isPos ? '+' : '';
          $cat = $isPos ? 'gainers' : 'losers';
        @endphp
        <div class="stock-item-row {{ $idx === 0 ? 'spotlight-active' : '' }}" data-global-idx="{{ $idx }}" data-symbol="{{ $stock['symbol'] }}" data-category="{{ $cat }}" data-is-positive="{{ $isPos ? '1' : '0' }}">
          <div class="stock-item-left">
            <span class="stock-item-rank">{{ BengaliHelper::toBengaliNumerals($idx + 1) }}</span>
            <div class="stock-item-info">
              <span class="stock-item-symbol">{{ $stock['symbol'] }}</span>
              <span class="stock-item-name" title="{{ $stock['name_bn'] }}">{{ $stock['name_bn'] }}</span>
            </div>
          </div>
          <div class="stock-item-right">
            <span class="stock-item-price">₹{{ BengaliHelper::toBengaliNumerals(number_format($stock['base_price'], 2)) }}</span>
            <span class="stock-change-pill {{ $pillCls }}">
              <i class="fas {{ $arrow }}"></i> {{ $sign }}{{ BengaliHelper::toBengaliNumerals(number_format($stock['base_percent'], 2)) }}%
            </span>
          </div>
        </div>
      @endforeach
    </div>

    <div class="stock-widget-footer">
      <span class="stock-status-text"><i class="fas fa-circle-check" style="color: #10b981;"></i> NSE / BSE তথ্য</span>
      <span class="stock-updated-text" id="stock-last-updated">লাইভ সক্রিয়</span>
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
