@extends('layouts.admin')

@section('title', 'Newsletter Subscribers')
@section('header_title', 'Newsletter Subscribers List')

@section('content')
<div class="admin-card">
  <div class="card-header-flex" style="margin-bottom: 20px;">
    <h2 class="card-title">Subscribed Users</h2>
    <span class="badge badge-primary">Total: {{ $subscribers->total() }}</span>
  </div>

  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Subscriber Email</th>
          <th>Subscribed Date</th>
        </tr>
      </thead>
      <tbody>
        @forelse($subscribers as $index => $sub)
          <tr>
            <td>{{ $subscribers->firstItem() + $index }}</td>
            <td style="font-weight: 600; color: #ffffff;">{{ $sub->email }}</td>
            <td style="color: var(--text-muted);">{{ $sub->created_at->format('M d, Y h:i A') }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="3" style="text-align: center; color: var(--text-muted); padding: 24px;">No newsletter subscribers found yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div style="margin-top: 20px;">
    {{ $subscribers->links() }}
  </div>
</div>
@endsection
