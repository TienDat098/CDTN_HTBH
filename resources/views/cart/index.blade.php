@extends('layouts.app')

@section('title', 'Giỏ hàng của bạn - Web Tạp Hóa')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-4"><i class="bi bi-cart3 text-danger me-2"></i>GIỎ HÀNG CỦA BẠN</h3>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('cart') && count(session('cart')) > 0)
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="ps-4">Sản phẩm / Phân loại</th>
                                        <th scope="col" class="text-center">Đơn giá</th>
                                        <th scope="col" class="text-center" style="width: 120px;">Số lượng</th>
                                        <th scope="col" class="text-end">Thành tiền</th>
                                        <th scope="col" class="text-center pe-4">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(session('cart') as $id => $details)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <a href="{{ route('product.show', $details['slug'] ?? $id) }}">
                                                        <img src="{{ $details['image'] ?? asset('images/no-image.png') }}" 
                                                            class="rounded me-3 img-thumbnail" width="70" height="70" 
                                                            style="object-fit: cover;" alt="{{ $details['name'] }}">
                                                    </a>
                                                    <div>
                                                        <a href="{{ route('product.show', $details['slug'] ?? $id) }}" class="text-decoration-none">
                                                            <h6 class="mb-1 text-dark fw-bold hover-text-danger">{{ $details['name'] }}</h6>
                                                        </a>
                                                        
                                                        @if(isset($details['variant_name']))
                                                            <div class="small bg-warning-subtle text-warning-emphasis p-1 px-2 rounded-pill d-inline-block border border-warning" style="font-size: 12px;">
                                                                <i class="bi bi-tag me-1"></i>Phân loại: <strong>{{ $details['variant_name'] }}</strong>
                                                            </div>
                                                        @else
                                                            <div class="small text-muted" style="font-size: 12px;">
                                                                Phân loại: <strong class="text-dark">Bán lẻ</strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center text-danger fw-bold">
                                                {{ number_format($details['price']) }}đ
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('cart.update') }}" method="POST" class="d-flex align-items-center justify-content-center">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $id }}">
                                                    <input type="number" name="quantity" value="{{ $details['quantity'] }}" 
                                                           class="form-control form-control-sm text-center border-warning fw-bold" 
                                                           style="width: 60px; font-size: 16px;" min="1" onchange="this.form.submit()">
                                                </form>
                                            </td>
                                            <td class="text-end text-danger fw-bold">
                                                {{ number_format($details['price'] * $details['quantity']) }}đ
                                            </td>
                                            <td class="text-center pe-4">
                                                <form action="{{ route('cart.remove') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $id }}">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Bạn có muốn xóa sản phẩm này?')">
                                                        <i class="bi bi-trash3-fill fs-5"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <a href="{{ route('home') }}" class="text-decoration-none text-danger fw-bold">
                        <i class="bi bi-arrow-left me-1"></i> Tiếp tục mua hàng
                    </a>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3 bg-white">
                    <div class="card-body p-4">
                        <h5 class="fw-bold border-bottom pb-3 mb-3">Thông tin đơn hàng</h5>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tạm tính:</span>
                            <span class="fw-bold fs-5 text-dark">{{ number_format($total) }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                            <span class="text-muted">Phí giao hàng:</span>
                            <span class="fw-bold text-success Miễn phí">Miễn phí</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-4 mt-2">
                            <span class="fw-bold fs-4 text-dark">TỔNG CỘNG:</span>
                            <span class="fw-bold fs-3 text-danger">{{ number_format($total) }}đ</span>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="btn btn-warning w-100 fw-bold py-3 fs-5 shadow-sm text-dark btn-thanh-toan">
                            TIẾN HÀNH THANH TOÁN
                        </a>
                    </div>
                </div>
            </div>
        </div>

    @else
        <div class="text-center py-5 shadow-sm bg-white rounded-3">
            <i class="bi bi-cart-x text-muted" style="font-size: 5rem;"></i>
            <h4 class="mt-3 fw-bold text-dark">Giỏ hàng của bạn đang trống</h4>
            <p class="text-muted mb-3">Hãy quay lại trang chủ và chọn cho mình những sản phẩm yêu thích nhé!</p>
            <a href="{{ route('home') }}" class="btn btn-danger px-4 py-2 fw-bold">
                MUA SẮM NGAY
            </a>
        </div>
    @endif
</div>

<style>
    /* Style mới cho giỏ hàng xịn */
    .table th { font-weight: 600; text-transform: uppercase; font-size: 13px; color: #6c757d; }
    .table-hover tbody tr:hover { background-color: #fef8f8; }
    .hover-text-danger:hover { color: #dc3545 !important; }
    
    .img-thumbnail { border-color: #f0f0f0; }
    
    .btn-thanh-toan { border-radius: 8px; transition: all 0.2s; }
    .btn-thanh-toan:hover { transform: translateY(-3px); box-shadow: 0 4px 10px rgba(255,193,7,0.3) !important; }
    
    .card { border-radius: 12px; }
    
    /* Làm con số số lượng to và rõ hơn */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
      opacity: 1; /* Luôn hiện nút tăng giảm */
    }
</style>
@endsection