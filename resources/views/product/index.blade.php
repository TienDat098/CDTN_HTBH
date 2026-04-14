@extends('layouts.app')

@section('title', 'Danh sách sản phẩm - Web Tạp Hóa')

@section('content')
<div class="container py-4 mb-5">
    <div class="row">
        
        <div class="col-lg-3 pe-lg-4 mb-4">
            <div class="bg-white p-3 rounded shadow-sm border">
                
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                    <h6 class="fw-bold text-primary mb-0"><i class="bi bi-funnel-fill me-2"></i>BỘ LỌC</h6>
                    <a href="{{ route('product.index') }}" class="text-danger small text-decoration-none"><i class="bi bi-arrow-counterclockwise"></i> Làm mới</a>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold mb-3 border-start border-3 border-primary ps-2">DANH MỤC</h6>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('product.index') }}" class="text-decoration-none text-primary fw-bold">
                            <i class="bi bi-record-circle me-1"></i> Tất cả sản phẩm
                        </a>
                        
                        @if(isset($globalCategories))
                            @foreach($globalCategories as $cat)
                            <a href="{{ route('category.show', $cat->slug) }}" class="text-decoration-none text-dark filter-link d-flex justify-content-between">
                                <span><i class="bi bi-circle me-2 text-muted" style="font-size: 0.7rem;"></i>{{ $cat->name }}</span>
                                <i class="bi bi-chevron-right text-muted small"></i>
                            </a>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold mb-3 border-start border-3 border-primary ps-2">KHOẢNG GIÁ</h6>
                    <form action="{{ route('product.index') }}" method="GET">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <input type="number" name="min_price" class="form-control form-control-sm text-center" placeholder="0đ" value="{{ request('min_price') }}">
                            <span class="text-muted">-</span>
                            <input type="number" name="max_price" class="form-control form-control-sm text-center" placeholder="5,000,000đ" value="{{ request('max_price') }}">
                        </div>
                        <button type="submit" class="btn btn-outline-primary btn-sm w-100 mt-2">Áp dụng</button>
                    </form>
                </div>

            </div>
        </div>

        <div class="col-lg-9">
            
            <div class="bg-white p-3 rounded shadow-sm border mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1">Danh sách sản phẩm</h5>
                    <small class="text-muted">Hiển thị {{ $products->count() }} trên tổng số {{ $products->total() }} sản phẩm</small>
                </div>
                
                <div class="d-flex align-items-center gap-2">
                    <span class="small text-muted text-nowrap"><i class="bi bi-sort-down"></i> Sắp xếp:</span>
                    <select class="form-select form-select-sm border-secondary w-auto" onchange="window.location.href=this.value;">
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'new']) }}" {{ request('sort') == 'new' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                    </select>
                </div>
            </div>

            <div class="row">
                @forelse($products as $product)
                    <div class="col-md-4 col-sm-6 mb-4">
                        <div class="card h-100 product-card border-0 shadow-sm text-center">
                            
                            <div class="product-img-wrapper position-relative overflow-hidden">
                                @if(isset($product->original_price) && $product->original_price > $product->sell_price)
                                    @php $discount = round((($product->original_price - $product->sell_price) / $product->original_price) * 100); @endphp
                                    <span class="badge bg-danger position-absolute top-0 start-0 m-2 sale-badge">
                                        SALE -{{ $discount }}%
                                    </span>
                                @endif
                                
                                <a href="{{ route('product.show', $product->slug ?? $product->id) }}" class="d-block">
                                    <img src="{{ $product->thumbnail }}" class="card-img-top w-100 p-3 img-hover-zoom" style="height: 220px; object-fit: contain;" alt="{{ $product->name }}">
                                </a>
                            </div>

                            <div class="card-body p-3 d-flex flex-column justify-content-center">
                                <a href="{{ route('product.show', $product->slug ?? $product->id) }}" class="text-decoration-none">
                                    <h6 class="card-title product-title mb-2 text-dark">{{ $product->name }}</h6>
                                </a>
                                
                                <div class="price-box mt-auto mb-3">
                                    <span class="text-danger fw-bold fs-6">{{ number_format($product->sell_price) }}đ</span>
                                    @if(isset($product->original_price) && $product->original_price > $product->sell_price)
                                        <span class="text-muted text-decoration-line-through ms-2" style="font-size: 0.85rem;">{{ number_format($product->original_price) }}đ</span>
                                    @endif
                                </div>

                                <a href="{{ route('product.show', $product->slug ?? $product->id) }}" class="btn btn-outline-primary btn-sm rounded-pill w-100 fw-bold">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <img src="{{ asset('images/empty-cart.png') }}" width="150" class="mb-3 opacity-50" onerror="this.style.display='none'">
                        <h5 class="text-muted">Không tìm thấy sản phẩm nào phù hợp!</h5>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
</div>

<style>
/* CSS cho Card Sản Phẩm */
.product-card {
    border-radius: 8px;
    border: 1px solid #f0f0f0 !important;
    transition: all 0.3s ease;
}
.product-card:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
}
.product-img-wrapper {
    background-color: #fff;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}
.img-hover-zoom {
    transition: transform 0.3s ease;
}
.product-card:hover .img-hover-zoom {
    transform: scale(1.05); /* Phóng to ảnh nhẹ khi đưa chuột vào */
}
.product-title {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    font-size: 0.95rem;
    line-height: 1.4;
    min-height: 2.8em;
    transition: color 0.2s;
}
.product-title:hover {
    color: #0d6efd !important;
}
.sale-badge {
    z-index: 2;
    font-size: 0.75rem;
    padding: 5px 10px;
    border-radius: 4px;
}
/* Link ở cột bộ lọc */
.filter-link:hover {
    color: #0d6efd !important;
    padding-left: 5px;
    transition: all 0.2s;
}
</style>
@endsection