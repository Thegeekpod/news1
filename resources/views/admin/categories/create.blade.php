@extends('layouts.admin')

@section('title', 'Create Category')
@section('header_title', 'Create New Category')

@section('content')
<div class="admin-card">
  <form action="{{ route('admin.categories.store') }}" method="POST">
    @csrf
    
    <div class="form-row form-row-2">
      <div class="form-group">
        <label for="name_bn" class="form-label">Category Name (Bengali) <span style="color: var(--danger)">*</span></label>
        <input type="text" name="name_bn" id="name_bn" class="form-control" placeholder="e.g., রাজনীতি" value="{{ old('name_bn') }}" required>
      </div>

      <div class="form-group">
        <label for="name_en" class="form-label">Category Name (English) <span style="color: var(--danger)">*</span></label>
        <input type="text" name="name_en" id="name_en" class="form-control" placeholder="e.g., Politics (helps generate slug)" value="{{ old('name_en') }}" required>
      </div>
    </div>

    <div class="form-group">
      <label for="display_order" class="form-label">Display Order <span style="color: var(--danger)">*</span></label>
      <input type="number" name="display_order" id="display_order" class="form-control" placeholder="e.g., 1, 2, 3" value="{{ old('display_order', 0) }}" required>
    </div>

    <div class="form-group">
      <label for="description" class="form-label">Description</label>
      <textarea name="description" id="description" class="form-control" placeholder="Short description of the category">{{ old('description') }}</textarea>
    </div>

    <div style="margin-top: 24px; display: flex; gap: 12px; justify-content: flex-end;">
      <a href="{{ route('admin.categories.index') }}" class="btn-admin btn-admin-secondary">Cancel</a>
      <button type="submit" class="btn-admin btn-admin-primary">Save Category</button>
    </div>
  </form>
</div>
@endsection
