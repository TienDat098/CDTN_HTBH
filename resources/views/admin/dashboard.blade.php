@extends('admin.layouts.app')

@section('title','Dashboard')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<h2 class="mb-4">Tổng quan hệ thống</h2>

<div class="row row-cols-1 row-cols-md-4 g-3 mb-4">
    
    <div class="col">
        <div class="card h-100 border-0 shadow-sm" style="background-color: #3266cc; color: white; border-radius: 6px;">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-uppercase fw-bold mb-1" style="font-size: 13px; letter-spacing: 0.5px; opacity: 0.9;">Tổng sản phẩm</h6>
                        <h2 class="fw-bold mb-0">{{ $totalProducts }}</h2>
                    </div>
                    <i class="bi bi-box-seam" style="font-size: 2rem; opacity: 0.5;"></i>
                </div>
                <div class="mt-4 pt-2">
                    <a href="{{ route('admin.products.index') }}" class="text-white text-decoration-none" style="font-size: 14px;">
                        Quản lý <i class="bi bi-chevron-right ms-1" style="font-size: 11px;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card h-100 border-0 shadow-sm" style="background-color: #1e9e62; color: white; border-radius: 6px;">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-uppercase fw-bold mb-1" style="font-size: 13px; letter-spacing: 0.5px; opacity: 0.9;">Danh mục</h6>
                        <h2 class="fw-bold mb-0">{{ $totalCategories }}</h2>
                    </div>
                    <i class="bi bi-tag" style="font-size: 2rem; opacity: 0.5;"></i>
                </div>
                <div class="mt-4 pt-2">
                    <a href="{{ route('admin.categories.index') }}" class="text-white text-decoration-none" style="font-size: 14px;">
                        Quản lý <i class="bi bi-chevron-right ms-1" style="font-size: 11px;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card h-100 border-0 shadow-sm" style="background-color: #289fae; color: white; border-radius: 6px;">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-uppercase fw-bold mb-1" style="font-size: 13px; letter-spacing: 0.5px; opacity: 0.9;">Thương hiệu</h6>
                        <h2 class="fw-bold mb-0">{{ $totalBrands }}</h2>
                    </div>
                    <i class="bi bi-shop" style="font-size: 2rem; opacity: 0.5;"></i>
                </div>
                <div class="mt-4 pt-2">
                    <a href="{{ route('admin.brands.index') }}" class="text-white text-decoration-none" style="font-size: 14px;">
                        Quản lý <i class="bi bi-chevron-right ms-1" style="font-size: 11px;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card h-100 border-0 shadow-sm" style="background-color: #e8ad21; color: white; border-radius: 6px;">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-uppercase fw-bold mb-1" style="font-size: 13px; letter-spacing: 0.5px; opacity: 0.9;">Người dùng</h6>
                        <h2 class="fw-bold mb-0">{{ $totalUsers }}</h2>
                    </div>
                    <i class="bi bi-people" style="font-size: 2rem; opacity: 0.5;"></i>
                </div>
                <div class="mt-4 pt-2">
                    <a href="{{ route('admin.users.index') }}" class="text-white text-decoration-none" style="font-size: 14px;">
                        Xem danh sách <i class="bi bi-chevron-right ms-1" style="font-size: 11px;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card h-100 border-0 shadow-sm" style="background-color: #6c757d; color: white; border-radius: 6px;">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-uppercase fw-bold mb-1" style="font-size: 13px; letter-spacing: 0.5px; opacity: 0.9;">Tổng tồn kho</h6>
                        <h2 class="fw-bold mb-0">{{ number_format($totalStock) }}</h2>
                    </div>
                    <i class="bi bi-archive" style="font-size: 2rem; opacity: 0.5;"></i>
                </div>
                <div class="mt-4 pt-2">
                    <a href="{{ route('admin.inventory.index') }}" class="text-white text-decoration-none" style="font-size: 14px;">
                        Xem chi tiết <i class="bi bi-chevron-right ms-1" style="font-size: 11px;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card h-100 border-0 shadow-sm" style="background-color: #00c0ef; color: white; border-radius: 6px;">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-uppercase fw-bold mb-1" style="font-size: 13px; letter-spacing: 0.5px; opacity: 0.9;">Tổng doanh thu</h6>
                        <h2 class="fw-bold mb-0">{{ number_format($totalRevenue) }}đ</h2>
                    </div>
                    <i class="bi bi-wallet2" style="font-size: 2rem; opacity: 0.5;"></i>
                </div>
                <div class="mt-4 pt-2">
                    <a href="{{ route('admin.reports.revenue') }}" class="text-white text-decoration-none" style="font-size: 14px;">
                        Xem chi tiết <i class="bi bi-chevron-right ms-1" style="font-size: 11px;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card h-100 border-0 shadow-sm" style="background-color: #6f42c1; color: white; border-radius: 6px;">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-uppercase fw-bold mb-1" style="font-size: 13px; letter-spacing: 0.5px; opacity: 0.9;">Doanh thu hôm nay</h6>
                        <h2 class="fw-bold mb-0">{{ number_format($todayRevenue) }}đ</h2>
                    </div>
                    <i class="bi bi-cash-coin" style="font-size: 2rem; opacity: 0.5;"></i>
                </div>
                <div class="mt-4 pt-2">
                    <a href="{{ route('admin.reports.revenue', ['from_date' => date('Y-m-d')]) }}" class="text-white text-decoration-none" style="font-size: 14px;">
                        Xem hôm nay <i class="bi bi-chevron-right ms-1" style="font-size: 11px;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card h-100 border-0 shadow-sm" style="background-color: #cb3c28; color: white; border-radius: 6px;">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-uppercase fw-bold mb-1" style="font-size: 13px; letter-spacing: 0.5px; opacity: 0.9;">Sắp Hết Kho (<10)</h6>
                        <h2 class="fw-bold mb-0">{{ count($lowStockProducts) }}</h2>
                    </div>
                    <i class="bi bi-exclamation-octagon" style="font-size: 2rem; opacity: 0.5;"></i>
                </div>
                <div class="mt-4 pt-2">
                    <a href="#" class="text-white text-decoration-none" style="font-size: 14px;">
                        Xem cảnh báo <i class="bi bi-chevron-right ms-1" style="font-size: 11px;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<hr>

