@extends('layouts.admin')

@section('title', 'Breaking Tickers')
@section('header_title', 'Breaking Tickers')


@section('content')


<div class="tickers-layout">
  <!-- Left Side: Add Ticker -->
  <div class="admin-card">
    <h2 class="card-title" style="margin-bottom: 20px;">Add New Ticker</h2>
    
    <form action="{{ route('admin.tickers.store') }}" method="POST">
      @csrf
      <div class="form-group">
        <label for="text_bn" class="form-label">Ticker Text (Bengali) <span style="color: var(--danger)">*</span></label>
        <input type="text" name="text_bn" id="text_bn" class="form-control" placeholder="e.g. পশ্চিমবঙ্গে তীব্র তাপপ্রবাহ..." required>
      </div>
      
      <div class="form-group">
        <label for="link_url" class="form-label">Link URL (Optional)</label>
        <input type="text" name="link_url" id="link_url" class="form-control" placeholder="e.g. /article/slug-name">
      </div>
      
      <button type="submit" class="btn-admin btn-admin-primary" style="width: 100%; justify-content: center;">
        <i class="fas fa-plus"></i> Add Ticker
      </button>
    </form>
  </div>

  <!-- Right Side: Tickers List -->
  <div class="admin-card">
    <h2 class="card-title" style="margin-bottom: 20px;">Breaking Tickers List</h2>
    
    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Ticker Text</th>
            <th>Link</th>
            <th>Status</th>
            <th style="text-align: right;">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($tickers as $ticker)
            <tr>
              <td style="font-weight: 600; color: #ffffff;">{{ $ticker->text_bn }}</td>
              <td><code>{{ $ticker->link_url ?? '-' }}</code></td>
              <td>
                @if($ticker->is_active)
                  <span class="badge badge-success">Active</span>
                @else
                  <span class="badge badge-danger">Inactive</span>
                @endif
              </td>
              <td style="text-align: right;">
                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                  <!-- Toggle status -->
                  <form action="{{ route('admin.tickers.update', $ticker->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn-admin btn-admin-secondary" style="padding: 6px 10px; font-size: 12px;">
                      {{ $ticker->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                  </form>
                  
                  <!-- Delete -->
                  <form action="{{ route('admin.tickers.destroy', $ticker->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ticker?');" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-admin btn-admin-danger" style="padding: 6px 10px; font-size: 12px;">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 20px;">No tickers found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
