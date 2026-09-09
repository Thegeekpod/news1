@extends('layouts.admin')

@section('title', 'Edit SEO Details')
@section('header_title', 'SEO Details')

@section('content')

@if(session('error'))
  <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #ef4444; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 500;">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
  </div>
@endif

@if($errors->any())
  <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #ef4444; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
    <ul style="margin: 0; padding-left: 20px;">
      @foreach($errors->all() as $err)
        <li>{{ $err }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="admin-card" style="padding: 0; overflow: hidden; border: 1px solid var(--border-color); border-radius: 8px; box-shadow: 0 4px 16px rgba(0,0,0,0.1);">
  <!-- Blue Banner Header matching user design -->
  <div style="background: #1877f2; color: #ffffff; padding: 14px 20px; font-weight: 700; font-size: 1.1rem; border-top-left-radius: 8px; border-top-right-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
    <span>SEO Details ({{ $seo->page_url }})</span>
    <a href="{{ url($seo->page_url) }}" target="_blank" style="color: #ffffff; font-size: 0.85rem; text-decoration: underline; font-weight: 500;">
      <i class="fas fa-external-link-alt"></i> View Page
    </a>
  </div>

  <form action="{{ route('admin.seo.update', $seo->id) }}" method="POST" style="padding: 24px;">
    @csrf
    @method('PUT')

    <!-- Page URL Field -->
    <div class="form-group" style="margin-bottom: 22px;">
      <label for="page_url" class="form-label" style="font-weight: 700; display: block; margin-bottom: 6px;">
        Page URL <span style="color: #ef4444;">*</span>
      </label>
      <input type="text" name="page_url" id="page_url" class="form-control" placeholder="e.g. /about or / or /category/sports" value="{{ old('page_url', $seo->page_url) }}" required
        style="width: 100%; padding: 10px 14px; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-surface-subtle); color: var(--text-primary); font-size: 0.95rem;">
      <small style="display: block; margin-top: 5px; color: var(--text-muted); font-size: 0.82rem;">
        Relative URL starting with / (Use <code>/</code> for Home, <code>/category/slug</code> for Category page)
      </small>
    </div>

    <!-- Meta Title Field -->
    <div class="form-group" style="margin-bottom: 22px;">
      <label for="meta_title" class="form-label" style="font-weight: 700; display: block; margin-bottom: 6px;">
        Meta Title
      </label>
      <input type="text" name="meta_title" id="meta_title" class="form-control" placeholder="Enter meta title for search engines..." value="{{ old('meta_title', $seo->meta_title) }}"
        style="width: 100%; padding: 10px 14px; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-surface-subtle); color: var(--text-primary); font-size: 0.95rem;">
    </div>

    <!-- Meta Description Field -->
    <div class="form-group" style="margin-bottom: 22px;">
      <label for="meta_description" class="form-label" style="font-weight: 700; display: block; margin-bottom: 6px;">
        Meta Description
      </label>
      <textarea name="meta_description" id="meta_description" class="form-control" rows="4" placeholder="Enter meta description for search engines..."
        style="width: 100%; padding: 10px 14px; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-surface-subtle); color: var(--text-primary); font-size: 0.95rem; font-family: inherit;">{{ old('meta_description', $seo->meta_description) }}</textarea>
    </div>

    <!-- Other Scripts / Tags Field -->
    <div class="form-group" style="margin-bottom: 26px;">
      <label for="other_tags" class="form-label" style="font-weight: 700; display: block; margin-bottom: 6px;">
        Other Scripts / Tags
      </label>
      <textarea name="other_tags" id="other_tags" class="form-control" rows="5" placeholder="<script>...</script> or <meta ...>"
        style="width: 100%; padding: 10px 14px; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-surface-subtle); color: var(--text-primary); font-size: 0.9rem; font-family: monospace;">{{ old('other_tags', $seo->other_tags) }}</textarea>
      <small style="display: block; margin-top: 5px; color: var(--text-muted); font-size: 0.82rem;">
        Raw HTML to be injected into the head section. Be careful with this field.
      </small>
    </div>

    <div style="display: flex; gap: 12px; align-items: center;">
      <button type="submit" class="btn-admin btn-admin-primary" style="padding: 10px 24px; font-weight: 700;">
        <i class="fas fa-save"></i> Update SEO Details
      </button>
      <a href="{{ route('admin.seo.index') }}" class="btn-admin btn-admin-secondary" style="padding: 10px 20px;">
        Cancel
      </a>
    </div>
  </form>
</div>

@endsection
