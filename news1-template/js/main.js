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
