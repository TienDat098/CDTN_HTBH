@extends('admin.layouts.app')

@section('title', 'Thương hiệu')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3>Quản lý Thương hiệu</h3>
    <a href="{{ route('admin.brands.create') }}" class="btn btn-success">
        + Thêm thương hiệu
    </a>
</div>

<table class="table table-bordered bg-white shadow-sm">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Slug</th>
            <th>Logo</th>
            <th>Trạng thái</th>
            <th width="150">Hành động</th>
        </tr>
    </thead>
    <tbody>
        @forelse($brands as $brand)
            <tr>
                <td>{{ $brand->id }}</td>
                <td>{{ $brand->name }}</td>
                <td>{{ $brand->slug }}</td>

                <td>
                    @if($brand->logo)
                        <img src="{{ asset('storage/' . $brand->logo) }}" width="60">
                    @else
                        <span class="text-muted">Không có</span>
                    @endif
                </td>

                <td>
                    @if($brand->status)
                        <span class="badge bg-success">Hiển thị</span>
                    @else
                        <span class="badge bg-secondary">Ẩn</span>
                    @endif
                </td>

                <td>
                    <a href="{{ route('admin.brands.edit', $brand) }}"
                    class="btn btn-sm btn-warning">Sửa</a>

                    <form action="{{ route('admin.brands.destroy', $brand) }}"
                        method="POST"
                        class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Xóa thương hiệu này?')">
                            Xóa
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Chưa có dữ liệu</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $brands->links('pagination::bootstrap-5') }}

@endsection