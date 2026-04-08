@extends('admin.layouts.app')

@section('title','Quản lý Sản phẩm')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0 d-flex align-items-center">
        <i class="bi bi-box-seam me-2"></i> Quản lý sản phẩm
        <span class="badge bg-secondary fs-6 ms-3 fw-medium" style="padding: 6px 12px;">Tổng: {{ $products->total() }} sản phẩm</span>
    </h3>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary fw-bold">
        <i class="bi bi-plus-circle me-1"></i> Thêm sản phẩm
    </a>
</div>

<div class="card shadow-sm border-0 mb-4 bg-light">
    <div class="card-body p-3">
        <form action="{{ route('admin.products.index') }}" method="GET" class="row gx-2 gy-2 align-items-center">
            
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="keyword" class="form-control border-start-0 ps-0" placeholder="Tên sản phẩm/Barcode..." value="{{ request('keyword') }}">
                </div>
            </div>

            <div class="col-md-3">
                <select name="category_id" class="form-select">
                    <option value="">-- Tất cả danh mục --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select name="sort" class="form-select">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần (Thấp -> Cao)</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần (Cao -> Thấp)</option>
                </select>
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1 fw-bold"><i class="bi bi-funnel-fill me-1"></i> Lọc</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary px-3" title="Làm mới"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>

        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">ID</th>
                        <th>Ảnh</th>
                        <th>Tên SP / Barcode</th>
                        <th>Danh mục</th>
                        <th>Thương hiệu</th>
                        <th>Giá bán</th>
                        <th>Tồn kho</th>
                        <th class="text-center pe-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="fw-bold ps-3">{{ $product->id }}</td>
                        
                        <td>
                            @if($product->thumbnail)
                                <img src="{{ $product->thumbnail }}" width="50" height="50" class="rounded object-fit-cover shadow-sm" alt="Ảnh SP">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted shadow-sm" style="width: 50px; height: 50px;">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                        </td>

                        <td>
                            <div class="fw-bold text-dark">{{ $product->name }}</div>
                            <div class="small text-muted"><i class="bi bi-upc-scan me-1"></i>{{ $product->barcode }}</div>
                        </td>

                        <td><span class="badge bg-secondary bg-opacity-10 text-dark border">{{ $product->category->name ?? '---' }}</span></td>

                        <td>{{ $product->brand->name ?? '---' }}</td>

                        <td class="fw-bold text-danger">{{ number_format($product->sell_price) }}đ</td>

                        <td>
                            @php $stock = $product->stock->quantity ?? 0; @endphp
                            @if($stock > 10)
                                <span class="badge bg-success px-2 py-1">{{ $stock }}</span>
                            @elseif($stock > 0)
                                <span class="badge bg-warning text-dark px-2 py-1">{{ $stock }}</span>
                            @else
                                <span class="badge bg-danger px-2 py-1">Hết hàng</span>
                            @endif
                        </td>

                        <td class="text-center pe-3">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('admin.products.edit', ['product' => $product, 'page' => request()->page]) }}" class="btn btn-sm btn-outline-warning" title="Sửa">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?')" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-box-seam fs-1 d-block mb-2"></i>
                            Không tìm thấy sản phẩm nào!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-end">
    {{ $products->appends(request()->all())->links('pagination::bootstrap-5') }}
</div>

@endsection