@extends('admin.layouts.app')
@section('title', 'Lịch sử kho')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<div class="d-flex justify-content-between mb-3 align-items-center">
    <h3 class="m-0">Lịch sử Nhập / Xuất kho</h3>

    <a href="{{ route('admin.inventory.create') }}" class="btn btn-success fw-bold">
         NHẬP HÀNG MỚI
    </a>
</div>

<div class="card shadow-sm border-0 mb-4 bg-light">
    <div class="card-body">
        <form action="{{ route('admin.inventory.index') }}" method="GET" class="row gx-2 gy-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="keyword" class="form-control border-start-0 ps-0" placeholder="Tìm theo tên sản phẩm..." value="{{ request('keyword') }}">
                </div>
            </div>

            <div class="col-md-3">
                <select name="type" class="form-select">
                    <option value=""> Tất cả loại (Nhập/Xuất) </option>
                    <option value="import" {{ request('type') == 'import' ? 'selected' : '' }}> Xem NHẬP KHO</option>
                    <option value="export" {{ request('type') == 'export' ? 'selected' : '' }}> Xem XUẤT KHO</option>
                </select>
            </div>

            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1 fw-bold">
                    <i class="bi bi-funnel-fill me-1"></i> Lọc dữ liệu
                </button>
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary px-3" title="Làm mới (Bỏ lọc)">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Làm mới
                </a>
            </div>
        </form>
    </div>
</div>

<table class="table table-bordered bg-white shadow-sm align-middle">
    <thead class="table-light">
        <tr>
            <th>Thời gian</th>
            <th>Sản phẩm</th>
            <th>Người thực hiện</th>
            <th>Loại</th>
            <th class="text-center">Biến động</th>
            <th class="text-center">Tồn kho hiện hành</th>
            <th>Ghi chú</th>
        </tr>
    </thead>
    <tbody>
        @forelse($logs as $log)
        <tr>
            <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
            <td class="fw-bold">{{ $log->product->name ?? 'Sản phẩm đã bị xóa' }}</td>
            <td>{{ $log->user->name ?? 'Hệ thống' }}</td>
            
            <td>
                @if($log->type == 'import' || mb_strtoupper($log->type) == 'NHẬP KHO')
                    <span class="badge bg-success">NHẬP KHO</span>
                @else
                    <span class="badge bg-danger">XUẤT KHO</span>
                @endif
            </td>
            
            <td class="text-center fw-bold fs-5 {{ ($log->type == 'import' || mb_strtoupper($log->type) == 'NHẬP KHO') ? 'text-success' : 'text-danger' }}">
                {{ ($log->type == 'import' || mb_strtoupper($log->type) == 'NHẬP KHO') ? '+' : '-' }}{{ abs($log->quantity) }}
            </td>

            <td class="text-center">
                <span class="fw-bold fs-5 text-dark">
                    {{ $log->balance_after }}
                </span>
            </td>

            <td class="text-muted small">{{ $log->note }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center py-4">Chưa có dữ liệu lịch sử kho phù hợp với điều kiện lọc</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-end mt-3">
    {{ $logs->appends(request()->all())->links('pagination::bootstrap-5') }}
</div>
@endsection