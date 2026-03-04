@extends('admin.layouts.app')

@section('title', 'Thêm danh mục')

@section('content')

<h3 class="mb-4">Thêm danh mục</h3>

<form action="{{ route('admin.categories.store') }}" method="POST" class="bg-white p-4 shadow-sm">
    @csrf

    <div class="mb-3">
        <label class="form-label">Tên danh mục</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}">
        @error('name')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Trạng thái</label>
        <select name="status" class="form-control">
            <option value="1">Hiển thị</option>
            <option value="0">Ẩn</option>
        </select>
    </div>

    <button class="btn btn-success">Lưu</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại</a>

</form>

@endsection