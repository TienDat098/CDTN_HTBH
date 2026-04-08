@extends('admin.layouts.app')

@section('title', 'Báo Cáo Doanh Thu Chi Tiết')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-success">
        <i class="bi bi-graph-up-arrow"></i> Báo Cáo Doanh Thu Theo 
        @if($type == 'month') Từng Tháng 
        @elseif($type == 'year') Từng Năm 
        @else Từng Ngày 
        @endif
    </h3>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body bg-light rounded">
        <form action="{{ route('admin.reports.revenue') }}" method="GET" class="row align-items-end g-3">
            <div class="col-md-3">
                <label class="form-label fw-bold">Xem theo:</label>
                <select name="type" class="form-select">
                    <option value="day" {{ $type == 'day' ? 'selected' : '' }}>Từng ngày</option>
                    <option value="month" {{ $type == 'month' ? 'selected' : '' }}>Từng tháng</option>
                    <option value="year" {{ $type == 'year' ? 'selected' : '' }}>Từng năm</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Từ ngày:</label>
                <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Đến ngày:</label>
                <input type="date" name="to_date" class="form-control" value="{{ $toDate }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success w-100 fw-bold">
                    <i class="bi bi-funnel"></i> Lọc
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle mb-0 text-center">
                <thead class="table-light">
                    <tr>
                        <th class="text-start">Thời gian</th>
                        <th class="text-center">Lượng đơn hàng</th>
                        <th class="text-center">Doanh thu tổng</th>
                        <th class="text-center">Đơn thấp nhất</th>
                        <th class="text-center">Đơn cao nhất</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sumOrders = 0;
                        $sumRevenue = 0;
                    @endphp
                    
                    @forelse($reports as $row)
                        @php
                            $sumOrders += $row->total_orders;
                            $sumRevenue += $row->total_revenue;
                        @endphp
                        <tr>
                            <td class="text-start fw-bold text-dark">
                                @if($type == 'day')
                                    {{ \Carbon\Carbon::parse($row->date_group)->format('d/m/Y') }}
                                @elseif($type == 'month')
                                    Tháng {{ \Carbon\Carbon::parse($row->date_group . '-01')->format('m/Y') }}
                                @elseif($type == 'year')
                                    Năm {{ $row->date_group }}
                                @endif
                            </td>
                            <td class="text-center"><span class="badge bg-light text-dark border">{{ $row->total_orders }}</span></td>
                            <td class="text-center fw-bold text-primary">{{ number_format($row->total_revenue) }}đ</td>
                            <td class="text-center text-muted">{{ number_format($row->min_order) }}đ</td>
                            <td class="text-center text-muted">{{ number_format($row->max_order) }}đ</td>
                        </tr>
                    @empty
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="py-4 text-muted">Không có dữ liệu doanh thu trong khoảng thời gian này.</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($reports) > 0)
                <tfoot class="table-light fw-bold fs-5">
                    <tr>
                        <td class="text-end pe-3">TỔNG CỘNG:</td>
                        <td class="text-center">{{ number_format($sumOrders) }}</td>
                        <td class="text-center text-danger">{{ number_format($sumRevenue) }}đ</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection