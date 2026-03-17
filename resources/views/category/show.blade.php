@extends('layouts.app')

@section('title', $category->name)

@section('content')

<h3 class="mb-4">Danh mục: {{ $category->name }}</h3>

<div class="row">
    @foreach($products as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100">

                <img src="{{ $product->images->first()->image_url ?? asset('images/no-image.png') }}"
                     class="card-img-top"
                     style="height:200px;object-fit:cover;">

                <div class="card-body">
                    <h6>{{ $product->name }}</h6>

                    <p class="text-danger fw-bold">
                        {{ number_format($product->sell_price) }}đ
                    </p>
                </div>

            </div>
        </div>
    @endforeach
</div>

<div class="mt-3">
    {{ $products->links('pagination::bootstrap-5') }}
</div>

@endsection