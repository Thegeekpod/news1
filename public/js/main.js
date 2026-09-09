/**
 * News1 (নিউজ১) - Bengali News Website JavaScript Logic
 */

document.addEventListener('DOMContentLoaded', () => {
  initThemeToggle();
  initFontSizeControls();
  initMobileMenu();
  initSearchModal();
  initBookmarkDrawer();
  initVideoModal();
  initCityWeatherTabs();
  initAudioReader();
  initTickerPause();
  initStockMarketWidget();
});

/* ==========================================================================
   1. Theme Switcher (Dark / Light Mode)
   ========================================================================== */
function initThemeToggle() {
  const themeToggleBtns = document.querySelectorAll('.theme-toggle-btn');
  const savedTheme = localStorage.getItem('news1_theme') || 'light';

  // Apply saved theme
  document.documentElement.setAttribute('data-theme', savedTheme);
  updateThemeIcons(savedTheme);

  themeToggleBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const currentTheme = document.documentElement.getAttribute('data-theme');
      const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

      document.documentElement.setAttribute('data-theme', newTheme);
      localStorage.setItem('news1_theme', newTheme);
      updateThemeIcons(newTheme);
    });
  });
}

function updateThemeIcons(theme) {
  const icons = document.querySelectorAll('.theme-toggle-btn i');
  const textLabels = document.querySelectorAll('.theme-toggle-btn .theme-label');

  icons.forEach(icon => {
    if (theme === 'dark') {
      icon.className = 'fas fa-sun';
    } else {
      icon.className = 'fas fa-moon';
    }
  });

  textLabels.forEach(label => {
    if (label) {
      label.textContent = theme === 'dark' ? 'লাইট মোড' : 'ডার্ক মোড';
    }
  });
}

/* ==========================================================================
   2. Font Size Resizer Controls (+A / -A)
   ========================================================================== */
function initFontSizeControls() {
  const increaseBtn = document.getElementById('btn-font-increase');
  const decreaseBtn = document.getElementById('btn-font-decrease');
  const resetBtn = document.getElementById('btn-font-reset');

  if (increaseBtn) {
    increaseBtn.addEventListener('click', () => {
      document.body.classList.remove('font-size-sm');
      document.body.classList.add('font-size-lg');
    });
  }

  if (decreaseBtn) {
    decreaseBtn.addEventListener('click', () => {
      document.body.classList.remove('font-size-lg');
      document.body.classList.add('font-size-sm');
    });
  }

  if (resetBtn) {
    resetBtn.addEventListener('click', () => {
      document.body.classList.remove('font-size-lg', 'font-size-sm');
    });
  }
}

/* ==========================================================================
   3. Mobile Navigation Drawer Toggle
   ========================================================================== */
function initMobileMenu() {
  const menuToggleBtn = document.getElementById('mobile-menu-toggle-btn');
  const navMenu = document.querySelector('.nav-menu');

  if (menuToggleBtn && navMenu) {
    menuToggleBtn.addEventListener('click', () => {
      navMenu.classList.toggle('mobile-open');
      const isOpen = navMenu.classList.contains('mobile-open');
      menuToggleBtn.innerHTML = isOpen ? '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
    });
  }
}

/* ==========================================================================
   4. Search Modal Overlay System
   ========================================================================== */
