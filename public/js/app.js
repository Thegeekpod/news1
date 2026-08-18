/**
 * News 1 - Bengali News Portal
 * Interactive UI Functionality
 */

document.addEventListener('DOMContentLoaded', () => {

  // ===== SEARCH OVERLAY =====
  const searchToggle = document.getElementById('search-toggle');
  const searchOverlay = document.getElementById('search-overlay');
  const searchClose = document.getElementById('search-close');
  const searchInput = document.getElementById('search-input');

  if (searchToggle && searchOverlay) {
    searchToggle.addEventListener('click', () => {
      searchOverlay.classList.add('active');
      setTimeout(() => searchInput && searchInput.focus(), 100);
    });

    searchClose && searchClose.addEventListener('click', () => {
      searchOverlay.classList.remove('active');
    });

    searchOverlay.addEventListener('click', (e) => {
      if (e.target === searchOverlay) {
        searchOverlay.classList.remove('active');
      }
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') searchOverlay.classList.remove('active');
    });
  }

  // ===== MOBILE NAV =====
  const mobileMenuToggle = document.getElementById('mobile-menu-toggle-btn');
  const navMenu = document.querySelector('.nav-menu');

  if (mobileMenuToggle && navMenu) {
    mobileMenuToggle.addEventListener('click', (e) => {
      e.stopPropagation();
      navMenu.classList.toggle('mobile-open');
      const icon = mobileMenuToggle.querySelector('i');
      if (icon) {
        if (navMenu.classList.contains('mobile-open')) {
          icon.className = 'fas fa-times';
        } else {
          icon.className = 'fas fa-bars';
        }
      }
    });

    document.addEventListener('click', (e) => {
      if (navMenu && !navMenu.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
        navMenu.classList.remove('mobile-open');
        const icon = mobileMenuToggle.querySelector('i');
        if (icon) icon.className = 'fas fa-bars';
      }
    });
  }

  // ===== SCROLL TO TOP =====
  const scrollTopBtn = document.getElementById('scroll-top');

  if (scrollTopBtn) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 400) {
        scrollTopBtn.classList.add('visible');
      } else {
        scrollTopBtn.classList.remove('visible');
      }
    }, { passive: true });

    scrollTopBtn.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // ===== STICKY HEADER SHADOW =====
  const header = document.getElementById('site-header');
  if (header) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 10) {
        header.style.boxShadow = '0 4px 20px rgba(0,0,0,0.15)';
      } else {
        header.style.boxShadow = '0 4px 12px rgba(0,0,0,0.10)';
      }
    }, { passive: true });
  }

  // ===== SPORTS TABS =====
  const catTabs = document.querySelectorAll('.cat-tab');
  const sportsContent = document.getElementById('sports-content');
  const tabSlider = document.getElementById('tab-slider');

  const sportsData = window.sportsData || {
    cricket: [
      { img: 'https://picsum.photos/seed/s1/300/200', title: 'রোহিত শর্মার অবসরের পর ভারতীয় ক্রিকেটে নতুন ব্যাটিং অর্ডার', time: '৪ ঘণ্টা আগে', views: '৮,৪৫২' },
      { img: 'https://picsum.photos/seed/s2/300/200', title: 'বিরাট কোহলি টেস্ট ক্রিকেটে ফিরছেন — দলে ফেরার ইঙ্গিত', time: '৬ ঘণ্টা আগে', views: '৬,১২৩' },
      { img: 'https://picsum.photos/seed/s3/300/200', title: 'আইপিএলের সবচেয়ে দামি খেলোয়াড় হলেন ঋষভ পন্ত, ২৭ কোটিতে বিক্রি', time: '৮ ঘণ্টা আগে', views: '৫,৭৮৯' },
    ],
    football: [
      { img: 'https://picsum.photos/seed/f1/300/200', title: 'মেসির MLS-এ নতুন মৌসুম শুরু — প্রথম ম্যাচেই হ্যাটট্রিক', time: '৩ ঘণ্টা আগে', views: '৭,৩৪১' },
      { img: 'https://picsum.photos/seed/f2/300/200', title: 'ইস্টবেঙ্গলের নতুন কোচ নিয়োগ — আই-লিগে নতুন আশা', time: '৫ ঘণ্টা আগে', views: '৪,৮৯৬' },
      { img: 'https://picsum.photos/seed/f3/300/200', title: 'FIFA বিশ্বকাপ ২০২৬-এর বাছাই পর্বে ভারত', time: '৭ ঘণ্টা আগে', views: '৯,২১৫' },
    ],
    kabaddi: [
      { img: 'https://picsum.photos/seed/k1/300/200', title: 'প্রো কাবাডি লিগে বাংলার দলের দুর্দান্ত পারফরম্যান্স', time: '২ ঘণ্টা আগে', views: '৩,৬৭৮' },
      { img: 'https://picsum.photos/seed/k2/300/200', title: 'জাতীয় কাবাডি চ্যাম্পিয়নশিপে পশ্চিমবঙ্গ সেরা', time: '৬ ঘণ্টা আগে', views: '২,৫৪৩' },
    ],
    others: [
      { img: 'https://picsum.photos/seed/o1/300/200', title: 'ব্যাডমিন্টনে ভারতের নতুন তারকা — পিভি সিন্ধু ঐতিহ্য এগিয়ে চলছে', time: '৪ ঘণ্টা আগে', views: '৪,১১২' },
      { img: 'https://picsum.photos/seed/o2/300/200', title: 'অলিম্পিক ২০২৮-এ ভারতের লক্ষ্যমাত্রা ১০টি পদক', time: '৮ ঘণ্টা আগে', views: '৩,৮৯০' },
    ],
  };

  // Move slider to the active tab
  function moveSlider(activeTab) {
    if (!tabSlider || !activeTab) return;
    const tabRect = activeTab.getBoundingClientRect();
    const parentRect = activeTab.parentElement.getBoundingClientRect();
    tabSlider.style.width = tabRect.width + 'px';
    tabSlider.style.transform = `translateX(${tabRect.left - parentRect.left}px)`;
  }

  function renderSportsContent(tab) {
    if (!sportsContent) return;
    // Fade out
    sportsContent.style.opacity = '0';
    sportsContent.style.transform = 'translateY(8px)';

    setTimeout(() => {
      const items = sportsData[tab] || sportsData.cricket;
      sportsContent.innerHTML = items.map(item => `
        <article class="card-horizontal">
          <div class="card-img-wrap">
            <a href="article.html"><img src="${item.img}" alt="" loading="lazy"></a>
          </div>
          <div class="card-body">
            <a href="article.html"><h3 class="card-title">${item.title}</h3></a>
            <div class="card-meta">
              <span class="card-meta-item"><i class="fas fa-clock"></i> ${item.time}</span>
              <span class="card-meta-item"><i class="fas fa-eye"></i> ${item.views}</span>
            </div>
          </div>
        </article>
      `).join('');
      // Fade in
      sportsContent.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
      sportsContent.style.opacity = '1';
      sportsContent.style.transform = 'translateY(0)';
    }, 180);
  }

  catTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      catTabs.forEach(t => {
        t.classList.remove('active');
        t.setAttribute('aria-selected', 'false');
      });
      tab.classList.add('active');
      tab.setAttribute('aria-selected', 'true');
      moveSlider(tab);
      renderSportsContent(tab.dataset.tab);
    });
  });

  // Init slider on load
  const activeTab = document.querySelector('.cat-tab.active');
  if (activeTab && tabSlider) {
    // Delay to ensure layout is ready
    requestAnimationFrame(() => {
      tabSlider.style.transition = 'none';
      moveSlider(activeTab);
      requestAnimationFrame(() => {
        tabSlider.style.transition = 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), width 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
      });
    });
  }

  // ===== PAGINATION =====
  const pageBtns = document.querySelectorAll('.page-btn');
  pageBtns.forEach(btn => {
    if (!btn.querySelector('i')) {
      btn.addEventListener('click', () => {
        pageBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }
  });

  // ===== LIVE DATE/TIME in Top Bar =====
  function formatBengaliDate() {
    const now = new Date();
    const days = ['রবিবার', 'সোমবার', 'মঙ্গলবার', 'বুধবার', 'বৃহস্পতিবার', 'শুক্রবার', 'শনিবার'];
    const months = ['জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'];

    const day = days[now.getDay()];
    const date = toBengaliNumerals(now.getDate());
    const month = months[now.getMonth()];
    const year = toBengaliNumerals(now.getFullYear());

    return `${day}, ${date} ${month} ${year}`;
  }

  function toBengaliNumerals(num) {
    const bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    return String(num).split('').map(d => bn[parseInt(d)] || d).join('');
  }

  const dateEl = document.getElementById('current-date');
  if (dateEl) {
    dateEl.textContent = formatBengaliDate();
  }

  // ===== LAZY IMAGE LOADING FALLBACK =====
  const images = document.querySelectorAll('img[loading="lazy"]');
  images.forEach(img => {
    img.addEventListener('error', () => {
      img.src = `https://picsum.photos/seed/${Math.random().toString(36).substr(2, 5)}/600/400`;
    });
  });



  // ===== NEWSLETTER FORM =====
  const newsletterBtns = document.querySelectorAll('[style*="background: var(--primary-red)"]');
  newsletterBtns.forEach(btn => {
    if (btn.textContent.trim() === 'সাবস্ক্রাইব করুন') {
      btn.addEventListener('click', (e) => {
        const input = btn.previousElementSibling;
        if (input && input.type === 'email') {
          if (!input.value) {
            input.style.border = '2px solid #e8101a';
            input.placeholder = 'ইমেইল দিন';
            setTimeout(() => {
              input.style.border = 'none';
            }, 2000);
          } else {
            btn.textContent = '✓ সাবস্ক্রাইব হয়েছেন!';
            btn.style.background = '#16a34a';
            input.value = '';
            setTimeout(() => {
              btn.textContent = 'সাবস্ক্রাইব করুন';
              btn.style.background = '';
            }, 3000);
          }
        }
      });
    }
  });

  // Scroll-triggered fade-in removed — all elements load visible immediately.

  // ===== GALLERY ITEMS - Simulate lightbox hint =====
  const galleryItems = document.querySelectorAll('.gallery-item');
  galleryItems.forEach(item => {
    item.addEventListener('click', () => {
      const img = item.querySelector('img');
      if (img) {
        // Simple full-screen preview
        const overlay = document.createElement('div');
        overlay.style.cssText = `
          position: fixed; inset: 0; background: rgba(0,0,0,0.92);
          display: flex; align-items: center; justify-content: center;
          z-index: 99999; cursor: zoom-out; padding: 20px;
        `;
        const bigImg = document.createElement('img');
        bigImg.src = img.src;
        bigImg.style.cssText = 'max-width: 90vw; max-height: 85vh; border-radius: 8px; box-shadow: 0 20px 60px rgba(0,0,0,0.5);';
        overlay.appendChild(bigImg);
        overlay.addEventListener('click', () => document.body.removeChild(overlay));
        document.addEventListener('keydown', function handler(e) {
          if (e.key === 'Escape') {
            if (document.body.contains(overlay)) document.body.removeChild(overlay);
            document.removeEventListener('keydown', handler);
          }
        });
        document.body.appendChild(overlay);
      }
    });
  });

  // ===== AD BANNER SLIDER =====
  const adSliders = document.querySelectorAll('.ad-slider-container');
  adSliders.forEach(slider => {
    const slides = slider.querySelectorAll('.ad-slide');
    if (slides.length <= 1) {
      if (slides.length === 1) {
        slides[0].classList.add('active');
      }
      return;
    }
    
    let currentIndex = 0;
    slides[currentIndex].classList.add('active');
    
    setInterval(() => {
      slides[currentIndex].classList.remove('active');
      currentIndex = (currentIndex + 1) % slides.length;
      slides[currentIndex].classList.add('active');
    }, 4000);
  });

  // ===== VIDEO POPUP MODAL =====
  const videoCards = document.querySelectorAll('.video-card');
  const videoModal = document.getElementById('videoModal');
  const videoIframe = document.getElementById('videoIframe');
  const closeModal = document.querySelector('.close-video-modal');

  function getYoutubeEmbedUrl(url) {
    const regExp = /^.*(navigator\.|\/\/)?(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    const match = url.match(regExp);
    const videoId = (match && match[3].length === 11) ? match[3] : null;
    if (videoId) {
      return `https://www.youtube.com/embed/${videoId}?autoplay=1`;
    }
    return url;
  }

  if (videoCards.length > 0 && videoModal && videoIframe && closeModal) {
    videoCards.forEach(card => {
      card.addEventListener('click', (e) => {
        e.preventDefault();
        const youtubeUrl = card.getAttribute('data-youtube-url');
        if (youtubeUrl) {
          const embedUrl = getYoutubeEmbedUrl(youtubeUrl);
          videoIframe.src = embedUrl;
          videoModal.style.display = 'flex';
          document.body.style.overflow = 'hidden';
          document.body.classList.add('video-modal-open');
        }
      });
    });

    const closeVideo = () => {
      videoModal.style.display = 'none';
      videoIframe.src = '';
      document.body.style.overflow = '';
      document.body.classList.remove('video-modal-open');
    };

    closeModal.addEventListener('click', closeVideo);
    videoModal.addEventListener('click', (e) => {
      if (e.target === videoModal) {
        closeVideo();
      }
    });

    // Close on escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && videoModal.style.display === 'flex') {
        closeVideo();
      }
    });
  }

});
