@extends('admin.layouts.app')
@section('title', 'Lịch sử kho')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3>Lịch sử Nhập / Xuất kho</h3>
</div>

<table class="table table-bordered bg-white shadow-sm">
    <thead class="table-dark">
        <tr>
            <th>Thời gian</th>
            <th>Sản phẩm</th>
            <th>Người thực hiện</th>
            <th>Loại</th>
            <th>Số lượng</th>
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
                @if($log->type == 'import')
                    <span class="badge bg-success">NHẬP KHO</span>
                @else
                    <span class="badge bg-danger">XUẤT KHO</span>
                @endif
            </td>
            <td class="fw-bold {{ $log->type == 'import' ? 'text-success' : 'text-danger' }}">
                {{ $log->type == 'import' ? '+' : '-' }}{{ $log->quantity }}
            </td>
            <td class="text-muted">{{ $log->note }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">Chưa có dữ liệu lịch sử kho</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $logs->links('pagination::bootstrap-5') }}
@endsection