<div class="row mt-4">

        <div class="col-md-6">
                <h4>Doanh thu 7 ngày gần nhất</h4>
                 <div style="height:350px">
                        <canvas id="revenueDayChart"></canvas>
                    </div>
        </div>

        <div class="col-md-6">
                <h4>Doanh thu theo tháng</h4>
                <div style="height:350px">
                    <canvas id="revenueMonthChart"></canvas>
                </div>
        </div>

</div>

<hr class="mt-5">

    <div class="row mt-4">

    <div class="col-md-6">

    <h4>Top 5 sản phẩm bán chạy</h4>

<table class="table table-bordered">

    <thead>
        <tr>
        <th>#</th>
        <th>Sản phẩm</th>
        <th>Số lượng bán</th>
        </tr>
    </thead>

<tbody>

@foreach($topProducts as $index => $product)

    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $product->name }}</td>
        <td>{{ $product->total }}</td>
    </tr>

@endforeach

</tbody>

</table>

</div>


<div class="col-md-6">

<h4>Biểu đồ Top sản phẩm</h4>

<div style="height:350px">
<canvas id="topProductChart"></canvas>
</div>

</div>

</div>

<hr class="mt-5">



<h4>Sản phẩm sắp hết hàng (tồn < 10)</h4>

<table class="table table-bordered">

<thead>
<tr>
<th>#</th>
<th>Sản phẩm</th>
<th>Tồn kho</th>
</tr>
</thead>

<tbody>

@foreach($lowStockProducts as $index => $product)

<tr>
<td>{{ $index + 1 }}</td>
<td>{{ $product->name }}</td>
<td class="text-danger">{{ $product->quantity }}</td>
</tr>

@endforeach

</tbody>

</table>



<script>

const dayLabels = @json($revenueByDay->pluck('date'));
const dayData = @json($revenueByDay->pluck('total'));

new Chart(document.getElementById('revenueDayChart'), {

type:'line',

data:{
labels:dayLabels,
datasets:[{
label:'Doanh thu',
data:dayData,
borderColor:'blue',
backgroundColor:'rgba(0,0,255,0.1)',
tension:0.4
}]
},

options:{
responsive:true,
maintainAspectRatio:false
}

});
const monthLabels = @json($revenueByMonth->pluck('month'));
const monthData = @json($revenueByMonth->pluck('total'));
new Chart(document.getElementById('revenueMonthChart'), {
type:'bar',
data:{
labels:monthLabels,
datasets:[{
label:'Doanh thu',
data:monthData,
backgroundColor:'green'
}]
},
options:{
responsive:true,
maintainAspectRatio:false
}
});

const productLabels = @json($topProducts->pluck('name'));
const productData = @json($topProducts->pluck('total'));

new Chart(document.getElementById('topProductChart'), {

type:'pie',

    data:{
    labels:productLabels,
    datasets:[{
    data:productData,
    backgroundColor:[
    'red',
    'blue',
    'green',
    'orange',
    'purple'
    ]
    }]
    },

        options:{
        responsive:true,
        maintainAspectRatio:false
        }

});

</script>
<style>
    .custom-card-footer {
        transition: background-color 0.2s ease-in-out;
    }
    .custom-card-footer:hover {
        background-color: rgba(0,0,0,0.25) !important;
    }
</style>
@endsection