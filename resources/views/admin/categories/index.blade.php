@extends('admin.layouts.app')

@section('title', 'Danh mục')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3>Quản lý Danh mục</h3>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
        + Thêm danh mục
    </a>
</div>

<table class="table table-bordered bg-white shadow-sm">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Ảnh</th>
            <th>Tên danh mục</th>
            <th>Slug</th>
            <th>Trạng thái</th>
            <th width="150">Hành động</th>
        </tr>
    </thead>
    <tbody>
        @forelse($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>

                 <td>
                    <img 
                        src="{{ $category->image 
                            ? asset('images/categories/'.$category->image) 
                            : asset('images/no-image.png') }}" 
                        width="60" 
                        height="60"
                        style="object-fit:cover; border-radius:6px;">
                </td>

                <td>{{ $category->name }}</td>
                <td>{{ $category->slug }}</td>
                <td>
                @if($category->status)
                    <span class="badge bg-success">Hiển thị</span>
                @else
                    <span class="badge bg-secondary">Ẩn</span>
                @endif
                </td>
                <td>
                    <a href="{{ route('admin.categories.edit', $category) }}" 
                       class="btn btn-sm btn-warning">Sửa</a>

                    <form action="{{ route('admin.categories.destroy', $category) }}" 
                          method="POST" 
                          class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Xóa danh mục này?')">
                            Xóa
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">Chưa có dữ liệu</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $categories->links('pagination::bootstrap-5') }}

@endsection