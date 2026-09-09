@extends('layouts.admin')

@section('title', 'SEO Management')
@section('header_title', 'SEO Details Manager')

@section('content')

@if(session('success'))
  <div style="background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; color: #10b981; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 500;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
  </div>
@endif

@if(session('error'))
  <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #ef4444; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 500;">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
  </div>
@endif

<div class="admin-card">
  <div class="card-header-flex">
    <div>
      <h2 class="card-title">All SEO Pages</h2>
      <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Configure Meta Title, Description, and Custom Head Scripts for Home, Category, and specific page URLs.</p>
    </div>
    <a href="{{ route('admin.seo.create') }}" class="btn-admin btn-admin-primary">
      <i class="fas fa-plus"></i> Add New SEO Page
    </a>
  </div>

  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Page URL</th>
          <th>Meta Title</th>
          <th>Meta Description</th>
          <th>Custom Tags / Scripts</th>
          <th style="text-align: right;">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($seoPages as $seo)
          <tr>
            <td style="font-weight: 700;">
              <code>{{ $seo->page_url }}</code>
              @if($seo->page_url === '/')
                <span style="background: #2563eb; color: #fff; font-size: 11px; padding: 2px 6px; border-radius: 4px; margin-left: 6px;">Home</span>
              @elseif(str_starts_with($seo->page_url, '/category/'))
                <span style="background: #059669; color: #fff; font-size: 11px; padding: 2px 6px; border-radius: 4px; margin-left: 6px;">Category</span>
              @endif
            </td>
            <td style="font-weight: 600; color: #ffffff; max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
              {{ $seo->meta_title ?: '—' }}
            </td>
            <td style="max-width: 320px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: var(--text-muted);">
              {{ $seo->meta_description ?: '—' }}
            </td>
            <td>
              @if(!empty($seo->other_tags))
                <span style="background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); font-size: 12px; padding: 3px 8px; border-radius: 4px; font-weight: 600;">
                  <i class="fas fa-code"></i> Configured
                </span>
              @else
                <span style="color: var(--text-muted); font-size: 12px;">None</span>
              @endif
            </td>
            <td style="text-align: right;">
              <div style="display: flex; gap: 8px; justify-content: flex-end;">
                <a href="{{ route('admin.seo.edit', $seo->id) }}" class="btn-admin btn-admin-secondary" style="padding: 6px 12px; font-size: 13px;">
                  <i class="fas fa-edit"></i> Edit
                </a>
                
                <form action="{{ route('admin.seo.destroy', $seo->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete SEO settings for {{ $seo->page_url }}?');" style="display: inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-admin btn-admin-danger" style="padding: 6px 12px; font-size: 13px;">
                    <i class="fas fa-trash"></i> Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 35px;">
              <i class="fas fa-search" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
              No SEO settings found. Click <strong>"Add New SEO Page"</strong> to create one.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($seoPages->hasPages())
    <div style="margin-top: 20px;">
      {{ $seoPages->links() }}
    </div>
  @endif
</div>

@endsection