function initSearchModal() {
  const searchTriggers = document.querySelectorAll('.search-trigger-btn');
  const searchModal = document.getElementById('search-modal');
  const closeModalBtn = document.getElementById('close-search-modal');
  const searchInput = document.getElementById('modal-search-input');
  const searchResultsBox = document.getElementById('search-results-box');

  if (!searchModal) return;

  searchTriggers.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      searchModal.classList.add('active');
      if (searchInput) searchInput.focus();
    });
  });

  if (closeModalBtn) {
    closeModalBtn.addEventListener('click', () => {
      searchModal.classList.remove('active');
    });
  }

  searchModal.addEventListener('click', (e) => {
    if (e.target === searchModal) {
      searchModal.classList.remove('active');
    }
  });

  // Simulated Live Search Filter
  if (searchInput && searchResultsBox) {
    searchInput.addEventListener('input', (e) => {
      const query = e.target.value.trim().toLowerCase();
      if (query.length > 1) {
        searchResultsBox.innerHTML = `
          <div class="search-result-item" style="padding: 10px; border-bottom: 1px solid var(--border-color);">
            <a href="article.html" style="font-weight: 600; color: var(--text-primary);">
              "<span style="color: var(--brand-red);">${query}</span>" সম্পর্কিত আজকের শীর্ষ খবরসমূহ
            </a>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 4px;">রাজ্য • ১০ মিনিট আগে</p>
          </div>
          <div class="search-result-item" style="padding: 10px;">
            <a href="article.html" style="font-weight: 600; color: var(--text-primary);">
              ${query} নিয়ে সাম্প্রতিক প্রকাশিত সংবাদ বিশ্লেষণ
            </a>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 4px;">আন্তর্জাতিক • ১ ঘণ্টা আগে</p>
          </div>
        `;
      } else {
        searchResultsBox.innerHTML = '<p style="color: var(--text-muted); font-size: 0.9rem; text-align: center; padding: 20px;">খবর খুঁজতে শব্দ টাইপ করুন...</p>';
      }
    });
  }
}

/* ==========================================================================
   5. Bookmark Side Drawer System
   ========================================================================== */
function initBookmarkDrawer() {
  const bookmarkTriggers = document.querySelectorAll('.bookmark-drawer-btn');
  const bookmarkDrawer = document.getElementById('bookmark-drawer');
  const closeDrawerBtn = document.getElementById('close-bookmark-drawer');
  const bookmarkList = document.getElementById('bookmark-items-list');

  if (!bookmarkDrawer) return;

  bookmarkTriggers.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      bookmarkDrawer.classList.add('active');
      renderSavedBookmarks();
    });
  });

  if (closeDrawerBtn) {
    closeDrawerBtn.addEventListener('click', () => {
      bookmarkDrawer.classList.remove('active');
    });
  }

  // Handle Save / Bookmark button clicks on news cards
  document.addEventListener('click', (e) => {
    const saveBtn = e.target.closest('.save-article-btn');
    if (saveBtn) {
      e.preventDefault();
      const title = saveBtn.dataset.title || 'সংবাদ শিরোনাম';
      const link = saveBtn.dataset.url || 'article.html';
      toggleBookmark({ title, link });
      saveBtn.classList.toggle('bookmarked');
      saveBtn.innerHTML = saveBtn.classList.contains('bookmarked') 
        ? '<i class="fas fa-bookmark" style="color: var(--brand-red);"></i>' 
        : '<i class="far fa-bookmark"></i>';
    }
  });
}

function getBookmarks() {
  return JSON.parse(localStorage.getItem('news1_bookmarks') || '[]');
}

function toggleBookmark(article) {
  let bookmarks = getBookmarks();
  const existsIndex = bookmarks.findIndex(b => b.title === article.title);

  if (existsIndex > -1) {
    bookmarks.splice(existsIndex, 1);
  } else {
    bookmarks.push(article);
  }
  localStorage.setItem('news1_bookmarks', JSON.stringify(bookmarks));
  renderSavedBookmarks();
}

function renderSavedBookmarks() {
  const bookmarkList = document.getElementById('bookmark-items-list');
  if (!bookmarkList) return;

  const bookmarks = getBookmarks();
  if (bookmarks.length === 0) {
    bookmarkList.innerHTML = '<p style="text-align: center; color: var(--text-muted); padding: 30px 0;">কোনো বুকমার্ক করা খবর নেই</p>';
    return;
  }

  bookmarkList.innerHTML = bookmarks.map((b, i) => `
    <div class="saved-bookmark-item" style="background: var(--bg-surface-subtle); padding: 12px; border-radius: var(--radius-sm); display: flex; justify-content: space-between; align-items: center;">
      <a href="${b.link}" style="font-weight: 600; font-size: 0.95rem; color: var(--text-primary); margin-right: 8px;">${b.title}</a>
      <button onclick="removeBookmark(${i})" style="color: var(--brand-red); font-size: 1.1rem;"><i class="fas fa-trash-alt"></i></button>
    </div>
  `).join('');
}

