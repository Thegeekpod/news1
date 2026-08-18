@extends('layouts.admin')

@section('title', 'Edit Article')
@section('header_title', 'Edit Article')

@section('content')
<div class="admin-card">
  <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <!-- Title & Slug -->
    <div class="form-group">
      <label for="title" class="form-label">Article Title <span style="color: var(--danger)">*</span></label>
      <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $article->title) }}" required>
    </div>

    <div class="form-group">
      <label for="slug" class="form-label">Slug (Optional)</label>
      <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $article->slug) }}">
    </div>

    <!-- Category & Thumbnail -->
    <div class="form-row form-row-2">
      <div class="form-group">
        <label for="category_id" class="form-label">Select Category <span style="color: var(--danger)">*</span></label>
        <select name="category_id" id="category_id" class="form-control" required>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ old('category_id', $article->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name_bn }} ({{ $cat->name_en }})</option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label for="thumbnail" class="form-label">Thumbnail Image (select file to change)</label>
        @if($article->thumbnail_url)
          <div style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
            <img src="{{ $article->thumbnail_url }}" alt="Current Thumbnail" style="width: 80px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-color);">
            <span style="font-size: 12px; color: var(--text-muted);">Previous Thumbnail</span>
          </div>
        @endif
        <input type="file" name="thumbnail" id="thumbnail" class="form-control" style="padding: 8px 12px;">
      </div>
    </div>

    <!-- Excerpt -->
    <div class="form-group">
      <label for="excerpt" class="form-label">Short Description (Excerpt - Optional)</label>
      <textarea name="excerpt" id="excerpt" class="form-control">{{ old('excerpt', $article->excerpt) }}</textarea>
    </div>

    <!-- Content -->
    <div class="form-group">
      <label for="content" class="form-label">Main News Content (HTML supported) <span style="color: var(--danger)">*</span></label>
      <textarea name="content" id="content" class="form-control" style="min-height: 250px;">{{ old('content', $article->content) }}</textarea>
    </div>

    <!-- Flags Checkboxes -->
    <div class="form-group">
      <label class="form-label">Article Placement Settings (নিবন্ধ স্থাপনের সেটিংস)</label>
      <div style="display: flex; flex-wrap: wrap; gap: 24px; margin-top: 10px;">
        <label class="form-check">
          <input type="checkbox" name="is_breaking" value="1" {{ old('is_breaking', $article->is_breaking) ? 'checked' : '' }}>
          <span>Show in Breaking Ticker (ব্রেকিং নিউজ টিকারে দেখান)</span>
        </label>
        
        <label class="form-check">
          <input type="checkbox" name="is_lead" value="1" {{ old('is_lead', $article->is_lead) ? 'checked' : '' }}>
          <span>Show as Lead News (হোমপেজে ভিডিওর পাশে প্রধান সংবাদে দেখান)</span>
        </label>

        <label class="form-check">
          <input type="checkbox" name="is_popular" value="1" {{ old('is_popular', $article->is_popular) ? 'checked' : '' }}>
          <span>Show in Popular (সাইডবারে সবচেয়ে জনপ্রিয় সংবাদে দেখান)</span>
        </label>
      </div>
    </div>

    <!-- Tags selection -->
    <div class="form-group" style="border-top: 1px solid var(--border-color); padding-top: 20px; margin-top: 20px;">
      <label class="form-label">Select Tags (ট্যাগ নির্বাচন করুন)</label>
      @php
          $articleTagIds = $article->tags->pluck('id')->toArray();
      @endphp
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 12px; margin-top: 10px;">
        @foreach($tags as $tag)
          <label class="form-check" style="background: rgba(255,255,255,0.02); padding: 8px; border-radius: 6px; border: 1px solid var(--border-color);">
            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $articleTagIds)) ? 'checked' : '' }}>
            <span style="font-size: 13px;">#{{ $tag->name_bn }}</span>
          </label>
        @endforeach
      </div>
    </div>

    <!-- Status & Published At -->
    <div class="form-row form-row-2" style="border-top: 1px solid var(--border-color); padding-top: 20px; margin-top: 20px;">
      <div class="form-group">
        <label for="status" class="form-label">Status <span style="color: var(--danger)">*</span></label>
        <select name="status" id="status" class="form-control" required>
          <option value="draft" {{ old('status', $article->status) === 'draft' ? 'selected' : '' }}>Draft</option>
          <option value="published" {{ old('status', $article->status) === 'published' ? 'selected' : '' }}>Published</option>
        </select>
      </div>

      <div class="form-group">
        <label for="published_at" class="form-label">Publish Date/Time</label>
        <input type="datetime-local" name="published_at" id="published_at" class="form-control" value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}">
      </div>
    </div>

    <!-- SEO Options -->
    <div style="border-top: 1px solid var(--border-color); padding-top: 20px; margin-top: 20px; background: rgba(0,0,0,0.1); padding: 20px; border-radius: 8px;">
      <h3 class="card-title" style="font-size: 15px; margin-bottom: 16px;"><i class="fas fa-search-plus"></i> Search Engine Optimization (SEO Settings - Optional)</h3>
      
      <div class="form-group">
        <label for="meta_title" class="form-label">Meta Title</label>
        <input type="text" name="meta_title" id="meta_title" class="form-control" value="{{ old('meta_title', $article->meta_title) }}">
      </div>
      
      <div class="form-group">
        <label for="meta_description" class="form-label">Meta Description</label>
        <textarea name="meta_description" id="meta_description" class="form-control">{{ old('meta_description', $article->meta_description) }}</textarea>
      </div>
      
      <div class="form-group">
        <label for="keywords" class="form-label">Keywords (comma separated)</label>
        <input type="text" name="keywords" id="keywords" class="form-control" value="{{ old('keywords', $article->keywords) }}">
      </div>
    </div>

    <!-- Form Submit -->
    <div style="margin-top: 24px; display: flex; gap: 12px; justify-content: flex-end;">
      <a href="{{ route('admin.articles.index') }}" class="btn-admin btn-admin-secondary">Cancel</a>
      <button type="submit" class="btn-admin btn-admin-primary">Update</button>
    </div>
  </form>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    if (window.tinymce) {
      tinymce.init({
        selector: '#content',
        height: 400,
        menubar: false,
        plugins: [
          'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
          'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
          'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | bold italic blockquote | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat | code fullscreen | help',
        toolbar_mode: 'sliding',
        skin: 'oxide-dark',
        content_css: 'dark',
        branding: false,
        promotion: false
      });
    }
  });
</script>
@endsection
