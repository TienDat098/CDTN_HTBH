@extends('layouts.app')

@section('title', $product->name . ' - Web Tạp Hóa')

@section('content')
<div class="container py-5">
    <div class="row bg-white p-4 shadow-sm rounded-3">
        <div class="col-md-5 text-center">
            <img src="{{ $product->thumbnail }}" class="img-fluid rounded border" alt="{{ $product->name }}" id="main-image" style="max-height: 400px; object-fit: contain;">
        </div>

        <div class="col-md-7">
            <h3 class="fw-bold text-uppercase">{{ $product->name }}</h3>
            <p class="mb-2">Thương hiệu: <span class="text-danger fw-bold">{{ $product->brand->name ?? 'Đang cập nhật' }}</span> | Tình trạng: <span class="text-danger fw-bold">Còn hàng</span></p>
            <p class="text-muted small">Mã vạch: {{ $product->barcode }}</p>

            <h2 class="text-danger fw-bold my-4" id="display-price">{{ number_format($product->sell_price) }}đ</h2>

            <form id="add-to-cart-form">
                <input type="hidden" id="product_id" value="{{ $product->id }}">
                
                @if($product->variants->count() > 0)
                    <div class="mb-4">
                        <label class="fw-bold mb-2">Chọn Giá Cả Theo Nhu Cầu</label>
                        <div class="d-flex flex-wrap gap-2">
                            
                            <input type="radio" class="btn-check variant-radio" name="variant_id" id="variant_0" value="" data-price="{{ $product->sell_price }}" checked>
                            <label class="btn btn-outline-warning text-dark px-4 py-2" for="variant_0">Bán lẻ ({{ $product->unit }})</label>

                            @foreach($product->variants as $variant)
                                <input type="radio" class="btn-check variant-radio" name="variant_id" id="variant_{{ $variant->id }}" value="{{ $variant->id }}" data-price="{{ $variant->price }}">
                                <label class="btn btn-outline-warning text-dark px-4 py-2" for="variant_{{ $variant->id }}">{{ $variant->name }}</label>
                            @endforeach

                        </div>
                    </div>
                @endif

                <div class="d-flex align-items-center mb-4 mt-3">
                    <div class="input-group border border-warning rounded" style="width: 140px;">
                        <button class="btn btn-light border-0 fw-bold fs-5" type="button" onclick="changeQty(-1)">-</button>
                        <input type="number" class="form-control border-0 text-center fw-bold fs-5" id="quantity" value="1" min="1" readonly>
                        <button class="btn btn-light border-0 fw-bold fs-5" type="button" onclick="changeQty(1)">+</button>
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <button type="button" class="btn btn-danger btn-lg fw-bold px-4 py-3 shadow-sm rounded-3" onclick="addToCart(false)">
                        <i class="bi bi-cart-plus me-2"></i>THÊM VÀO GIỎ
                    </button>
                    <button type="button" class="btn btn-warning btn-lg fw-bold px-5 py-3 shadow-sm rounded-3" onclick="addToCart(true)">
                        MUA NGAY
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .btn-check:checked + .btn-outline-warning {
        background-color: #ffc107;
        color: #000 !important;
        border-color: #ffc107;
        font-weight: bold;
    }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; margin: 0; 
    }
</style>

<script>
    // 1. Logic đổi giá tiền khi bấm Lốc/Thùng
    const radios = document.querySelectorAll('.variant-radio');
    const priceDisplay = document.getElementById('display-price');

    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            let price = parseInt(this.getAttribute('data-price'));
            // Cập nhật giá màu đỏ to
            priceDisplay.innerText = price.toLocaleString('vi-VN') + 'đ';
        });
    });

    // 2. Logic tăng giảm số lượng mượt mà
    function changeQty(amount) {
        let qtyInput = document.getElementById('quantity');
        let currentVal = parseInt(qtyInput.value);
        if(currentVal + amount >= 1) {
            qtyInput.value = currentVal + amount;
        }
    }

    // 3. Logic Gửi dữ liệu qua Giỏ hàng (Có kèm Biến thể)
    function addToCart(isBuyNow) {
        let productId = document.getElementById('product_id').value;
        let qty = document.getElementById('quantity').value;
        
        // Tìm xem khách đang chọn Bịch hay Thùng
        let selectedVariant = document.querySelector('.variant-radio:checked');
        let variantId = selectedVariant ? selectedVariant.value : null;

        fetch('{{ route('cart.add') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ 
                product_id: productId,
                variant_id: variantId, // Gửi lên Controller chìa khóa này
                quantity: qty
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                // Nảy số trên giỏ hàng góc phải
                document.querySelector('.cart-count').innerText = data.cart_count;
                
                if(isBuyNow) {
                    // Nếu bấm MUA NGAY -> Nhảy sang trang Giỏ Hàng luôn
                    window.location.href = "{{ route('cart.index') }}"; 
                } else {
                    alert(data.message); // Thêm bình thường thì báo thành công
                }
            }
        });
    }
</script>
@endsection