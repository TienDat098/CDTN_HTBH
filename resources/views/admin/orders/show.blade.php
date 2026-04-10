@extends('admin.layouts.app')

@section('title', 'Chi tiết Đơn hàng')

@section('content')
<div id="invoice-area">

    <!-- PHẦN HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">
            <i class="bi bi-receipt-cutoff"></i> Chi tiết Đơn hàng: <span class="text-primary">{{ $order->order_code }}</span>
        </h3>
        <div class="no-print">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
            <button class="btn btn-success" onclick="window.print()">
                <i class="bi bi-printer"></i> In Hóa Đơn
            </button>
        </div>
    </div>

    <div class="row">
        <!-- ================= CỘT TRÁI: THÔNG TIN VÀ SẢN PHẨM ================= -->
        <div class="col-lg-8 mb-4">
            
            <!-- THÔNG TIN CHUNG (Code của bạn) -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="bi bi-info-circle text-primary me-2"></i> Thông tin chung
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Ngày tạo:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            <p class="mb-2"><strong>Kênh bán:</strong> 
                                {!! $order->order_type == 'pos' ? '<span class="badge bg-info text-dark">Tại quầy (POS)</span>' : '<span class="badge bg-success">Website</span>' !!}
                            </p>
                            <p class="mb-2"><strong>Thanh toán:</strong> 
                                @if($order->payment && $order->payment->status == 'completed')
                                    <span class="text-success fw-bold">Đã thanh toán</span> 
                                    <span class="badge bg-light text-dark border border-secondary ms-1">
                                        {{ strtoupper($order->payment->payment_method) }}
                                    </span>
                                @else
                                    <span class="text-danger fw-bold">Chưa thanh toán</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 border-start">
                            @if($order->order_type == 'pos')
                                <p class="mb-2"><strong>Thu ngân lập đơn:</strong> <span class="text-primary">{{ $order->staff->name ?? 'Không rõ' }}</span></p>
                            @else
                                <p class="mb-2"><strong>Khách hàng:</strong> {{ $order->user->name ?? 'Khách vãng lai' }}</p>
                                <p class="mb-2"><strong>Địa chỉ giao:</strong> {{ $order->shipping_address ?? 'Không có' }}</p>
                                <p class="mb-0"><strong>Ghi chú:</strong> <span class="text-danger">{{ $order->note ?? 'Không có' }}</span></p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- DANH SÁCH SẢN PHẨM (Code của bạn) -->
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="bi bi-box-seam text-primary me-2"></i> Danh sách Sản phẩm
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Sản phẩm</th>
                                    <th class="text-center">Đơn giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end pe-4">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="ps-3 py-3">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $item->product->thumbnail ?? '' }}" class="rounded me-3 border" style="width: 50px; height: 50px; object-fit: cover;">
                                            <div>
                                                <span class="fw-bold d-block">{{ $item->product->name ?? 'Sản phẩm đã xóa' }}</span>
                                                <small class="text-muted">Phân loại: <strong class="text-warning">{{ $item->variant->name ?? 'Bán lẻ' }}</strong></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ number_format($item->price) }}đ</td>
                                    <td class="text-center">x{{ $item->quantity }}</td>
                                    <td class="text-end fw-bold text-danger pe-4">
                                        {{ number_format($item->price * $item->quantity) }}đ
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold fs-5 py-3">Tổng cộng:</td>
                                    <td class="text-end fw-bold fs-5 text-danger pe-4 py-3">{{ number_format($order->final_total) }}đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- ================= CỘT PHẢI: XỬ LÝ TRẠNG THÁI & LỊCH SỬ (Không hiển thị khi in) ================= -->
        <div class="col-lg-4 mb-4 no-print">
            
            <!-- FORM XỬ LÝ ĐƠN HÀNG -->
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-white fw-bold py-3 border-bottom-0">
                    <i class="bi bi-gear-fill text-primary me-2"></i> Xử lý đơn hàng
                </div>
                <div class="card-body p-4 pt-0">
                    
                    <form action="{{ route('admin.orders.update_status', $order->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Trạng thái hiện tại:</label>
                            <div class="p-3 bg-warning bg-opacity-10 border border-warning text-center rounded-3 fw-bold text-dark fs-6" style="background-color: #fff8e1 !important;">
                                <i class="bi bi-clock me-1"></i> 
                                @if($order->status == 'pending') CHỜ XỬ LÝ (COD)
                                @elseif($order->status == 'preparing') ĐANG CHUẨN BỊ HÀNG
                                @elseif($order->status == 'shipping') ĐANG GIAO HÀNG
                                @elseif($order->status == 'completed') KHÁCH ĐÃ NHẬN HÀNG
                                @elseif($order->status == 'cancelled') ĐÃ HỦY ĐƠN HÀNG
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Chuyển trạng thái tiếp theo:</label>
                            
                            @if($order->status == 'completed' || $order->status == 'cancelled')
                                <div class="alert alert-secondary text-center small mb-0">
                                    Đơn hàng đã kết thúc, không thể thay đổi trạng thái.
                                </div>
                            @else
                               <select name="status" class="form-select">
                                    <option value="{{ $order->status }}" selected>Giữ nguyên trạng thái</option>

                                    @if($order->status == 'pending_payment')
                                        <option value="pending">Xác nhận đã nhận tiền (Chuyển sang Chờ xử lý)</option>
                                        <option value="cancelled">Hủy đơn hàng</option>

                                    @elseif($order->status == 'pending')
                                        <option value="preparing">Chuyển sang: Đang chuẩn bị hàng</option>
                                        <option value="cancelled">Hủy đơn hàng</option>

                                    @elseif($order->status == 'preparing')
                                        <option value="shipping">Chuyển sang: Đang giao hàng</option>

                                    @elseif($order->status == 'shipping')
                                        <option value="completed">Xác nhận: Giao thành công</option>
                                        <option value="cancelled">Hủy đơn (Khách bom hàng)</option>
                                    @endif
                                </select>
                                
                                <button type="submit" class="btn btn-primary w-100 mt-3 py-2 fw-bold shadow-sm">
                                    CẬP NHẬT TRẠNG THÁI
                                </button>
                            @endif
                        </div>
                    </form>

                </div>
            </div>

            <!-- LỊCH SỬ TRẠNG THÁI -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="bi bi-clock-history text-primary me-2"></i> Lịch sử trạng thái
                </div>
                <div class="card-body p-3">
                    @if($order->statusHistory && $order->statusHistory->count() > 0)
                        <ul class="list-unstyled mb-0 small">
                            @foreach($order->statusHistory as $history)
                                <li class="mb-3 pb-3 border-bottom position-relative">
                                    <div class="fw-bold text-primary mb-1">
                                        <i class="bi bi-check-circle-fill me-1"></i> {{ $history->status }}
                                    </div>
                                    <div class="text-muted mb-1">
                                        <i class="bi bi-calendar3 me-1"></i> {{ $history->created_at->format('d/m/Y H:i:s') }}
                                    </div>
                                    <div class="fst-italic text-secondary">
                                        Lý do/Ghi chú: {{ $history->note }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                            Chưa có lịch sử trạng thái.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

</div> 

<!-- STYLE CHO IN ẤN (Code của bạn) -->
<style>
    @media print {
        body * {
            visibility: hidden; 
        }
        #invoice-area, #invoice-area * {
            visibility: visible; 
        }
        #invoice-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%; 
        }
        .no-print {
            display: none !important; 
        }
        .card { 
            border: none !important; 
            box-shadow: none !important; 
        }
        .card-header {
            border-bottom: 2px solid #000 !important;
        }
    }
</style>
@endsection