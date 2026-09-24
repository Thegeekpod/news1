<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <title>@yield('title', 'Dashboard') – News 1 Admin Panel</title>
  
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
  <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">
  @yield('styles')
</head>

<body>

  <!-- ===== SIDEBAR ===== -->
  <aside class="admin-sidebar" id="admin-sidebar">
    <div class="sidebar-brand">
      <span>News</span><span>1</span>
    </div>
    
    <ul class="sidebar-menu">
      <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-pie"></i> Dashboard</a>
      </li>
      <li class="{{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
        <a href="{{ route('admin.articles.index') }}"><i class="fas fa-file-invoice"></i> Articles</a>
      </li>
      <li class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
        <a href="{{ route('admin.categories.index') }}"><i class="fas fa-folder-open"></i> Categories</a>
      </li>
      <li class="{{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
        <a href="{{ route('admin.tags.index') }}"><i class="fas fa-hashtag"></i> Tags Manager</a>
      </li>
      <li class="{{ request()->routeIs('admin.tickers.*') ? 'active' : '' }}">
        <a href="{{ route('admin.tickers.index') }}"><i class="fas fa-bolt"></i> Breaking Tickers</a>
      </li>
      <li class="{{ request()->routeIs('admin.videos.*') ? 'active' : '' }}">
        <a href="{{ route('admin.videos.index') }}"><i class="fas fa-video"></i> Video Gallery</a>
      </li>
      <li class="{{ request()->routeIs('admin.ads.*') ? 'active' : '' }}">
        <a href="{{ route('admin.ads.index') }}"><i class="fas fa-rectangle-ad"></i> Advertisements</a>
      </li>
      <li class="{{ request()->routeIs('admin.seo.*') ? 'active' : '' }}">
        <a href="{{ route('admin.seo.index') }}"><i class="fas fa-globe"></i> SEO Details</a>
      </li>
      <li class="{{ request()->routeIs('admin.sitemap.*') ? 'active' : '' }}">
        <a href="{{ route('admin.sitemap.index') }}"><i class="fas fa-sitemap"></i> Sitemap Control</a>
      </li>
      <li class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
        <a href="{{ route('admin.settings') }}"><i class="fas fa-cogs"></i> Settings Panel</a>
      </li>
      <li class="{{ request()->routeIs('admin.newsletter') ? 'active' : '' }}">
        <a href="{{ route('admin.newsletter') }}"><i class="fas fa-envelope-open-text"></i> Newsletter Subscribers</a>
      </li>
    </ul>
    
    @auth
    <div class="sidebar-user">
      <img src="{{ auth()->user()->avatar_url ?? 'https://picsum.photos/seed/user/100/100' }}" alt="User Avatar" class="user-avatar">
      <div class="user-info">
        <div class="user-name">{{ auth()->user()->name }}</div>
        <div class="user-role">{{ auth()->user()->role === 'admin' ? 'Admin' : 'Author' }}</div>
      </div>
    </div>
    @endauth
  </aside>

  <!-- ===== MAIN CONTENT ===== -->
  <main class="admin-main">
    <header class="admin-header">
      <div class="header-left">
        <button class="mobile-toggle" id="mobile-sidebar-toggle" aria-label="Toggle Sidebar">
          <i class="fas fa-bars"></i>
        </button>
        <div class="header-title">@yield('header_title', 'Dashboard')</div>
      </div>
      
      <div class="header-right">
        <a href="{{ route('home') }}" class="btn-view-site" target="_blank">
          <i class="fas fa-external-link-alt"></i> View Site
        </a>
        
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
          @csrf
          <button type="submit" class="btn-logout">
            <i class="fas fa-sign-out-alt"></i> Logout
          </button>
        </form>
      </div>
    </header>
    
    <div class="admin-content">
      @yield('content')
    </div>
  </main>

  <!-- Notification Toast alerts -->
  @if(session('success'))
    <div class="alert-toast" id="alert-toast">
      <i class="fas fa-check-circle" style="color: var(--success); font-size: 20px;"></i>
      <div>
        <div style="font-weight: 600; font-size: 14px;">Success!</div>
        <div style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">{{ session('success') }}</div>
      </div>
    </div>
  @endif
  
  @if(session('error') || (isset($errors) && $errors->any()))
    <div class="alert-toast" id="alert-toast" style="border-left-color: var(--danger)">
      <i class="fas fa-exclamation-circle" style="color: var(--danger); font-size: 20px;"></i>
      <div>
        <div style="font-weight: 600; font-size: 14px;">Error!</div>
        <div style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">
          @if(session('error'))
            {{ session('error') }}
          @else
            <ul style="margin: 0; padding-left: 16px; list-style-type: disc;">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          @endif
        </div>
      </div>
    </div>
  @endif

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Mobile sidebar toggle handler
      const toggleBtn = document.getElementById('mobile-sidebar-toggle');
      const sidebar = document.getElementById('admin-sidebar');
      
      if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', () => {
          sidebar.classList.toggle('active');
        });
        
        // Hide sidebar clicking outside
        document.addEventListener('click', (e) => {
          if (sidebar.classList.contains('active') && !sidebar.contains(e.target) && e.target !== toggleBtn && !toggleBtn.contains(e.target)) {
            sidebar.classList.remove('active');
          }
        });
      }
      
      // Auto-hide alert toasts after 4 seconds
      const toast = document.getElementById('alert-toast');
      if (toast) {
        setTimeout(() => {
          toast.style.animation = 'slideIn 0.3s ease reverse forwards';
          setTimeout(() => toast.remove(), 300);
        }, 4000);
      }
    });
  </script>
  @yield('scripts')
</body>

</html>
