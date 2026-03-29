@extends('layouts.app')

@section('title', $category->name . ' - Web Tạp Hóa')

@section('content')
<div class="container py-4 mb-5">
    <!-- Breadcrumb (Đường dẫn) -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-danger"><i class="bi bi-house-door"></i> Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Cột Filter (Bên trái) - Có thể mở rộng sau -->
        <div class="col-lg-3 d-none d-lg-block">
            <div class="bg-white p-3 rounded-4 shadow-sm">
                <h5 class="fw-bold mb-3 border-bottom pb-2">Danh mục</h5>
                <ul class="list-unstyled mb-0">
                    <!-- Bạn có thể dùng vòng lặp in các danh mục khác ở đây -->
                    <li class="mb-2"><a href="#" class="text-decoration-none text-danger fw-bold"><i class="bi bi-caret-right-fill"></i> {{ $category->name }}</a></li>
                </ul>
            </div>
        </div>

        <!-- Cột Danh sách Sản phẩm (Bên phải) -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3 bg-white p-3 rounded-4 shadow-sm">
                <h4 class="mb-0 fw-bold text-uppercase">{{ $category->name }}</h4>
                <span class="text-muted">Có <b>{{ $products->total() }}</b> sản phẩm</span>
            </div>

            <!-- GRID SẢN PHẨM -->
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                @forelse($products as $product)
                    <div class="col">
                        <!-- THE SẢN PHẨM HIỆN ĐẠI -->
                        <div class="card h-100 border-0 rounded-4 shadow-sm product-card">
                            <!-- Nhãn Hết hàng -->
                            @if(!$product->stock || $product->stock->quantity <= 0)
                                <span class="badge bg-dark position-absolute top-0 start-0 m-2 z-2">Hết hàng</span>
                            @endif

                            <div class="img-wrapper p-3 text-center position-relative overflow-hidden">
                                <a href="{{ route('product.show', $product->slug) }}">
                                    <img src="{{ $product->thumbnail }}" class="card-img-top product-img" alt="{{ $product->name }}">
                                </a>
                            </div>

                            <div class="card-body d-flex flex-column p-3">
                                <small class="text-muted mb-1">{{ $product->brand->name ?? 'Không thương hiệu' }}</small>
                                
                                <h6 class="card-title mb-2">
                                    <a href="{{ route('product.show', $product->slug) }}" class="text-dark text-decoration-none product-name">
                                        {{ $product->name }}
                                    </a>
                                </h6>
                                
                                <div class="mt-auto d-flex justify-content-between align-items-end">
                                    <span class="text-danger fw-bold fs-5">{{ number_format($product->sell_price) }}đ</span>
                                    
                                    <!-- Nút thêm giỏ hàng nhanh (tròn) -->
                                    <a href="{{ route('product.show', $product->slug) }}" class="btn btn-warning rounded-circle btn-cart-quick d-flex justify-content-center align-items-center" style="width: 35px; height: 35px;">
                                        <i class="bi bi-cart-plus text-dark"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <img src="https://cdn-icons-png.flaticon.com/512/2748/2748558.png" width="120" class="mb-3 opacity-50">
                        <h5 class="text-muted">Chưa có sản phẩm nào trong danh mục này.</h5>
                    </div>
                @endforelse
            </div>

            <!-- Phân trang -->
            <div class="d-flex justify-content-center mt-5 custom-pagination">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<style>
    /* HIỆU ỨNG THẺ SẢN PHẨM HIỆN ĐẠI */
    .product-card {
        transition: all 0.3s ease;
        border: 1px solid transparent !important;
    }
    
    /* Khi rê chuột vào thẻ: Nổi lên, hiện viền cam, đổ bóng to hơn */
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        border-color: #ffc107 !important; 
    }

    /* Vùng chứa ảnh: Giữ kích thước cố định để layout không bị lệch */
    .img-wrapper {
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Hiệu ứng Zoom ảnh nhẹ khi rê chuột */
    .product-img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        transition: transform 0.4s ease;
    }
    .product-card:hover .product-img {
        transform: scale(1.08);
    }

    /* Cắt chữ thông minh: Nếu tên quá dài sẽ hiện dấu 3 chấm ở dòng thứ 2 */
    .product-name {
        display: -webkit-box;
        -webkit-line-clamp: 2; /* Số dòng tối đa */
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.4;
        height: 2.8em; /* 1.4 x 2 dòng */
        transition: color 0.2s;
    }
    .product-name:hover {
        color: #ef1c1c !important;
    }

    /* Nút giỏ hàng nhỏ gọn */
    .btn-cart-quick {
        transition: all 0.2s;
        opacity: 0.8;
    }
    .product-card:hover .btn-cart-quick {
        opacity: 1;
        transform: scale(1.1);
    }
</style>
@endsection