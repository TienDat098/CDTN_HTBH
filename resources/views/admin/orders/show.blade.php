@extends('admin.layouts.app')

@section('title', 'Chi tiết Đơn hàng')

@section('content')
<div id="invoice-area">

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
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-info-circle"></i> Thông tin chung
                </div>
                <div class="card-body">
                    <p><strong>Ngày tạo:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Kênh bán:</strong> 
                        {!! $order->order_type == 'pos' ? '<span class="badge bg-info text-dark">Tại quầy (POS)</span>' : '<span class="badge bg-success">Website</span>' !!}
                    </p>
                    <p><strong>Trạng thái:</strong> 
                        {!! $order->status == 'completed' ? '<span class="badge bg-success">Hoàn thành</span>' : '<span class="badge bg-primary">Mới</span>' !!}
                    </p>
                    <p><strong>Thanh toán:</strong> 
                        {!! $order->payment_status == 'paid' ? '<span class="text-success fw-bold">Đã thanh toán</span>' : '<span class="text-danger fw-bold">Chưa thanh toán</span>' !!}
                    </p>
                    <hr>
                    @if($order->order_type == 'pos')
                        <p><strong>Thu ngân lập đơn:</strong> {{ $order->staff->name ?? 'Không rõ' }}</p>
                    @else
                        <p><strong>Khách hàng:</strong> {{ $order->user->name ?? 'Khách vãng lai' }}</p>
                        <p><strong>Địa chỉ giao:</strong> {{ $order->shipping_address ?? 'Không có' }}</p>
                        <p><strong>Ghi chú:</strong> {{ $order->note ?? 'Không có' }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-box-seam"></i> Danh sách Sản phẩm
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Đơn giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end pe-4">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $item->product->thumbnail ?? '' }}" class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                            <span class="fw-bold">{{ $item->product->name ?? 'Sản phẩm đã xóa' }}</span>
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
                                    <td colspan="3" class="text-end fw-bold fs-5">Tổng cộng:</td>
                                    <td class="text-end fw-bold fs-5 text-danger pe-4">{{ number_format($order->final_total) }}đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div> 
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
    }
</style>
@endsection