window.removeBookmark = function(index) {
  let bookmarks = getBookmarks();
  bookmarks.splice(index, 1);
  localStorage.setItem('news1_bookmarks', JSON.stringify(bookmarks));
  renderSavedBookmarks();
};

/* ==========================================================================
   6. Interactive Video Modal Popup
   ========================================================================== */
function initVideoModal() {
  const videoCards = document.querySelectorAll('.video-card');
  const videoModal = document.getElementById('video-modal');
  const videoFrame = document.getElementById('modal-video-frame');
  const closeVideoModal = document.getElementById('close-video-modal');

  if (!videoModal || !videoFrame) return;

  videoCards.forEach(card => {
    card.addEventListener('click', () => {
      const videoSrc = card.dataset.video || 'https://www.youtube.com/embed/dQw4w9WgXcQ';
      videoFrame.src = videoSrc + '?autoplay=1';
      videoModal.classList.add('active');
    });
  });

  if (closeVideoModal) {
    closeVideoModal.addEventListener('click', () => {
      videoModal.classList.remove('active');
      videoFrame.src = '';
    });
  }

  videoModal.addEventListener('click', (e) => {
    if (e.target === videoModal) {
      videoModal.classList.remove('active');
      videoFrame.src = '';
    }
  });
}

/* ==========================================================================
   7. Live City Weather Selector & Forecast Widget
   ========================================================================== */
function initCityWeatherTabs() {
  const cityTabs = document.querySelectorAll('.city-weather-tab');
  const cityNameEl = document.getElementById('weather-city-name');
  const tempEl = document.getElementById('weather-temp');
  const conditionEl = document.getElementById('weather-condition');
  const humidityEl = document.getElementById('weather-humidity');
  const windEl = document.getElementById('weather-wind');
  const iconEl = document.getElementById('weather-icon');

  const weatherData = {
    'kolkata': { name: 'কলকাতা', temp: '৩২°C', condition: 'আংশিক মেঘলা', humidity: '৭৮%', wind: '১২ কিমি/ঘণ্টা', icon: 'fas fa-cloud-sun' },
    'siliguri': { name: 'শিলিগুড়ি', temp: '২৫°C', condition: 'হালকা বৃষ্টি', humidity: '৮৮%', wind: '১৫ কিমি/ঘণ্টা', icon: 'fas fa-cloud-showers-heavy' },
    'asansol': { name: 'আসানসোল', temp: '৩৪°C', condition: 'রৌদ্রোজ্জ্বল', humidity: '৬২%', wind: '১০ কিমি/ঘণ্টা', icon: 'fas fa-sun' },
    'durgapur': { name: 'দূর্গাপুর', temp: '৩৩°C', condition: 'পরিষ্কার আকাশ', humidity: '৬৪%', wind: '১১ কিমি/ঘণ্টা', icon: 'fas fa-sun' }
  };

  cityTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      cityTabs.forEach(t => {
        t.style.background = 'var(--bg-surface-subtle)';
        t.style.color = 'var(--text-primary)';
        t.style.border = '1px solid var(--border-color)';
      });
      tab.style.background = 'var(--brand-red)';
      tab.style.color = '#fff';
      tab.style.border = 'none';

      const cityKey = tab.dataset.city;
      const data = weatherData[cityKey];

      if (data && cityNameEl) {
        cityNameEl.textContent = data.name;
        tempEl.textContent = data.temp;
        conditionEl.textContent = data.condition;
        humidityEl.textContent = data.humidity;
        windEl.textContent = data.wind;
        if (iconEl) iconEl.className = data.icon;
      }
    });
  });
}

/* ==========================================================================
   8. Bengali News Text-To-Speech Audio Reader Simulation
   ========================================================================== */
function initAudioReader() {
  const playAudioBtn = document.getElementById('btn-play-article-audio');
  if (!playAudioBtn) return;

  let isPlaying = false;
  playAudioBtn.addEventListener('click', () => {
    isPlaying = !isPlaying;
    if (isPlaying) {
      playAudioBtn.innerHTML = '<i class="fas fa-pause-circle"></i> থামান';
      playAudioBtn.style.background = 'var(--brand-red)';
      playAudioBtn.style.color = '#fff';
    } else {
      playAudioBtn.innerHTML = '<i class="fas fa-play-circle"></i> খবর শুনুন';
      playAudioBtn.style.background = 'transparent';
      playAudioBtn.style.color = 'var(--brand-red)';
    }
  });
}

