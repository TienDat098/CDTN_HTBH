@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

<h2 class="mb-4">Tổng quan hệ thống</h2>

<div class="row">

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary shadow">
            <div class="card-body">
                <h5>Tổng sản phẩm</h5>
                <h3>{{ $totalProducts }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success shadow">
            <div class="card-body">
                <h5>Danh mục</h5>
                <h3>{{ $totalCategories }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning shadow">
            <div class="card-body">
                <h5>Thương hiệu</h5>
                <h3>{{ $totalBrands }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-danger shadow">
            <div class="card-body">
                <h5>Người dùng</h5>
                <h3>{{ $totalUsers }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-dark shadow">
            <div class="card-body">
                <h5>Tổng tồn kho</h5>
                <h3>{{ $totalStock }}</h3>
            </div>
        </div>
    </div>

</div>

@endsection