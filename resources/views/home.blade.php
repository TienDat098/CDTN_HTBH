@extends('layouts.app')

@section('title', 'Cửa Hàng Tạp Hóa')

@section('content')

<h1 class="mb-4">Danh sách sản phẩm</h1>

<div class="row">
    @foreach($products as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100">

                <img src="{{ asset($product->thumbnail) }}" class="card-img-top">

                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>

                    <p class="text-danger fw-bold">
                        {{ number_format($product->sell_price) }}đ
                    </p>

                    <button class="btn btn-success w-100">
                        Thêm vào giỏ
                    </button>
                </div>

            </div>
        </div>
    @endforeach
</div>
<div class="d-flex justify-content-center mt-4">
    {{ $products->links('pagination::bootstrap-5') }}
</div>
@endsection