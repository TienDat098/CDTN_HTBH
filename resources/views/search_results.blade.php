@extends('layouts.app') 

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Kết quả tìm kiếm cho: <span class="text-primary">"{{ $keyword }}"</span></h4>
    
    <div class="row">
        @forelse($products as $product)
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm border-0 rounded-3">
                    
                    <!-- Dùng cấu trúc ảnh y hệt trang chủ của bạn -->
                    <div style="height: 200px; overflow: hidden;" class="bg-light rounded-top">
                        <a href="{{ route('product.show', $product->slug) }}">
                            <img src="{{ $product->thumbnail }}" class="card-img-top w-100 h-100" 
                                 style="object-fit: cover;" alt="{{ $product->name }}">
                        </a>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title text-truncate-2 mb-2">
                            <a href="{{ route('product.show', $product->slug) }}" class="text-dark text-decoration-none">
                                {{ $product->name }}
                            </a>
                        </h6>
                        
                        <div class="mt-auto">
                            <p class="text-danger fw-bold fs-5 mb-2">
                                {{ number_format($product->sell_price, 0, ',', '.') }}đ
                            </p>
                            <a href="{{ route('product.show', $product->slug) }}" class="btn btn-outline-primary w-100">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning text-center py-4 rounded-3 border-0 shadow-sm">
                    <i class="bi bi-search fs-1 text-muted mb-2"></i>
                    <p class="mb-0 fs-5">Rất tiếc, không tìm thấy sản phẩm nào phù hợp với từ khóa <strong>"{{ $keyword }}"</strong>.</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Hiển thị phân trang theo style Bootstrap 5 giống trang chủ --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $products->appends(['keyword' => $keyword])->links('pagination::bootstrap-5') }}
    </div>
</div>

<style>
    /* CSS giữ cho tên sản phẩm tối đa 2 dòng, giúp các thẻ card bằng nhau */
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        min-height: 40px; 
    }
    
    .card {
        transition: transform 0.2s;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }
</style>
@endsection