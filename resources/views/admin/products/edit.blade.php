@extends('admin.layouts.app')

@section('title', 'Sửa sản phẩm')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3>Sửa sản phẩm</h3>
    <a href="{{ route('admin.products.index', ['page' => request()->page]) }}" class="btn btn-secondary">
    Quay lại
</a>
</div>

<form action="{{ route('admin.products.update', ['product' => $product, 'page' => request()->page]) }}" 
      method="POST" 
      enctype="multipart/form-data"
      class="bg-white p-4 shadow-sm">

    @csrf
    @method('PUT')

    <div class="row">

        <!-- Tên sản phẩm -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Tên sản phẩm</label>
            <input type="text" 
                   name="name" 
                   class="form-control"
                   value="{{ old('name', $product->name) }}">
        </div>

        <!-- Barcode -->
        <div class="col-md-6 mb-3">
            <label>Barcode</label>
            <input type="text" class="form-control" value="{{ $product->barcode }}" readonly>
        </div>

        <!-- Category -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Danh mục</label>
            <select name="category_id" class="form-control">

                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach

            </select>
        </div>

        <!-- Brand -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Thương hiệu</label>
            <select name="brand_id" class="form-control">

                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}"
                        {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach

            </select>
        </div>

        <!-- Giá nhập -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Giá nhập</label>
            <input type="number"
                   name="import_price"
                   class="form-control"
                   value="{{ old('import_price', $product->import_price) }}">
        </div>

        <!-- Giá bán -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Giá bán</label>
            <input type="number"
                   name="sell_price"
                   class="form-control"
                   value="{{ old('sell_price', $product->sell_price) }}">
        </div>

        <!-- Đơn vị -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Đơn vị</label>
            <input type="text"
                   name="unit"
                   class="form-control"
                   value="{{ old('unit', $product->unit) }}">
        </div>

        <!-- Số lượng -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Số lượng tồn</label>
            <input type="number"
                   name="quantity"
                   class="form-control"
                   value="{{ old('quantity', $product->stock->quantity ?? 0) }}">
        </div>

        <!-- Ảnh -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Ảnh sản phẩm</label>
                <div class="mb-2">
                    <img src="{{ $product->thumbnail }}" width="80" class="img-thumbnail" alt="Ảnh SP">
                </div>

            <input type="file" name="image" class="form-control">
        </div>

        <!-- Trạng thái -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Trạng thái</label>

            <select name="status" class="form-control">
                <option value="1" {{ $product->status ? 'selected' : '' }}>
                    Hiển thị
                </option>

                <option value="0" {{ !$product->status ? 'selected' : '' }}>
                    Ẩn
                </option>
            </select>

        </div>

        <!-- Mô tả -->
        <div class="col-md-12 mb-3">
            <label class="form-label">Mô tả</label>

            <textarea name="description"
                      class="form-control"
                      rows="4">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="card border-warning mb-4">
                <div class="card-header bg-warning text-dark fw-bold">
                    <i class="bi bi-tags"></i> Phân loại / Biến thể hiện tại
                </div>
                <div class="card-body bg-light">
                    <div id="variants-container">
                        @php $vIndex = 0; @endphp
                        @foreach($product->variants->where('status', 1) as $variant)
                        <div class="row align-items-end mb-3 pb-3 border-bottom variant-row" id="variant-{{ $vIndex }}">
                            <input type="hidden" name="variants[{{ $vIndex }}][id]" value="{{ $variant->id }}">
                            
                            <div class="col-md-3">
                                <label class="form-label small">Tên phân loại</label>
                                <input type="text" name="variants[{{ $vIndex }}][name]" class="form-control" value="{{ $variant->name }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Mã vạch</label>
                                <input type="text" name="variants[{{ $vIndex }}][barcode]" class="form-control" value="{{ $variant->barcode }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">Giá bán</label>
                                <input type="number" name="variants[{{ $vIndex }}][price]" class="form-control" value="{{ intval($variant->price) }}" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">Tồn kho</label>
                                <input type="number" name="variants[{{ $vIndex }}][stock_quantity]" class="form-control" value="{{ $variant->stock_quantity }}">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger w-100" onclick="removeVariant({{ $vIndex }})">Xóa</button>
                            </div>
                        </div>
                        @php $vIndex++; @endphp
                        @endforeach
                    </div>
                    
                    <button type="button" class="btn btn-outline-warning text-dark btn-sm mt-2 fw-bold" onclick="addVariant()">
                        <i class="bi bi-plus-circle"></i> Thêm phân loại mới
                    </button>
                </div>
        </div>

    </div>

    <button class="btn btn-success">
        Cập nhật thay đổi
    </button>

</form>
<script>
    // Tiếp tục biến đếm từ số lượng đã có
    let variantIndex = {{ $vIndex }}; 
    
    function addVariant() {
        const html = `
        <div class="row align-items-end mb-3 pb-3 border-bottom variant-row" id="variant-${variantIndex}">
            <div class="col-md-3">
                <label class="form-label small">Tên (VD: Thùng 24 Gói)</label>
                <input type="text" name="variants[${variantIndex}][name]" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Mã vạch riêng</label>
                <input type="text" name="variants[${variantIndex}][barcode]" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Giá bán</label>
                <input type="number" name="variants[${variantIndex}][price]" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Tồn kho</label>
                <input type="number" name="variants[${variantIndex}][stock_quantity]" class="form-control" value="0">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100" onclick="removeVariant(${variantIndex})">
                    <i class="bi bi-trash"></i> Hủy
                </button>
            </div>
        </div>`;
        document.getElementById('variants-container').insertAdjacentHTML('beforeend', html);
        variantIndex++;
    }

    function removeVariant(index) {
        document.getElementById(`variant-${index}`).remove();
    }
</script>
@endsection