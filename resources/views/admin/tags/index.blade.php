@extends('layouts.admin')

@section('title', 'Tags')
@section('header_title', 'Tags Manager')

@section('content')
<div class="tag-layout" style="display: grid; grid-template-columns: 320px 1fr; gap: 24px;">
  <!-- Left Side: Quick Add Tag -->
  <div class="admin-card">
    <h2 class="card-title" style="margin-bottom: 20px;">Add New Tag</h2>
    
    <form action="{{ route('admin.tags.store') }}" method="POST">
      @csrf
      <div class="form-group">
        <label for="name_bn" class="form-label">Tag Name (Bengali) <span style="color: var(--danger)">*</span></label>
        <input type="text" name="name_bn" id="name_bn" class="form-control" placeholder="e.g. বাজেট২০২৬, নির্বাচন" required>
      </div>

      <button type="submit" class="btn-admin btn-admin-primary" style="width: 100%; justify-content: center; margin-top: 10px;">
        <i class="fas fa-plus"></i> Add Tag
      </button>
    </form>
  </div>

  <!-- Right Side: Tag Cloud / List -->
  <div class="admin-card">
    <h2 class="card-title">Existing Tags</h2>
    <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px; margin-bottom: 16px;">The number next to each tag indicates the count of associated articles.</p>

    <div class="tag-pill-container" style="display: flex; flex-wrap: wrap; gap: 10px;">
      @forelse($tags as $tag)
        <div class="tag-badge-item" style="background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); padding: 6px 12px; border-radius: 20px; display: flex; align-items: center; gap: 8px;">
          <span style="font-weight: 500; color: #ffffff;">#{{ $tag->name_bn }}</span>
          <span class="badge badge-primary" style="font-size: 11px;">{{ $tag->articles_count }}</span>
          
          <form action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this tag?');" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" style="background: none; border: none; color: var(--danger); cursor: pointer; padding: 2px 4px; font-size: 12px;" title="Delete">
              <i class="fas fa-times"></i>
            </button>
          </form>
        </div>
      @empty
        <div style="color: var(--text-muted); font-size: 14px; padding: 20px 0;">No tags found. Create one using the form on the left.</div>
      @endforelse
    </div>
  </div>
</div>
@endsection
