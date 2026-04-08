@extends('admin.layouts.app')

@section('title', 'Quản lý Mã Giảm Giá')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark m-0">Quản lý Mã Giảm Giá (Coupons)</h3>
    <a href="{{ route('admin.promotions.create') }}" class="btn btn-primary fw-bold">
        <i class="bi bi-plus-lg me-1"></i> Thêm Mã Mới
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Mã Code</th>
                        <th>Giá trị giảm</th>
                        <th>Đơn tối thiểu</th>
                        <th>Số lượng còn</th>
                        <th>Thời gian hiệu lực</th>
                        <th>Trạng thái</th>
                        <th class="text-center pe-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promotions as $promo)
                    <tr>
                        <td class="fw-bold text-primary ps-3">{{ $promo->code }}</td>
                        
                        <td class="fw-bold text-danger">
                            @if($promo->discount_type == 'percent')
                                {{ number_format($promo->discount_value) }}%
                            @else
                                {{ number_format($promo->discount_value) }}đ
                            @endif
                        </td>
                        
                        <td>{{ number_format($promo->min_order_value) }}đ</td>
                        
                        <td>
                            @php $remaining = $promo->quantity - $promo->used_count; @endphp
                            @if($remaining > 0)
                                <span class="badge bg-success px-2 py-1">{{ $remaining }} / {{ $promo->quantity }}</span>
                            @else
                                <span class="badge bg-danger px-2 py-1">Đã hết</span>
                            @endif
                        </td>
                        
                        <td>
                            <div class="small text-muted"><i class="bi bi-calendar-event me-1"></i> Từ: {{ $promo->start_date->format('d/m/Y H:i') }}</div>
                            <div class="small text-muted"><i class="bi bi-calendar-x me-1"></i> Đến: {{ $promo->end_date->format('d/m/Y H:i') }}</div>
                        </td>
                        
                        <td>
                            @if(now()->between($promo->start_date, $promo->end_date) && $remaining > 0 && $promo->status == 1)
                                <span class="badge bg-primary">Đang hoạt động</span>
                            @elseif(now()->greaterThan($promo->end_date))
                                <span class="badge bg-secondary">Đã hết hạn</span>
                            @elseif($remaining <= 0)
                                <span class="badge bg-danger">Hết lượt</span>
                            @else
                                <span class="badge bg-warning text-dark">Chưa bắt đầu</span>
                            @endif
                        </td>
                        
                       <td class="text-center pe-3">
                        <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('admin.promotions.edit', $promo->id) }}" class="btn btn-sm btn-outline-warning" title="Sửa">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="{{ route('admin.promotions.destroy', $promo->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa mã này?')" title="Xóa">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted bg-light">Chưa có mã giảm giá nào được tạo.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-end">
    {{ $promotions->links('pagination::bootstrap-5') }}
</div>
@endsection