@extends('layouts.admin')

@section('title', 'Categories')
@section('header_title', 'Categories')

@section('content')


<div class="admin-card">
  <div class="card-header-flex">
    <h2 class="card-title">All Categories</h2>
    <a href="{{ route('admin.categories.create') }}" class="btn-admin btn-admin-primary">
      <i class="fas fa-plus"></i> Create New Category
    </a>
  </div>

  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Order</th>
          <th>Bengali Name</th>
          <th>English Name</th>
          <th>Slug</th>
          <th>Description</th>
          <th style="text-align: right;">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($categories as $cat)
          <tr>
            <td style="font-weight: 600;">{{ $cat->display_order }}</td>
            <td style="font-weight: 600; color: #ffffff;">{{ $cat->name_bn }}</td>
            <td>{{ $cat->name_en }}</td>
            <td><code>{{ $cat->slug }}</code></td>
            <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: var(--text-muted);">{{ $cat->description }}</td>
            <td style="text-align: right;">
              <div style="display: flex; gap: 8px; justify-content: flex-end;">
                <a href="{{ route('admin.categories.edit', $cat->id) }}" class="btn-admin btn-admin-secondary" style="padding: 6px 10px; font-size: 13px;">
                  <i class="fas fa-edit"></i> Edit
                </a>
                
                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Deleting this category will delete all articles under it! Are you sure?');" style="display: inline;">
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
            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">No categories found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
