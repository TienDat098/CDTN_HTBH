@extends('layouts.app')

@section('title', 'Cửa Hàng Tạp Hóa')

@section('content')


<div class="row mb-4">

    <!-- Slider -->
    <div class="col-lg-8 mb-3">

        <div id="homeSlider" class="carousel slide" data-bs-ride="carousel">

            <div class="carousel-inner rounded shadow">

                <div class="carousel-item active">
                    <img src="{{ asset('images/banner1.jpg') }}" class="d-block w-100 slider-img">
                </div>

                <div class="carousel-item">
                    <img src="{{ asset('images/banner2.jpg') }}" class="d-block w-100 slider-img">
                </div>

                <div class="carousel-item">
                    <img src="{{ asset('images/banner3.jpg') }}" class="d-block w-100 slider-img">
                </div>

            </div>

            <!-- nút chuyển -->
            <button class="carousel-control-prev" type="button" data-bs-target="#homeSlider" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#homeSlider" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>

        </div>

    </div>


    <!-- Banner bên phải -->
    <div class="col-lg-4">

        <div class="banner-box shadow rounded mb-3">
            <img src="{{ asset('images/banner-right1.jpg') }}" class="w-100 banner-img">
        </div>

        <div class="banner-box shadow rounded">
            <img src="{{ asset('images/banner-right2.jpg') }}" class="w-100 banner-img">
        </div>

    </div>

</div>

<div class="container mb-4">
    <div class="bg-white p-3 rounded shadow-sm">

        <h5 class="fw-bold text-danger mb-3">
            DANH MỤC NỔI BẬT
        </h5>

        <div class="category-list">
            @foreach($categories as $category)
                <a href="{{ route('category.show', $category->slug) }}" 
                   class="text-decoration-none text-dark text-center category-item">

                    <img 
                        src="{{ $category->image 
                            ? asset('images/categories/'.$category->image) 
                            : asset('images/no-image.png') }}"
                    >

                    <div class="mt-2">
                        {{ $category->name }}
                    </div>

                </a>
            @endforeach
        </div>

    </div>
</div>

<h4 class="mb-3"> Sản phẩm bán chạy</h4>

<div class="row">
    @foreach($bestSellers as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100">

                <img src="{{ $product->thumbnail }}"  
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


<h1 class="mb-4">Danh sách sản phẩm</h1>

<div class="row">
    @foreach($products as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100">

                <div style="height: 200px; overflow: hidden;">
                    <img src="{{ $product->thumbnail }}" class="card-img-top w-100 h-100" 
                    style="object-fit: cover;" alt="{{ $product->name }}">
                </div>

                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>

                    <p class="text-danger fw-bold">
                        {{ number_format($product->sell_price) }}đ
                    </p>
                    <p class="text-muted mb-1">
                        Đã bán: {{ $product->total_sold ?? 0 }}
                    </p>
                    <button class="btn btn-success w-100 add-to-cart-btn" data-id="{{ $product->id }}">
                        <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                    </button>
                </div>

            </div>
        </div>
    @endforeach
</div>
<div class="d-flex justify-content-center mt-4">
    {{ $products->links('pagination::bootstrap-5') }}
</div>
<style>


.category-list {
    display: flex;
    justify-content: space-between; 
    align-items: center;
}

.category-item {
    width: 100px;
    text-align: center;
}

.category-item img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #eee;
    padding: 5px;
    background: #fff;
}
.category-item:hover {
    color: #ee4d2d;
     transform: translateY(-5px);
    transition: 0.3s;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tìm tất cả các nút Thêm vào giỏ hàng
    const addBtnList = document.querySelectorAll('.add-to-cart-btn');

    addBtnList.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            let productId = this.getAttribute('data-id');
            let btn = this;
            let originalText = btn.innerHTML;

            // 1. Hiệu ứng lúc đang bấm (Loading)
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang xử lý...';
            btn.disabled = true;

            // 2. Gửi lệnh ngầm qua Ajax (Fetch API)
            fetch('{{ route('cart.add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // 3. Cập nhật con số trên giỏ hàng cái BÍP
                    document.querySelector('.cart-count').innerText = data.cart_count;
                    
                    // 4. Hiệu ứng thêm thành công (Đổi sang màu vàng)
                    btn.innerHTML = '<i class="bi bi-check-lg"></i> Đã thêm';
                    btn.classList.replace('btn-success', 'btn-warning');

                    // 5. Trả lại nút bình thường sau 1.5 giây để khách bấm tiếp
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.classList.replace('btn-warning', 'btn-success');
                        btn.disabled = false;
                    }, 1500);
                }
            })
            .catch(error => {
                console.error('Lỗi:', error);
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        });
    });
});
</script>
@endsection