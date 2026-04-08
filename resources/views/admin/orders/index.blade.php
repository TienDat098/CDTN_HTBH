@extends('admin.layouts.app')

@section('title', 'Quản lý Đơn hàng')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0 d-flex align-items-center">
        <i class="bi bi-receipt me-2"></i> Danh sách Đơn hàng
        <span class="badge bg-secondary fs-6 ms-3 fw-medium" style="padding: 6px 12px;">Tổng: {{ $orders->total() }} đơn</span>
    </h3>
    <a href="{{ route('admin.pos.index') }}" class="btn btn-primary fw-bold">
        <i class="bi bi-plus-circle me-1"></i> Tạo đơn POS mới
    </a>
</div>

<div class="card shadow-sm border-0 mb-4 bg-light">
    <div class="card-body p-3">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="row gx-2 gy-2 align-items-center">
            
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="keyword" class="form-control border-start-0 ps-0" placeholder="Nhập mã đơn, tên khách hoặc SĐT..." value="{{ request('keyword') }}">
                </div>
            </div>

            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Đang chuẩn bị</option>
                    <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Đang giao</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>

            <div class="col-md-3">
                <select name="sort" class="form-select">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Tổng tiền cao nhất</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Tổng tiền thấp nhất</option>
                </select>
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1 fw-bold"><i class="bi bi-funnel-fill me-1"></i> Lọc</button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary" title="Làm mới"><i class="bi bi-arrow-counterclockwise"></i></a>
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
                        <th class="ps-3">Mã Đơn</th>
                        <th>Ngày tạo</th>
                        <th>Kênh bán</th>
                        <th>Người lập / Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Thanh toán</th>
                        <th>Trạng thái</th>
                        <th class="text-center pe-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="fw-bold text-primary ps-3">{{ $order->order_code }}</td>
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
                                <span class="fw-bold">{{ $order->user->name ?? $order->customer_name ?? 'Khách vãng lai' }}</span>
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
                            @if($order->status == 'pending')
                                <span class="badge bg-secondary">Chờ xử lý</span>
                            @elseif($order->status == 'preparing')
                                <span class="badge bg-warning text-dark">Đang chuẩn bị</span>
                            @elseif($order->status == 'shipping')
                                <span class="badge bg-info text-dark">Đang giao</span>
                            @elseif($order->status == 'completed')
                                <span class="badge bg-success">Hoàn thành</span>
                            @elseif($order->status == 'cancelled')
                                <span class="badge bg-danger">Đã hủy</span>
                            @else
                                <span class="badge bg-dark">{{ $order->status }}</span>
                            @endif
                        </td>

                        <td class="text-center pe-3">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Xem
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-box-seam fs-1 d-block mb-2"></i>
                            Không tìm thấy đơn hàng nào phù hợp!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-end">
    {{ $orders->appends(request()->all())->links('pagination::bootstrap-5') }}
</div>
@endsection