@extends('admin.layouts.app')

@section('title','Sản phẩm')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3>Quản lý sản phẩm</h3>
    <a href="{{ route('admin.products.create') }}" class="btn btn-success">
        + Thêm sản phẩm
    </a>
</div>

    <table class="table table-bordered bg-white">

        <thead class="table-dark">
        <tr>
        <th>ID</th>
        <th>Barcode</th>
        <th>Ảnh</th>
        <th>Tên</th>
        <th>Danh mục</th>
        <th>Thương hiệu</th>
        <th>Giá bán</th>
        <th>Tồn kho</th>
        <th>Hành động</th>
        </tr>
        </thead>

    <tbody>

    @foreach($products as $product)

    <tr>

    <td>{{ $product->id }}</td>
    <td>{{ $product->barcode }}</td>
    <td>
    <img src="{{ $product->thumbnail }}" width="60" class="img-thumbnail" alt="Ảnh SP">
    </td>

    <td>{{ $product->name }}</td>

    <td>{{ $product->category->name }}</td>

    <td>{{ $product->brand->name }}</td>

    <td>{{ number_format($product->sell_price) }}</td>

    <td>{{ $product->stock->quantity ?? 0 }}</td>

    <td>

    <a href="{{ route('admin.products.edit',$product) }}"
    class="btn btn-warning btn-sm">Sửa</a>

    <form action="{{ route('admin.products.destroy',$product) }}"
    method="POST"
    class="d-inline">

    @csrf
    @method('DELETE')

    <button class="btn btn-danger btn-sm"
    onclick="return confirm('Xóa sản phẩm?')">

    Xóa

    </button>

    </form>

    </td>

    </tr>

    @endforeach

    </tbody>

    </table>

    {{ $products->links('pagination::bootstrap-5') }}

    @endsection