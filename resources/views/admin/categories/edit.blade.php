@extends('layouts.admin')

@section('title', 'Edit Category')
@section('header_title', 'Edit Category')

@section('content')
<div class="admin-card">
  <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="form-row form-row-2">
      <div class="form-group">
        <label for="name_bn" class="form-label">Category Name (Bengali) <span style="color: var(--danger)">*</span></label>
        <input type="text" name="name_bn" id="name_bn" class="form-control" value="{{ old('name_bn', $category->name_bn) }}" required>
      </div>

      <div class="form-group">
        <label for="name_en" class="form-label">Category Name (English) <span style="color: var(--danger)">*</span></label>
        <input type="text" name="name_en" id="name_en" class="form-control" value="{{ old('name_en', $category->name_en) }}" required>
      </div>
    </div>

    <div class="form-group">
      <label for="display_order" class="form-label">Display Order <span style="color: var(--danger)">*</span></label>
      <input type="number" name="display_order" id="display_order" class="form-control" value="{{ old('display_order', $category->display_order) }}" required>
    </div>

    <div class="form-group">
      <label for="description" class="form-label">Description</label>
      <textarea name="description" id="description" class="form-control">{{ old('description', $category->description) }}</textarea>
    </div>

    <div style="margin-top: 24px; display: flex; gap: 12px; justify-content: flex-end;">
      <a href="{{ route('admin.categories.index') }}" class="btn-admin btn-admin-secondary">Cancel</a>
      <button type="submit" class="btn-admin btn-admin-primary">Update</button>
    </div>
  </form>
</div>
@endsection
