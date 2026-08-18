@extends('layouts.admin')

@section('title', 'Video Gallery')
@section('header_title', 'Video Gallery')


@section('content')


<div class="videos-layout">
  <!-- Left Side: Add Video -->
  <div class="admin-card">
    <h2 class="card-title" style="margin-bottom: 20px;">Add New Video</h2>
    
    <form action="{{ route('admin.videos.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="form-group">
        <label for="title_bn" class="form-label">Video Title (Bengali) <span style="color: var(--danger)">*</span></label>
        <input type="text" name="title_bn" id="title_bn" class="form-control" placeholder="e.g. আইপিএল ফাইনাল হাইলাইটস..." required>
      </div>
      
      <div class="form-group">
        <label for="youtube_url" class="form-label">YouTube URL <span style="color: var(--danger)">*</span></label>
        <input type="url" name="youtube_url" id="youtube_url" class="form-control" placeholder="e.g. https://www.youtube.com/watch?v=..." required>
      </div>
      
      <div class="form-row form-row-2">
        <div class="form-group">
          <label for="duration" class="form-label">Video Duration</label>
          <input type="text" name="duration" id="duration" class="form-control" placeholder="e.g. 3:24">
        </div>
        
        <div class="form-group">
          <label for="thumbnail_url" class="form-label">Thumbnail URL (Optional)</label>
          <input type="url" name="thumbnail_url" id="thumbnail_url" class="form-control" placeholder="e.g. https://img.youtube.com/...">
        </div>
      </div>

      <div class="form-group">
        <label for="thumbnail_file" class="form-label">Or Upload Custom Thumbnail (Optional)</label>
        <input type="file" name="thumbnail_file" id="thumbnail_file" class="form-control" style="padding: 6px 10px; font-size: 13px;">
        <small style="color: var(--text-muted); font-size: 11px; margin-top: 4px; display: block;">Leave both empty to auto-fetch the thumbnail from YouTube.</small>
      </div>
      
      <button type="submit" class="btn-admin btn-admin-primary" style="width: 100%; justify-content: center;">
        <i class="fas fa-plus"></i> Add Video
      </button>
    </form>
  </div>

  <!-- Right Side: Videos List -->
  <div class="admin-card">
    <h2 class="card-title" style="margin-bottom: 20px;">Video News List</h2>
    
    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Thumbnail</th>
            <th>Video Title</th>
            <th>Duration</th>
            <th>Link</th>
            <th style="text-align: right;">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($videos as $video)
            <tr>
              <td>
                <img src="{{ $video->thumbnail_url }}" alt="Preview" class="video-preview-thumb">
              </td>
              <td style="font-weight: 600; color: #ffffff; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                {{ $video->title_bn }}
              </td>
              <td><span class="badge badge-primary">{{ $video->duration }}</span></td>
              <td><a href="{{ $video->youtube_url }}" target="_blank" style="color: #38bdf8; text-decoration: none;">Watch on YouTube <i class="fas fa-external-link-alt" style="font-size: 10px;"></i></a></td>
              <td style="text-align: right;">
                <form action="{{ route('admin.videos.destroy', $video->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this video?');" style="display: inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-admin btn-admin-danger" style="padding: 6px 10px; font-size: 12px;">
                    <i class="fas fa-trash"></i> Delete
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 20px;">No videos found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
