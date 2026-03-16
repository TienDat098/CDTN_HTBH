@extends('admin.layouts.app')

@section('title', 'Quản lý Đơn hàng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold"><i class="bi bi-receipt"></i> Danh sách Đơn hàng</h3>
    <a href="{{ route('admin.pos.index') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tạo đơn POS mới
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Mã Đơn</th>
                        <th>Ngày tạo</th>
                        <th>Kênh bán</th>
                        <th>Người lập / Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Thanh toán</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="fw-bold text-primary">{{ $order->order_code }}</td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        
                        <td>
                            @if($order->order_type == 'pos')
                                <span class="badge bg-info text-dark"><i class="bi bi-shop"></i> Tại quầy (POS)</span>
                            @else
                                <span class="badge bg-success"><i class="bi bi-globe"></i> Website</span>
                            @endif
                        </td>

                        <td>
                            @if($order->order_type == 'pos')
                                <small class="text-muted d-block">Thu ngân:</small>
                                <span class="fw-bold">{{ $order->staff->name ?? 'Không rõ' }}</span>
                            @else
                                <small class="text-muted d-block">Khách hàng:</small>
                                <span class="fw-bold">{{ $order->user->name ?? 'Khách vãng lai' }}</span>
                            @endif
                        </td>

                        <td class="fw-bold text-danger">{{ number_format($order->final_total) }}đ</td>

                       <td>
                            @if($order->payment && $order->payment->status == 'completed')
                                <span class="badge bg-success">Đã thanh toán</span>
                            @else
                                <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                            @endif
                        </td>

                        <td>
                            @if($order->status == 'completed')
                                <span class="badge bg-success">Hoàn thành</span>
                            @elseif($order->status == 'new')
                                <span class="badge bg-primary">Mới</span>
                            @else
                                <span class="badge bg-secondary">{{ $order->status }}</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Xem
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Chưa có đơn hàng nào!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $orders->links('pagination::bootstrap-5') }}
</div>
@endsection