@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-5">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="fw-bold mb-1">Chi tiết đơn hàng #{{ $order->order_code }}</h3>
            <span class="text-muted small">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <a href="{{ route('profile.orders') }}" class="btn btn-secondary btn-sm px-3 fw-bold">
            Quay lại
        </a>
    </div>

    <!-- THANH TIẾN TRÌNH TRẠNG THÁI -->
    <div class="card shadow-sm border-0 mb-4 py-4">
        <div class="card-body">
            @if($order->status == 'cancelled')
                <div class="text-center text-danger">
                    <i class="bi bi-x-circle-fill fs-1"></i>
                    <h5 class="fw-bold mt-2">Đơn hàng đã bị hủy</h5>
                </div>
            @elseif($order->status == 'returned')
                <div class="text-center text-warning">
                    <i class="bi bi-arrow-return-left fs-1"></i>
                    <h5 class="fw-bold mt-2">Đơn hàng đã hoàn trả / Hoàn tiền</h5>
                </div>
            @else
                <div class="tracking-wrapper">
                    <!-- Bước 1: Đã đặt hàng -->
                    <div class="tracking-step active">
                        <div class="tracking-icon">1</div>
                        <div class="tracking-text">Đã đặt hàng<br><small class="text-muted fw-normal">(Đang chuẩn bị)</small></div>
                    </div>
                    
                    <div class="tracking-line {{ in_array($order->status, ['shipping', 'completed']) ? 'active' : '' }}"></div>
                    
                    <!-- Bước 2: Đang giao hàng -->
                    <div class="tracking-step {{ in_array($order->status, ['shipping', 'completed']) ? 'active' : '' }}">
                        <div class="tracking-icon">2</div>
                        <div class="tracking-text">Đang giao hàng</div>
                    </div>
                    
                    <div class="tracking-line {{ $order->status == 'completed' ? 'active' : '' }}"></div>
                    
                    <!-- Bước 3: Hoàn thành -->
                    <div class="tracking-step {{ $order->status == 'completed' ? 'active' : '' }}">
                        <div class="tracking-icon">3</div>
                        <div class="tracking-text">Hoàn thành</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- NỘI DUNG CHI TIẾT -->
    <div class="row">
        <!-- Cột trái: Sản phẩm -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom-0 pt-3 pb-2">
                    <h6 class="text-primary fw-bold mb-0"><i class="bi bi-box-seam me-2"></i>Sản phẩm</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table align-middle mb-0 text-center">
                        <thead class="table-light text-muted small">
                            <tr>
                                <th class="text-start ps-4">Sản phẩm</th>
                                <th>Số lượng</th>
                                <th class="text-end pe-4">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td class="text-start ps-4 py-3">
                                    <div class="fw-bold text-dark">{{ $item->product->name ?? 'Sản phẩm đã xóa' }}</div>
                                    <small class="text-muted">Phân loại: {{ $item->variant->name ?? 'Bán lẻ' }}</small>
                                </td>
                                <td>x{{ $item->quantity }}</td>
                                <td class="text-end pe-4 fw-bold text-dark">{{ number_format($item->price * $item->quantity) }}đ</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-top">
                            <tr>
                                <td colspan="2" class="text-end py-3 text-muted">Tổng tiền hàng:</td>
                                <td class="text-end pe-4 py-3 fw-bold">{{ number_format($order->final_total) }}đ</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-end pb-3 text-dark fw-bold fs-5">Thành tiền:</td>
                                <td class="text-end pe-4 pb-3 fw-bold text-danger fs-5">{{ number_format($order->final_total) }}đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer bg-white border-top-0 p-3">
                    <form action="{{ route('orders.reorder', $order->id) }}" method="POST" class="m-0 p-0 d-inline-block">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm px-3 fw-bold rounded-1 text-dark shadow-sm" style="background-color: #ffc107; border: none;">
                            <i class="bi bi-cart-fill me-1"></i> Mua lại
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Cột phải: Thông tin & Địa chỉ -->
        <div class="col-lg-4">
            <!-- Địa chỉ -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-3 pb-2">
                    <h6 class="text-primary fw-bold mb-0"><i class="bi bi-geo-alt-fill me-2"></i>Địa chỉ nhận hàng</h6>
                </div>
                <div class="card-body pt-2">
                    <p class="mb-1 fw-bold text-dark">{{ $order->customer_name ?? $order->user->name }}</p>
                    <p class="mb-1 text-muted small">SĐT: {{ $order->customer_phone ?? ($order->user->phone ?? 'Không rõ') }}</p>
                    <p class="mb-0 text-muted small">{{ $order->shipping_address ?? 'Không có địa chỉ' }}</p>
                </div>
            </div>

            <!-- Thanh toán -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pt-3 pb-2">
                    <h6 class="text-primary fw-bold mb-0"><i class="bi bi-info-circle-fill me-2"></i>Thông tin đơn hàng</h6>
                </div>
                <div class="card-body pt-2 small">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Phương thức TT:</span>
                        <span class="fw-bold text-dark">{{ strtoupper($order->payment->payment_method ?? 'COD') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Trạng thái TT:</span>
                        @if($order->payment && $order->payment->status == 'completed')
                            <span class="badge bg-success">Đã thanh toán</span>
                        @else
                            <span class="badge bg-secondary">Chưa thanh toán</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS Tracking 3 Bước */
    .tracking-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        max-width: 800px;
        margin: 0 auto;
        position: relative;
    }
    
    .tracking-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        z-index: 2;
        width: 120px;
    }
    
    .tracking-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e9ecef;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 18px;
        margin-bottom: 8px;
        transition: 0.3s;
    }
    
    .tracking-text {
        font-size: 14px;
        font-weight: 600;
        color: #6c757d;
        text-align: center;
    }

    .tracking-line {
        flex-grow: 1;
        height: 4px;
        background-color: #e9ecef;
        margin-top: -30px; /* Căn giữa dòng với hình tròn */
        z-index: 1;
        transition: 0.3s;
    }

    /* Trạng thái Active (Màu xanh lá) */
    .tracking-step.active .tracking-icon {
        background-color: #198754; /* Màu xanh chuẩn Success Bootstrap */
        color: white;
    }
    
    .tracking-step.active .tracking-text {
        color: #000;
    }

    .tracking-line.active {
        background-color: #198754;
    }
</style>
@endsection