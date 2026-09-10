@extends('layouts.admin')

@section('title', 'SEO Management')
@section('header_title', 'SEO Details & Dynamic Sitemap')

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

<!-- Dynamic XML Sitemap Card -->
<div class="admin-card" style="margin-bottom: 24px; border: 1px solid rgba(59, 130, 246, 0.3); background: linear-gradient(145deg, rgba(30, 41, 59, 0.8), rgba(15, 23, 42, 0.9));">
  <div class="card-header-flex" style="align-items: flex-start; margin-bottom: 18px;">
    <div>
      <div style="display: flex; align-items: center; gap: 10px;">
        <h2 class="card-title" style="margin: 0; display: flex; align-items: center; gap: 8px;">
          <i class="fas fa-sitemap" style="color: #60a5fa;"></i> Dynamic XML Sitemaps
        </h2>
        <span style="background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.4); font-size: 11px; padding: 3px 8px; border-radius: 999px; font-weight: 700; display: inline-flex; align-items: center; gap: 5px;">
          <span style="width: 7px; height: 7px; background: #10b981; border-radius: 50%; display: inline-block; box-shadow: 0 0 6px #10b981;"></span>
          Auto-Updating Real-Time
        </span>
      </div>
      <p style="font-size: 13px; color: var(--text-muted); margin-top: 6px;">
        Your XML sitemaps update automatically in real-time as new blog articles, categories, and tags are created, updated, or deleted. No manual rebuild is ever needed.
      </p>
    </div>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
      <a href="{{ route('sitemap.index') }}" target="_blank" class="btn-admin btn-admin-primary" style="background: #2563eb; border-color: #3b82f6;">
        <i class="fas fa-external-link-alt"></i> View Live Sitemap.xml
      </a>
      <a href="{{ route('sitemap.news') }}" target="_blank" class="btn-admin btn-admin-secondary">
        <i class="fas fa-newspaper"></i> Google News XML
      </a>
    </div>
  </div>

  <!-- Real-time Stats Grid -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; margin-bottom: 20px;">
    <div style="background: rgba(255, 255, 255, 0.04); padding: 14px 16px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.06);">
      <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Articles Indexed</div>
      <div style="font-size: 22px; font-weight: 700; color: #60a5fa; margin-top: 4px;">{{ number_format($articlesCount ?? 0) }}</div>
      <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">Dynamic & Images</div>
    </div>
    <div style="background: rgba(255, 255, 255, 0.04); padding: 14px 16px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.06);">
      <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Categories</div>
      <div style="font-size: 22px; font-weight: 700; color: #34d399; margin-top: 4px;">{{ number_format($categoriesCount ?? 0) }}</div>
      <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">Daily Changefreq</div>
    </div>
    <div style="background: rgba(255, 255, 255, 0.04); padding: 14px 16px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.06);">
      <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Tags</div>
      <div style="font-size: 22px; font-weight: 700; color: #f59e0b; margin-top: 4px;">{{ number_format($tagsCount ?? 0) }}</div>
      <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">Tag Archive URLs</div>
    </div>
    <div style="background: rgba(255, 255, 255, 0.04); padding: 14px 16px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.06);">
      <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Custom SEO Pages</div>
      <div style="font-size: 22px; font-weight: 700; color: #a855f7; margin-top: 4px;">{{ number_format($seoCount ?? 0) }}</div>
      <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">Custom Meta URLs</div>
    </div>
  </div>

  <!-- Sitemap URLs Table -->
  <div style="background: rgba(0, 0, 0, 0.25); border-radius: 8px; padding: 14px 18px; border: 1px solid rgba(255, 255, 255, 0.08);">
    <div style="font-size: 13px; font-weight: 600; color: #e2e8f0; margin-bottom: 10px;">
      <i class="fas fa-link" style="color: #60a5fa;"></i> Search Engine Ready Sitemap Endpoints:
    </div>
    <div style="display: flex; flex-direction: column; gap: 8px;">
      <div style="display: flex; align-items: center; justify-content: space-between; background: rgba(255,255,255,0.03); padding: 8px 12px; border-radius: 6px; flex-wrap: wrap; gap: 10px;">
        <div>
          <span style="font-weight: 600; color: #93c5fd; font-size: 13px;">Full Sitemap (Main):</span>
          <code style="margin-left: 8px; color: #f8fafc; font-size: 12px;">{{ route('sitemap.index') }}</code>
        </div>
        <div style="display: flex; gap: 6px;">
          <button onclick="copyToClipboard('{{ route('sitemap.index') }}')" class="btn-admin btn-admin-secondary" style="padding: 4px 10px; font-size: 11px;">
            <i class="fas fa-copy"></i> Copy
          </button>
          <a href="{{ route('sitemap.index') }}" target="_blank" class="btn-admin btn-admin-secondary" style="padding: 4px 10px; font-size: 11px;">
            <i class="fas fa-external-link-alt"></i> Open
          </a>
        </div>
      </div>

      <div style="display: flex; align-items: center; justify-content: space-between; background: rgba(255,255,255,0.03); padding: 8px 12px; border-radius: 6px; flex-wrap: wrap; gap: 10px;">
        <div>
          <span style="font-weight: 600; color: #86efac; font-size: 13px;">Google News Sitemap:</span>
          <code style="margin-left: 8px; color: #f8fafc; font-size: 12px;">{{ route('sitemap.news') }}</code>
        </div>
        <div style="display: flex; gap: 6px;">
          <button onclick="copyToClipboard('{{ route('sitemap.news') }}')" class="btn-admin btn-admin-secondary" style="padding: 4px 10px; font-size: 11px;">
            <i class="fas fa-copy"></i> Copy
          </button>
          <a href="{{ route('sitemap.news') }}" target="_blank" class="btn-admin btn-admin-secondary" style="padding: 4px 10px; font-size: 11px;">
            <i class="fas fa-external-link-alt"></i> Open
          </a>
        </div>
      </div>

      <div style="display: flex; align-items: center; justify-content: space-between; background: rgba(255,255,255,0.03); padding: 8px 12px; border-radius: 6px; flex-wrap: wrap; gap: 10px;">
        <div>
          <span style="font-weight: 600; color: #cbd5e1; font-size: 13px;">Articles Only:</span>
          <code style="margin-left: 8px; color: #cbd5e1; font-size: 12px;">{{ route('sitemap.articles') }}</code>
        </div>
        <div style="display: flex; gap: 6px;">
          <button onclick="copyToClipboard('{{ route('sitemap.articles') }}')" class="btn-admin btn-admin-secondary" style="padding: 4px 10px; font-size: 11px;">
            <i class="fas fa-copy"></i> Copy
          </button>
          <a href="{{ route('sitemap.articles') }}" target="_blank" class="btn-admin btn-admin-secondary" style="padding: 4px 10px; font-size: 11px;">
            <i class="fas fa-external-link-alt"></i> Open
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- SEO Page Management -->
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

@section('scripts')
<script>
function copyToClipboard(text) {
  navigator.clipboard.writeText(text).then(function() {
    alert('Sitemap URL copied to clipboard: ' + text);
  }).catch(function(err) {
    prompt('Copy this sitemap URL:', text);
  });
}
</script>
@endsection
