@extends('layouts.app')

@section('title', 'Cửa Hàng Tạp Hóa')

@section('content')

<div class="row mb-4">
    <div class="col-lg-8 mb-3">
        <div id="homeSlider" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner rounded shadow">
                <div class="carousel-item active">
                    <img src="{{ asset('images/banner1.jpg') }}" class="d-block w-100 slider-img">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/banner2.jpg') }}" class="d-block w-100 slider-img">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/banner3.jpg') }}" class="d-block w-100 slider-img">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#homeSlider" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#homeSlider" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="banner-box shadow rounded mb-3">
            <img src="{{ asset('images/banner-right1.jpg') }}" class="w-100 banner-img">
        </div>
        <div class="banner-box shadow rounded">
            <img src="{{ asset('images/banner-right2.jpg') }}" class="w-100 banner-img">
        </div>
    </div>
</div>

<div class="container mb-4">
    <div class="bg-white p-3 rounded shadow-sm">
        <h5 class="fw-bold text-danger mb-3">
            DANH MỤC NỔI BẬT
        </h5>
        <div class="category-list">
            @foreach($categories as $category)
                <a href="{{ route('category.show', $category->slug) }}" class="text-decoration-none text-dark text-center category-item">
                    <img src="{{ $category->image ? asset('images/categories/'.$category->image) : asset('images/no-image.png') }}">
                    <div class="mt-2">{{ $category->name }}</div>
                </a>
            @endforeach
        </div>
    </div>
</div>

@if(isset($activePromotions) && $activePromotions->count() > 0)
<div class="container mb-5 px-0">
    <h4 class="mb-3 fw-bold text-danger">
        <i class="bi bi-ticket-perforated-fill me-2"></i>MÃ GIẢM GIÁ HOT
    </h4>
    <div class="row gx-3">
        @foreach($activePromotions as $promo)
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="coupon-item">
                <div class="coupon-left">
                    <span class="fs-4 fw-bold">
                        @if($promo->discount_type == 'percent')
                            {{ intval($promo->discount_value) }}%
                        @else
                            {{ number_format($promo->discount_value / 1000) }}k
                        @endif
                    </span>
                    <span class="small">OFF</span>
                </div>
                <div class="coupon-right">
                    <div class="fw-bold text-danger mb-1" style="font-size: 15px;">Mã: {{ $promo->code }}</div>
                    <div class="small text-muted mb-2">Đơn từ {{ number_format($promo->min_order_value) }}đ</div>
                    <div class="d-flex justify-content-between align-items-center mt-auto w-100">
                        <small class="text-muted" style="font-size: 11px;">HSD: {{ $promo->end_date->format('d/m') }}</small>
                        <button class="btn-copy-code" data-code="{{ $promo->code }}">Copy</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif



<div class="text-center mb-4 mt-5">
    <h4 class="fw-bold text-primary text-uppercase"> SẢN PHẨM NỔI BẬT</h4>
</div>

