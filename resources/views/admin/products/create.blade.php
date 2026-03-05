@extends('admin.layouts.app')

@section('title','Thêm sản phẩm')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3>Thêm sản phẩm</h3>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
         Quay lại
    </a>
</div>
<form method="POST"
action="{{ route('admin.products.store') }}"
enctype="multipart/form-data">

@csrf

<label>Danh mục</label>
<select name="category_id" class="form-control">
@foreach($categories as $cat)
<option value="{{ $cat->id }}">{{ $cat->name }}</option>
@endforeach
</select>

<label>Thương hiệu</label>
<select name="brand_id" class="form-control">
@foreach($brands as $brand)
<option value="{{ $brand->id }}">{{ $brand->name }}</option>
@endforeach
</select>

<label>Tên</label>
<input type="text" name="name" class="form-control">


<label>Giá nhập</label>
<input type="number" name="import_price" class="form-control">

<label>Giá bán</label>
<input type="number" name="sell_price" class="form-control">

<label>Đơn vị</label>
<input type="text" name="unit" class="form-control">

<label>Số lượng</label>
<input type="number" name="quantity" class="form-control">

<label>Ảnh</label>
<input type="file" name="image" class="form-control">

<label>Mô tả</label>
<textarea name="description" class="form-control"></textarea>

<label>Trạng thái</label>
<select name="status" class="form-control">
<option value="1">Hiển thị</option>
<option value="0">Ẩn</option>
</select>

<br>

<button class="btn btn-success">Lưu</button>

</form>

@endsection