@extends('layouts.app')

@section('title', 'Livestream')

@section('content')
<div class="container my-4">

    <h3 class="fw-bold text-primary mb-4">
        <i class="bi bi-broadcast-pin me-2"></i>Livestream tư vấn sản phẩm
    </h3>

    @if($livestream)
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">

                        <h5 class="fw-bold mb-3">{{ $livestream->title }}</h5>

                        <div class="ratio ratio-16x9 rounded overflow-hidden">
                            <iframe
                                src="https://www.youtube.com/embed/{{ $livestream->youtube_video_id }}?autoplay=1&mute=1"
                                title="{{ $livestream->title }}"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen>
                            </iframe>
                        </div>

                        @if($livestream->description)
                            <p class="text-muted mt-3 mb-0">
                                {{ $livestream->description }}
                            </p>
                        @endif

                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-danger text-white fw-bold">
                        <i class="bi bi-bag-heart me-2"></i>Sản phẩm trong livestream
                    </div>

                    <div class="card-body p-0">
                        @forelse($livestream->products as $product)
                            <div class="p-3 border-bottom d-flex gap-3 align-items-center">
                                <img src="{{ $product->thumbnail }}"
                                     alt="{{ $product->name }}"
                                     style="width:70px;height:70px;object-fit:contain;">

                                <div class="flex-grow-1">
                                    <div class="fw-bold small">{{ $product->name }}</div>
                                    <div class="text-danger fw-bold">
                                        {{ number_format($product->sell_price) }}đ
                                    </div>

                                    <a href="{{ route('product.show', $product->slug ?? $product->id) }}"
                                       class="btn btn-sm btn-outline-primary mt-1">
                                        Xem sản phẩm
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="p-3 text-muted">
                                Chưa có sản phẩm nào được gắn với livestream.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="alert alert-info mt-3 small mb-0">
                    Bạn có thể nhắn tin cho admin hoặc hỏi trợ lý AI để được tư vấn sản phẩm đang phát.
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            Hiện tại chưa có livestream nào đang phát.
        </div>
    @endif

</div>
@endsection