/* ==========================================================================
   9. Ticker Pause on Hover
   ========================================================================== */
function initTickerPause() {
  const tickerContent = document.querySelector('.ticker-content');
  if (tickerContent) {
    tickerContent.addEventListener('mouseenter', () => {
      tickerContent.style.animationPlayState = 'paused';
    });
    tickerContent.addEventListener('mouseleave', () => {
      tickerContent.style.animationPlayState = 'running';
    });
  }
}

/* ==========================================================================
   10. Live Top 10 Stock Market Widget & Auto-Rotation
   ========================================================================== */
function initStockMarketWidget() {
  const stockWidget = document.getElementById('stock-market-widget');
  if (!stockWidget) return;

  const niftyPrice = document.getElementById('nifty-price');
  const niftyChange = document.getElementById('nifty-change');
  const sensexPrice = document.getElementById('sensex-price');
  const sensexChange = document.getElementById('sensex-change');

  const spotlightRank = document.getElementById('spotlight-rank');
  const spotlightSymbol = document.getElementById('spotlight-symbol');
  const spotlightName = document.getElementById('spotlight-name');
  const spotlightBadge = document.getElementById('spotlight-badge');
  const spotlightPrice = document.getElementById('spotlight-price');
  const spotlightProgress = document.getElementById('spotlight-progress');

  const stockListContainer = document.getElementById('stock-list-container');
  const stockRefreshBtn = document.getElementById('stock-refresh-btn');
  const stockLastUpdated = document.getElementById('stock-last-updated');
  const filterTabs = document.querySelectorAll('.stock-tab-btn');

  let allStocks = [];
  let currentFilter = 'all';
  let currentSpotlightIdx = 0;
  let progressInterval = null;
  let previousPrices = {};
  let isHoveringList = false;

  // Helper to get currently filtered stocks list
  function getFilteredStocks() {
    if (currentFilter === 'gainers') {
      return allStocks.filter(s => s.is_positive === true);
    } else if (currentFilter === 'losers') {
      return allStocks.filter(s => s.is_positive === false);
    }
    return allStocks;
  }

  // Fetch live stock data from API
  async function fetchStockMarketData(isManual = false) {
    if (isManual && stockRefreshBtn) {
      stockRefreshBtn.classList.add('spinning');
    }

    try {
      const response = await fetch('/market/top-stocks', {
        headers: { 'Accept': 'application/json' }
      });

      if (!response.ok) throw new Error('Failed to fetch stock data');
      const data = await response.json();

      if (data.status === 'success') {
        allStocks = data.stocks || [];

        // Render Indices
        if (data.indices && data.indices.length >= 2) {
          const nifty = data.indices[0];
          const sensex = data.indices[1];

          if (niftyPrice && niftyChange) {
            niftyPrice.textContent = nifty.price_formatted;
            niftyChange.textContent = (nifty.is_positive ? '▲ ' : '▼ ') + nifty.change_percent_formatted;
            niftyChange.className = 'index-change ' + (nifty.is_positive ? 'positive' : 'negative');
          }

          if (sensexPrice && sensexChange) {
            sensexPrice.textContent = sensex.price_formatted;
            sensexChange.textContent = (sensex.is_positive ? '▲ ' : '▼ ') + sensex.change_percent_formatted;
            sensexChange.className = 'index-change ' + (sensex.is_positive ? 'positive' : 'negative');
          }
        }

        // Update Last Updated Timestamp
        if (stockLastUpdated && data.last_updated) {
          stockLastUpdated.textContent = 'আপডেট: ' + data.last_updated;
        }

        // Render Stock List
        renderStockList();
        updateSpotlightCard(currentSpotlightIdx);
      }
    } catch (err) {
      console.error('Error loading stock market data:', err);
    } finally {
      if (stockRefreshBtn) {
        setTimeout(() => stockRefreshBtn.classList.remove('spinning'), 500);
      }
    }
  }

  // Render stock list with active filter
  function renderStockList() {
    if (!stockListContainer) return;

    const filteredStocks = getFilteredStocks();

    if (filteredStocks.length === 0) {
      stockListContainer.innerHTML = '<div style="text-align:center; padding:18px; color:var(--text-muted); font-size:0.85rem;"><i class="fas fa-info-circle"></i> এই ক্যাটাগরিতে কোনো স্টক নেই।</div>';
      return;
    }

    let html = '';
    filteredStocks.forEach((stock) => {
      const globalIdx = allStocks.findIndex(s => s.symbol === stock.symbol);
      const isSpotlight = (globalIdx === currentSpotlightIdx);
      const prevPrice = previousPrices[stock.symbol];
      let flashClass = '';

      if (prevPrice !== undefined && prevPrice !== stock.price) {
        flashClass = stock.price > prevPrice ? 'stock-flash-green' : 'stock-flash-red';
      }
      if (stock.price !== undefined) {
        previousPrices[stock.symbol] = stock.price;
      }

      const arrow = stock.is_positive ? '<i class="fas fa-arrow-trend-up"></i>' : '<i class="fas fa-arrow-trend-down"></i>';
      const pillClass = stock.is_positive ? 'positive' : 'negative';
      const cat = stock.is_positive ? 'gainers' : 'losers';

      html += `
        <div class="stock-item-row ${isSpotlight ? 'spotlight-active' : ''} ${flashClass}" data-global-idx="${globalIdx >= 0 ? globalIdx : 0}" data-symbol="${stock.symbol}" data-category="${cat}">
          <div class="stock-item-left">
            <span class="stock-item-rank">${stock.rank_bn || ''}</span>
            <div class="stock-item-info">
              <span class="stock-item-symbol">${stock.symbol}</span>
              <span class="stock-item-name" title="${stock.name_bn}">${stock.name_bn}</span>
            </div>
          </div>
          <div class="stock-item-right">
            <span class="stock-item-price">${stock.price_formatted}</span>
            <span class="stock-change-pill ${pillClass}">
              ${arrow} ${stock.change_percent_formatted}
            </span>
          </div>
        </div>
      `;
    });

    stockListContainer.innerHTML = html;
    attachRowEvents();
  }

  function attachRowEvents() {
    if (!stockListContainer) return;
    const rows = stockListContainer.querySelectorAll('.stock-item-row');
    rows.forEach(row => {
      row.addEventListener('mouseenter', () => {
        isHoveringList = true;
        const idx = parseInt(row.getAttribute('data-global-idx'), 10);
        if (!isNaN(idx)) {
          currentSpotlightIdx = idx;
          updateSpotlightCard(idx);
        }
      });

      row.addEventListener('mouseleave', () => {
        isHoveringList = false;
      });

      row.addEventListener('click', (e) => {
        e.preventDefault();
        const idx = parseInt(row.getAttribute('data-global-idx'), 10);
        if (!isNaN(idx)) {
          currentSpotlightIdx = idx;
          updateSpotlightCard(idx);
          resetSpotlightProgress();
        }
      });
    });
  }

  // Update Spotlight Featured Card
  function updateSpotlightCard(index) {
    if (!allStocks || allStocks.length === 0 || !allStocks[index]) return;

    const stock = allStocks[index];

    if (spotlightRank) spotlightRank.textContent = '#' + (stock.rank_bn || (index + 1));
    if (spotlightSymbol) spotlightSymbol.textContent = stock.symbol;
    if (spotlightName) spotlightName.textContent = stock.name_bn;
    if (spotlightPrice) spotlightPrice.textContent = stock.price_formatted;

    if (spotlightBadge) {
      const arrow = stock.is_positive ? '<i class="fas fa-arrow-trend-up"></i>' : '<i class="fas fa-arrow-trend-down"></i>';
      spotlightBadge.innerHTML = arrow + ' ' + (stock.change_percent_formatted || '');
      spotlightBadge.className = 'spotlight-badge ' + (stock.is_positive ? 'positive' : 'negative');
    }

    // Highlight corresponding row in list
    if (stockListContainer) {
      const rows = stockListContainer.querySelectorAll('.stock-item-row');
      rows.forEach(r => {
        if (parseInt(r.getAttribute('data-global-idx'), 10) === index) {
          r.classList.add('spotlight-active');
        } else {
          r.classList.remove('spotlight-active');
        }
      });
    }
  }

  // Start Spotlight Auto-Rotation and Progress Bar Animation
  function startSpotlightRotation() {
    if (progressInterval) clearInterval(progressInterval);

    updateSpotlightCard(currentSpotlightIdx);
    resetSpotlightProgress();

    const duration = 3600; // 3.6s per stock spotlight cycle
    const step = 60;
    let elapsed = 0;

    progressInterval = setInterval(() => {
      if (!isHoveringList) {
        elapsed += step;
        const pct = Math.min((elapsed / duration) * 100, 100);
        if (spotlightProgress) {
          spotlightProgress.style.width = pct + '%';
        }
        if (elapsed >= duration) {
          elapsed = 0;
          const currentFiltered = getFilteredStocks();
          if (currentFiltered.length > 0) {
            const currentFilteredIdx = currentFiltered.findIndex(s => allStocks.indexOf(s) === currentSpotlightIdx);
            const nextFilteredIdx = (currentFilteredIdx + 1) % currentFiltered.length;
            const nextStock = currentFiltered[nextFilteredIdx];
            currentSpotlightIdx = allStocks.findIndex(s => s.symbol === nextStock.symbol);
            if (currentSpotlightIdx < 0) currentSpotlightIdx = 0;
            updateSpotlightCard(currentSpotlightIdx);
          }
        }
      }
    }, step);
  }

  function resetSpotlightProgress() {
    if (spotlightProgress) {
      spotlightProgress.style.width = '0%';
    }
  }

  // Filter Tab Click Handlers
  filterTabs.forEach(tab => {
    tab.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();

      filterTabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      currentFilter = tab.getAttribute('data-filter') || 'all';

      const filtered = getFilteredStocks();
      if (filtered.length > 0) {
        currentSpotlightIdx = allStocks.findIndex(s => s.symbol === filtered[0].symbol);
        if (currentSpotlightIdx < 0) currentSpotlightIdx = 0;
      }

      renderStockList();
      updateSpotlightCard(currentSpotlightIdx);
      resetSpotlightProgress();
    });
  });

  // Refresh Button Click Handler
  if (stockRefreshBtn) {
    stockRefreshBtn.addEventListener('click', (e) => {
      e.preventDefault();
      fetchStockMarketData(true);
    });
  }

  // Initial DOM Parse (Instant zero-delay start)
  const initialRows = stockListContainer ? stockListContainer.querySelectorAll('.stock-item-row') : [];
  if (initialRows.length > 0) {
    initialRows.forEach((row, i) => {
      const rankEl = row.querySelector('.stock-item-rank');
      const symEl = row.querySelector('.stock-item-symbol');
      const nameEl = row.querySelector('.stock-item-name');
      const priceEl = row.querySelector('.stock-item-price');
      const pillEl = row.querySelector('.stock-change-pill');
      const isPos = row.getAttribute('data-is-positive') === '1' || (pillEl ? pillEl.classList.contains('positive') : true);

      allStocks.push({
        rank: i + 1,
        rank_bn: rankEl ? rankEl.textContent.trim() : String(i + 1),
        symbol: symEl ? symEl.textContent.trim() : (row.getAttribute('data-symbol') || ''),
        name_bn: nameEl ? nameEl.textContent.trim() : '',
        price_formatted: priceEl ? priceEl.textContent.trim() : '₹০.০০',
        change_percent_formatted: pillEl ? pillEl.textContent.trim() : '০.০০%',
        is_positive: isPos,
      });
    });
    attachRowEvents();
    startSpotlightRotation();
  }

  // Initial background fetch for real-time live data
  fetchStockMarketData();

  // Auto refresh stock data every 30 seconds
  setInterval(() => {
    fetchStockMarketData(false);
  }, 30000);
}
