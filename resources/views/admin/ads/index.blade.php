@extends('layouts.admin')

@section('title', 'Advertisements')
@section('header_title', 'Advertisements Manager')

@section('content')
<div style="display: grid; grid-template-columns: 360px 1fr; gap: 24px;">
  <!-- Add Ad Form -->
  <div class="admin-card">
    <h2 class="card-title" style="margin-bottom: 20px;">Add New Advertisement</h2>

    <form action="{{ route('admin.ads.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="form-group">
        <label for="title" class="form-label">Ad Title <span style="color: var(--danger)">*</span></label>
        <input type="text" name="title" id="title" class="form-control" placeholder="e.g. Summer Sale Banner" required>
      </div>

      <div class="form-group">
        <label for="slot_name" class="form-label">Ad Slot Position <span style="color: var(--danger)">*</span></label>
        <select name="slot_name" id="slot_name" class="form-control" required>
          <option value="category_banner_top">Category Page - Top (বিভাগের খবর যেখানে শুরু হচ্ছে)</option>
          <option value="category_banner_bottom">Category Page - Bottom (বিভাগের খবর যেখানে শেষ হচ্ছে)</option>
          <option value="home_banner_top">Home - Above State & National News (রাজ্য ও দেশের খবরের উপরে)</option>
          <option value="tech_banner">Home - Tech & Innovation Section (টেকনোলজি ও গেজেট সংবাদের নিচে)</option>
          <option value="sidebar_banner">Sidebar - Above Popular Topics (জনপ্রিয় বিষয়বস্তুর উপরে)</option>
          <option value="in_article_banner">In-Article Content (সংবাদের ২ লাইন/প্যারাগ্রাফ পরে)</option>
          <option value="above_related_banner">Above Tags (সম্পর্কিত ট্যাগের উপরে)</option>
          <option value="header_banner">Header Banner (Top Page)</option>
          <option value="footer_banner">Footer Banner (Bottom Page)</option>
        </select>
      </div>

      <div class="form-group">
        <label for="ad_image" class="form-label">Ad Image <span style="color: var(--danger)">*</span></label>
        <input type="file" name="ad_image" id="ad_image" class="form-control" required style="padding: 8px 12px;">
      </div>

      <div class="form-group">
        <label for="destination_url" class="form-label">Target URL (Link)</label>
        <input type="url" name="destination_url" id="destination_url" class="form-control" placeholder="https://example.com">
      </div>

      <div class="form-group">
        <label class="form-check">
          <input type="checkbox" name="is_active" value="1" checked>
          <span>Ad Active Status (সক্রিয় রাখুন)</span>
        </label>
      </div>

      <button type="submit" class="btn-admin btn-admin-primary" style="width: 100%; justify-content: center; margin-top: 10px;">
        <i class="fas fa-plus"></i> Save Advertisement
      </button>
    </form>
  </div>

  <!-- Ads List Table -->
  <div class="admin-card">
    <h2 class="card-title" style="margin-bottom: 16px;">Active & Managed Ads</h2>

    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Preview</th>
            <th>Title & Slot</th>
            <th>Target URL</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($ads as $ad)
            <tr>
              <td style="width: 100px;">
                <img src="{{ asset($ad->image_url) }}" alt="{{ $ad->title }}" style="width: 90px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-color);">
              </td>
              <td>
                <div style="font-weight: 600; color: #ffffff;">{{ $ad->title }}</div>
                <span class="badge badge-primary" style="font-size: 11px; margin-top: 4px;">{{ $ad->slot_name }}</span>
              </td>
              <td style="font-size: 13px; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                <a href="{{ $ad->destination_url }}" target="_blank" style="color: var(--primary);">{{ $ad->destination_url ?? '#' }}</a>
              </td>
              <td>
                @if($ad->is_active)
                  <span class="badge badge-success">Active</span>
                @else
                  <span class="badge badge-danger">Disabled</span>
                @endif
              </td>
              <td>
                <form action="{{ route('admin.ads.destroy', $ad->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ad?');" style="display: inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-admin btn-admin-danger btn-sm">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 24px;">No advertisements found. Add one using the form.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
