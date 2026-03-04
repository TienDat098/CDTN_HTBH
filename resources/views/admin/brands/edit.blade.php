@extends('admin.layouts.app')

@section('title', 'Sửa thương hiệu')

@section('content')

<h3 class="mb-4">Sửa thương hiệu</h3>

<form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 shadow-sm">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Tên thương hiệu</label>
        <input type="text" name="name"
               class="form-control"
               value="{{ old('name', $brand->name) }}">
        @error('name')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Logo</label>
        <input type="file" name="logo" class="form-control">

        @if($brand->logo)
            <img src="{{ asset('storage/' . $brand->logo) }}"
                width="120"
                class="mt-2">
        @endif
    </div>

    <div class="mb-3">
        <label class="form-label">Mô tả</label>
        <textarea name="description"
                class="form-control">{{ old('description', $brand->description) }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Trạng thái</label>
        <select name="status" class="form-control">
            <option value="1" {{ $brand->status ? 'selected' : '' }}>
                Hiển thị
            </option>
            <option value="0" {{ !$brand->status ? 'selected' : '' }}>
                Ẩn
            </option>
        </select>
    </div>

    <button class="btn btn-primary">Cập nhật</button>
    <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">Quay lại</a>

</form>

@endsection