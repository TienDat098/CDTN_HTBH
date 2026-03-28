@extends('layouts.app')

@section('content')
<div class="row mt-4 mb-5">
    <!-- ================= CỘT TRÁI: SIDEBAR ================= -->
    <div class="col-lg-3 col-md-4 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center pt-4 pb-3">
                <div class="avatar-circle mx-auto mb-3">
                    <span class="fs-1 text-white fw-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    <div class="camera-icon shadow-sm">
                        <i class="bi bi-camera-fill"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-1">{{ Auth::user()->name }}</h5>
                <p class="text-muted small mb-4">{{ Auth::user()->email }}</p>

                <div class="list-group list-group-flush text-start custom-sidebar-menu">
                    <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-person-fill me-2"></i> Hồ sơ của tôi
                    </a>
                    
                    <!-- Nút Đơn mua ở trang này sẽ có class active (màu xanh) -->
                    <a href="{{ route('profile.orders') }}" class="list-group-item list-group-item-action active">
                        <i class="bi bi-receipt me-2"></i> Đơn mua
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="list-group-item list-group-item-action text-danger mt-2">
                            <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= CỘT PHẢI: LỊCH SỬ ĐƠN HÀNG ================= -->
    <div class="col-lg-9 col-md-8">
        <div class="card shadow-sm border-0 mb-4 h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-3">
                <h5 class="text-primary fw-bold mb-0">
                    <i class="bi bi-clock-history me-2"></i>Lịch sử đơn hàng
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center mb-0">
                        <thead class="table-light text-muted small">
                            <tr>
                                <th class="text-start ps-4">Mã đơn</th>
                                <th>Ngày đặt</th>
                                <th>Người nhận</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th class="text-end pe-4">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td class="text-start ps-4 fw-bold text-primary">#{{ $order->order_code }}</td>
                                <td class="small"><i class="bi bi-clock me-1 text-muted"></i>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $order->customer_name ?? $order->user->name }}</td>
                                <td class="fw-bold text-danger">{{ number_format($order->final_total) }}đ</td>
                                <td>
                                    @if($order->status == 'completed')
                                        <span class="badge bg-success px-2 py-1">Hoàn thành</span>
                                    @elseif($order->status == 'cancelled')
                                        <span class="badge bg-danger px-2 py-1">Đã hủy</span>
                                    @elseif($order->status == 'shipping')
                                        <span class="badge bg-primary px-2 py-1">Đang giao</span>
                                    @elseif($order->status == 'preparing')
                                        <span class="badge bg-warning text-dark px-2 py-1">Đang chuẩn bị</span>
                                    @else
                                        <span class="badge bg-secondary px-2 py-1">Chờ xử lý</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <!-- Nút Chi tiết (Bạn có thể bổ sung route show sau) -->
                                        <a href="{{ route('profile.orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm px-3 rounded-1">Chi tiết</a>
                                        
                                        @if($order->status == 'completed' || $order->status == 'cancelled')
                                            <!-- Nút Mua lại cho đơn đã xong/hủy -->
                                            <form action="{{ route('orders.reorder', $order->id) }}" method="POST" class="m-0 p-0 d-inline-block">
                                                @csrf
                                                <button type="submit" class="btn btn-warning btn-sm px-3 fw-bold rounded-1 text-dark shadow-sm" style="background-color: #ffc107; border: none;">
                                                    <i class="bi bi-cart-fill me-1"></i> Mua lại
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-box-seam fs-1 d-block mb-2"></i>
                                    Bạn chưa có đơn hàng nào.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Phân trang -->
            @if($orders->hasPages())
            <div class="card-footer bg-white border-top py-3">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
            @endif

        </div>
    </div>
</div>

<style>
    /* CSS Sidebar (Giữ nguyên như trang Profile) */
    .avatar-circle { width: 100px; height: 100px; background-color: #6c4e4e; border-radius: 50%; display: flex; align-items: center; justify-content: center; position: relative; }
    .camera-icon { position: absolute; bottom: 0; right: 0px; background: #0d6efd; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border: 2px solid white; cursor: pointer; }
    .custom-sidebar-menu .list-group-item { border: none; padding: 12px 16px; font-weight: 500; color: #495057; margin-bottom: 2px; border-radius: 4px; transition: all 0.2s; }
    .custom-sidebar-menu .list-group-item.active { background-color: #0d6efd; color: white; }
    .custom-sidebar-menu .list-group-item:hover:not(.active) { background-color: #f8f9fa; color: #0d6efd; }
    .custom-sidebar-menu button.text-danger:hover { background-color: #fff5f5 !important; color: #dc3545 !important; }
</style>
@endsection