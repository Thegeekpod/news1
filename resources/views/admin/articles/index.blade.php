@extends('layouts.admin')

@section('title', 'Articles')
@section('header_title', 'Articles')

@section('content')


<div class="admin-card">
  <div class="card-header-flex">
    <h2 class="card-title">All Articles</h2>
    <div style="display: flex; gap: 10px;">
      <a href="{{ route('admin.settings') }}#article-view-settings" class="btn-admin btn-admin-secondary">
        <i class="fas fa-cog"></i> View Count Settings
      </a>
      <a href="{{ route('admin.articles.create') }}" class="btn-admin btn-admin-primary">
        <i class="fas fa-plus"></i> Write New Article
      </a>
    </div>
  </div>

  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Thumbnail</th>
          <th>Title</th>
          <th>Category</th>
          <th>Views</th>
          <th>Author</th>
          <th>Status</th>
          <th>Date</th>
          <th style="text-align: right;">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($articles as $art)
          <tr>
            <td>
              <img src="{{ $art->thumbnail_url ?? 'https://picsum.photos/seed/'.$art->id.'/120/80' }}" alt="Thumbnail" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-color);">
            </td>
            <td style="font-weight: 600; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
              {{ $art->title }}
              @if($art->is_lead)
                <span class="badge badge-danger" style="font-size: 9px; padding: 2px 4px; margin-left: 4px;">Lead</span>
              @endif
              @if($art->is_breaking)
                <span class="badge badge-primary" style="font-size: 9px; padding: 2px 4px; margin-left: 4px;">Breaking</span>
              @endif
            </td>
            <td><span class="badge badge-primary">{{ $art->category->name_bn }}</span></td>
            <td>{{ number_format($art->view_count) }}</td>
            <td>{{ $art->author->name }}</td>
            <td>
              @if($art->status === 'published')
                <span class="badge badge-success">Published</span>
              @else
                <span class="badge badge-danger">Draft</span>
              @endif
            </td>
            <td>{{ $art->created_at->format('M d, Y') }}</td>
            <td style="text-align: right;">
              <div style="display: flex; gap: 8px; justify-content: flex-end;">
                <a href="{{ route('admin.articles.edit', $art->id) }}" class="btn-admin btn-admin-secondary" style="padding: 6px 10px; font-size: 13px;">
                  <i class="fas fa-edit"></i> Edit
                </a>
                
                <form action="{{ route('admin.articles.destroy', $art->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this article?');" style="display: inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-admin btn-admin-danger" style="padding: 6px 10px; font-size: 13px;">
                    <i class="fas fa-trash"></i> Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 30px;">No articles found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  
  <!-- Custom pagination mapping class styles -->
  @if($articles->hasPages())
    <div class="pagination" role="navigation" aria-label="Page navigation" style="margin-top: 24px; display: flex; justify-content: center; gap: 6px;">
        @if ($articles->onFirstPage())
            <button class="page-btn" aria-label="Previous Page" style="opacity: 0.5; background: rgba(255,255,255,0.05); color: var(--text-muted); padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 4px;" disabled><i class="fas fa-chevron-left"></i></button>
        @else
            <a href="{{ $articles->previousPageUrl() }}" class="page-btn" aria-label="Previous Page" style="color: var(--text-main); background: rgba(255,255,255,0.05); padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 4px; text-decoration: none;"><i class="fas fa-chevron-left"></i></a>
        @endif
 
        @foreach ($articles->getUrlRange(1, $articles->lastPage()) as $page => $url)
            @if ($page == $articles->currentPage())
                <button class="page-btn active" aria-label="Page {{ $page }}" style="background: var(--primary); color: #ffffff; padding: 8px 12px; border: 1px solid var(--primary); border-radius: 4px; font-weight: 600;">{{ $page }}</button>
            @else
                <a href="{{ $url }}" class="page-btn" aria-label="Page {{ $page }}" style="color: var(--text-main); background: rgba(255,255,255,0.05); padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 4px; text-decoration: none;">{{ $page }}</a>
            @endif
        @endforeach
 
        @if ($articles->hasMorePages())
            <a href="{{ $articles->nextPageUrl() }}" class="page-btn" aria-label="Next Page" style="color: var(--text-main); background: rgba(255,255,255,0.05); padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 4px; text-decoration: none;"><i class="fas fa-chevron-right"></i></a>
        @else
            <button class="page-btn" aria-label="Next Page" style="opacity: 0.5; background: rgba(255,255,255,0.05); color: var(--text-muted); padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 4px;" disabled><i class="fas fa-chevron-right"></i></button>
        @endif
    </div>
  @endif
</div>
@endsection