<div class="row">
    @foreach($products as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100 product-card border-0 shadow-sm text-center">
                
                <div class="product-img-wrapper position-relative overflow-hidden">
                    @if(isset($product->original_price) && $product->original_price > $product->sell_price)
                        @php
                            $discount = round((($product->original_price - $product->sell_price) / $product->original_price) * 100);
                        @endphp
                        <span class="badge bg-danger position-absolute top-0 start-0 m-2 sale-badge">
                            <i class="bi bi-lightning-fill"></i> SALE -{{ $discount }}%
                        </span>
                    @endif
                    
                    <img src="{{ $product->thumbnail }}" class="card-img-top w-100 p-3" style="height: 220px; object-fit: contain;" alt="{{ $product->name }}">
                    
                    <div class="overlay-btn d-flex justify-content-center align-items-center">
                        <a href="{{ route('product.show', $product->slug ?? $product->id) }}" class="btn btn-primary rounded-pill px-4 py-2 btn-xem-ngay shadow">Xem ngay</a>
                    </div>
                </div>

                <div class="card-body p-3 d-flex flex-column justify-content-center">
                    <h6 class="card-title product-title mb-2 text-dark">{{ $product->name }}</h6>
                    
                    <div class="price-box mt-auto">
                        <span class="text-danger fw-bold fs-6">{{ number_format($product->sell_price) }}đ</span>
                        @if(isset($product->original_price) && $product->original_price > $product->sell_price)
                            <span class="text-muted text-decoration-line-through ms-2" style="font-size: 0.85rem;">{{ number_format($product->original_price) }}đ</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="d-none">
    {{ $products->links('pagination::bootstrap-5') }}
</div>

<div class="text-center mt-4 mb-5">
    <a href="{{ route('product.index') }}" class="btn btn-primary rounded-pill px-5 py-2 fw-bold btn-xem-them shadow fs-6">
        Xem thêm sản phẩm <i class="bi bi-arrow-right fw-bold ms-1"></i>
    </a>
</div>

<style>
/* CSS Danh mục & Coupon giữ nguyên... */
.category-list { display: flex; justify-content: space-between; align-items: center; }
.category-item { width: 100px; text-align: center; }
.category-item img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 1px solid #eee; padding: 5px; background: #fff; }
.category-item:hover { color: #ee4d2d; transform: translateY(-5px); transition: 0.3s; }
.coupon-item { display: flex; border: 1px solid #dc3545; border-radius: 8px; background: #fff; position: relative; height: 100px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
.coupon-item::before { content: ''; position: absolute; left: -8px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; background: #f8f9fa; border-right: 1px solid #dc3545; border-radius: 50%; z-index: 2; }
.coupon-item::after { content: ''; position: absolute; right: -8px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; background: #f8f9fa; border-left: 1px solid #dc3545; border-radius: 50%; z-index: 2; }
.coupon-left { background: #dc3545; color: white; width: 35%; display: flex; flex-direction: column; align-items: center; justify-content: center; border-right: 2px dashed #fff; border-top-left-radius: 7px; border-bottom-left-radius: 7px; }
.coupon-right { width: 65%; height: 100%; padding: 10px 15px; display: flex; flex-direction: column; background: #fff; border-top-right-radius: 7px; border-bottom-right-radius: 7px; }
.btn-copy-code { border: 1px solid #dc3545; color: #dc3545; background: #fff; padding: 3px 12px; border-radius: 4px; font-size: 12px; cursor: pointer; transition: 0.2s; }
.btn-copy-code:hover { background: #dc3545; color: #fff; }

/* ================== CSS PRODUCT CARD (Giao diện Mới Ảnh 1 & 3) ================== */
.product-card {
    transition: all 0.3s ease;
    border-radius: 6px; /* Bo góc nhẹ giống ảnh 1 */
    border: 1px solid #f0f0f0 !important;
}

.product-card:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
}

.product-img-wrapper {
    background-color: #fff;
    border-top-left-radius: 6px;
    border-top-right-radius: 6px;
}

/* Tem SALE màu đỏ */
.sale-badge {
    z-index: 2;
    font-size: 0.75rem;
    padding: 5px 10px;
    border-radius: 4px;
    font-weight: 600;
}

/* Lớp mờ phủ lên ảnh trắng đục */
.overlay-btn {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(255, 255, 255, 0.6); 
    opacity: 0;
    transition: all 0.3s ease;
    z-index: 10;
}

.product-card:hover .overlay-btn {
    opacity: 1; 
}

/* Nút Xem ngay màu xanh */
.btn-xem-ngay {
    background-color: #3b82f6;
    border-color: #3b82f6;
    transform: translateY(15px);
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    opacity: 0;
}

.btn-xem-ngay:hover {
    background-color: #2563eb;
    border-color: #2563eb;
}

.product-card:hover .btn-xem-ngay {
    transform: translateY(0);
    opacity: 1;
}

/* Cắt chữ tiêu đề 2 dòng */
.product-title {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    font-size: 0.95rem;
    line-height: 1.4;
    min-height: 2.8em;
}

/* Nút Xem thêm sản phẩm (Ảnh 3) */
.btn-xem-them {
    background-color: #0d6efd;
    border: none;
    transition: all 0.3s ease;
}

.btn-xem-them:hover {
    background-color: #0b5ed7;
    padding-left: 3.5rem !important;
    padding-right: 3.5rem !important; /* Dãn dài ra khi lướt chuột */
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // JS Copy Code giữ nguyên
    document.querySelectorAll('.btn-copy-code').forEach(btn => {
        btn.addEventListener('click', function() {
            const code = this.getAttribute('data-code');
            navigator.clipboard.writeText(code).then(() => {
                const originalText = this.innerHTML;
                this.innerHTML = 'Đã chép!';
                this.classList.add('bg-danger', 'text-white');
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.classList.remove('bg-danger', 'text-white');
                }, 2000);
            });
        });
    });
});
</script>
@endsection