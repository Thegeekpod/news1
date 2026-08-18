@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header_title', 'Dashboard')

@section('content')

<!-- Dashboard Stat Cards -->
<div class="dashboard-stats">
  <!-- Articles Stat -->
  <div class="stat-card">
    <div class="stat-info">
      <div class="stat-num">{{ $totalArticles }}</div>
      <div class="stat-label">Total Articles</div>
    </div>
    <div class="stat-icon"><i class="fas fa-newspaper"></i></div>
  </div>
  
  <!-- Categories Stat -->
  <div class="stat-card" style="--primary: #10b981;">
    <div class="stat-info">
      <div class="stat-num">{{ $totalCategories }}</div>
      <div class="stat-label">Total Categories</div>
    </div>
    <div class="stat-icon"><i class="fas fa-folder-open"></i></div>
  </div>
  
  <!-- Subscribers Stat -->
  <div class="stat-card" style="--primary: #ec4899;">
    <div class="stat-info">
      <div class="stat-num">{{ $totalSubscribers }}</div>
      <div class="stat-label">Newsletter Subscribers</div>
    </div>
    <div class="stat-icon"><i class="fas fa-users"></i></div>
  </div>
</div>

<!-- Main Panels split -->
<div class="grid-2-col">
  <!-- Recent Articles -->
  <div class="admin-card">
    <div class="card-header-flex">
      <h2 class="card-title">Recent Articles</h2>
      <a href="{{ route('admin.articles.index') }}" class="btn-admin btn-admin-secondary btn-sm" style="padding: 6px 12px; font-size: 12px;">View All</a>
    </div>
    
    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Author</th>
            <th>Status</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentArticles as $art)
            <tr>
              <td style="font-weight: 600; max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                <a href="{{ route('admin.articles.edit', $art->id) }}" style="color: #ffffff; text-decoration: none; hover: underline;">{{ $art->title }}</a>
              </td>
              <td><span class="badge badge-primary">{{ $art->category->name_bn ?? 'Uncategorized' }}</span></td>
              <td>{{ $art->author->name ?? 'Admin' }}</td>
              <td>
                @if($art->status === 'published')
                  <span class="badge badge-success">Published</span>
                @else
                  <span class="badge badge-danger">Draft</span>
                @endif
              </td>
              <td>{{ $art->created_at->format('M d, Y') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="text-align: center; color: var(--text-muted);">No articles found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Recent Newsletter Subscribers -->
  <div class="admin-card">
    <div class="card-header-flex">
      <h2 class="card-title">Recent Subscribers</h2>
      <a href="{{ route('admin.newsletter') }}" class="btn-admin btn-admin-secondary btn-sm" style="padding: 6px 12px; font-size: 12px;">All</a>
    </div>
    
    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Email</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentSubscribers as $sub)
            <tr>
              <td style="font-weight: 500; color: #ffffff;">{{ $sub->email }}</td>
              <td style="font-size: 12px; color: var(--text-muted);">{{ $sub->created_at->format('M d, Y') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="2" style="text-align: center; color: var(--text-muted);">No newsletter subscribers.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
