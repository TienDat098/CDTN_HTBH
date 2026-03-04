@extends('admin.layouts.app')

@section('title', 'Thêm thương hiệu')

@section('content')

<h3 class="mb-4">Thêm thương hiệu</h3>

<form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 shadow-sm">
    @csrf

    <div class="mb-3">
        <label class="form-label">Tên thương hiệu</label>
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
    <div class="mb-3">
        <label class="form-label">Logo</label>
        <input type="file" name="logo" class="form-control">
        @error('logo')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" 
            class="form-control">{{ old('description') }}</textarea>
    </div>

    <button class="btn btn-success">Lưu</button>
    <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">Quay lại</a>
</form>

@endsection