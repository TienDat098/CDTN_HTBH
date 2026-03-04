@extends('admin.layouts.app')

@section('title', 'Sửa danh mục')

@section('content')

<h3 class="mb-4">Sửa danh mục</h3>

<form action="{{ route('admin.categories.update', $category) }}" method="POST" class="bg-white p-4 shadow-sm">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Tên danh mục</label>
        <input type="text" name="name" class="form-control"
               value="{{ old('name', $category->name) }}">
        @error('name')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Trạng thái</label>
        <select name="status" class="form-control">
            <option value="1" {{ $category->status ? 'selected' : '' }}>
                Hiển thị
            </option>
            <option value="0" {{ !$category->status ? 'selected' : '' }}>
                Ẩn
            </option>
        </select>
    </div>

    <button class="btn btn-primary">Cập nhật</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại</a>

</form>

@endsection