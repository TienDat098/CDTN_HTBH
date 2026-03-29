@extends('layouts.app')

@section('title', $product->name . ' - Web Tạp Hóa')

@section('content')
<div class="container py-4 mb-5">
    <div class="bg-white p-4 shadow-sm rounded-3">
        
        <!-- PHẦN HEADER SẢN PHẨM -->
        <div class="mb-4 border-bottom pb-3">
            <h3 class="fw-bold text-uppercase mb-2">{{ $product->name }}</h3>
            <div class="d-flex flex-wrap gap-4 text-muted small">
                <span>Mã vạch: {{ $product->barcode ?? 'Đang cập nhật' }}</span>
                <span>Thương hiệu: <span class="text-danger fw-bold">{{ $product->brand->name ?? 'Đang cập nhật' }}</span></span>
                <span>Tình trạng: 
                    @if($product->stock && $product->stock->quantity > 0)
                        <span class="text-danger fw-bold">Còn hàng</span>
                    @else
                        <span class="text-muted fw-bold">Hết hàng</span>
                    @endif
                </span>
            </div>
        </div>

        <div class="row position-relative">
            <!-- CỘT TRÁI: HÌNH ẢNH & ZOOM -->
            <div class="col-md-5">
                <div class="img-zoom-container position-relative mb-3 text-center border rounded" id="image-container">
                    <img id="main-image" src="{{ $product->thumbnail }}" class="img-fluid rounded" alt="{{ $product->name }}">
                    <div id="zoom-lens" class="img-zoom-lens"></div>
                </div>

                <!-- ĐÃ SỬA LỖI TRÙNG ẢNH: Chỉ dùng 1 vòng lặp duy nhất -->
                <div class="d-flex gap-2 overflow-auto py-2 px-1">
                    @foreach($images as $index => $img)
                        @php 
                            // Xử lý link ảnh cho chuẩn
                            $imgPath = str_starts_with($img->image_url, 'http') ? $img->image_url : asset('storage/' . $img->image_url);
                        @endphp
                        <img src="{{ $imgPath }}" class="thumbnail-img {{ $index == 0 ? 'active' : '' }} rounded border" onclick="changeImage('{{ $imgPath }}', this)">
                    @endforeach
                </div>
            </div>

            <!-- CỬA SỔ ZOOM KẾT QUẢ -->
            <div id="zoom-result" class="img-zoom-result shadow-lg border bg-white rounded"></div>

            <!-- CỘT PHẢI: GIÁ, BIẾN THỂ & KHO -->
            <div class="col-md-7 ps-md-4">
                <h2 class="text-danger fw-bold mb-2" id="display-price">{{ number_format($product->sell_price) }}đ</h2>
                
                <div class="mb-4 text-success fw-bold fs-6" id="stock-container">
                    <i class="bi bi-check2"></i> 
                    Kho còn: <span id="display-stock">{{ $product->stock ? $product->stock->quantity : 0 }}</span> sản phẩm
                </div>

                <form id="add-to-cart-form">
                    <input type="hidden" id="product_id" value="{{ $product->id }}">
                    
                    @if($product->variants->count() > 0)
                        <div class="mb-4">
                            <label class="fw-bold mb-2">Chọn Phân Loại</label>
                            <div class="d-flex flex-wrap gap-2">
                                <!-- Option Bán lẻ (Gốc) -->
                                <input type="radio" class="btn-check variant-radio" name="variant_id" id="variant_0" value="" 
                                    data-price="{{ $product->sell_price }}" 
                                    data-stock="{{ $product->stock ? $product->stock->quantity : 0 }}" checked>
                                <label class="btn btn-outline-warning text-dark px-4 py-2" for="variant_0">Bán lẻ ({{ $product->unit }})</label>

                                <!-- Các biến thể (Lốc, Thùng...) -->
                                @foreach($product->variants as $variant)
                                    <input type="radio" class="btn-check variant-radio" name="variant_id" id="variant_{{ $variant->id }}" value="{{ $variant->id }}" 
                                        data-price="{{ $variant->price }}"
                                        data-stock="{{ $variant->stock_quantity }}"> <!-- Lấy thẳng tồn kho riêng từ DB -->
                                    <label class="btn btn-outline-warning text-dark px-4 py-2" for="variant_{{ $variant->id }}">{{ $variant->name }}</label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="d-flex align-items-center mb-4 mt-3">
                        <label class="fw-bold me-3">Số lượng:</label>
                        <div class="input-group border border-warning rounded" style="width: 140px;">
                            <button class="btn btn-light border-0 fw-bold fs-5" type="button" onclick="changeQty(-1)">-</button>
                            <input type="number" class="form-control border-0 text-center fw-bold fs-5" id="quantity" value="1" min="1" readonly>
                            <button class="btn btn-light border-0 fw-bold fs-5" type="button" onclick="changeQty(1)">+</button>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="button" class="btn btn-danger btn-lg fw-bold px-4 py-3 shadow-sm rounded-3 w-50" onclick="addToCart(false)" id="btn-add-cart">
                            <i class="bi bi-cart-plus me-2"></i>THÊM VÀO GIỎ
                        </button>
                        <button type="button" class="btn btn-warning btn-lg fw-bold px-4 py-3 shadow-sm rounded-3 w-50" onclick="addToCart(true)" id="btn-buy-now">
                            MUA NGAY
                        </button>
                    </div>
                </form>

                <div class="mt-5 pt-4 border-top">
                    <h5 class="fw-bold mb-3">Mô tả sản phẩm</h5>
                    <div class="text-muted lh-lg">
                        {!! nl2br(e($product->description ?? 'Đang cập nhật mô tả cho sản phẩm này.')) !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    .btn-check:checked + .btn-outline-warning { background-color: #ffc107; color: #000 !important; border-color: #ffc107; font-weight: bold; }
    input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    .thumbnail-img { width: 70px; height: 70px; object-fit: cover; cursor: pointer; opacity: 0.6; transition: 0.2s; }
    .thumbnail-img:hover { opacity: 1; }
    .thumbnail-img.active { opacity: 1; border: 2px solid #ef1c1c !important; }
    .img-zoom-container { cursor: crosshair; }
    #main-image { max-height: 450px; width: 100%; object-fit: contain; }
    .img-zoom-result { position: absolute; top: 0; left: 45%; width: 500px; height: 450px; background-color: #fff; z-index: 999; display: none; background-repeat: no-repeat; pointer-events: none; }
    .img-zoom-lens { position: absolute; border: 1px solid #d4d4d4; background-color: rgba(255, 255, 255, 0.4); width: 150px; height: 150px; display: none; pointer-events: none; }
</style>

<script>
    function changeImage(src, element) {
        document.getElementById('main-image').src = src;
        document.querySelectorAll('.thumbnail-img').forEach(img => img.classList.remove('active'));
        element.classList.add('active');
        setupZoom();
    }

    function setupZoom() {
        const img = document.getElementById("main-image");
        const result = document.getElementById("zoom-result");
        const lens = document.getElementById("zoom-lens");
        const container = document.getElementById("image-container");
        result.style.backgroundImage = "url('" + img.src + "')";
        let cx, cy;

        container.addEventListener("mouseenter", function() {
            lens.style.display = "block";
            result.style.display = "block";
            cx = result.offsetWidth / lens.offsetWidth;
            cy = result.offsetHeight / lens.offsetHeight;
            result.style.backgroundSize = (img.width * cx) + "px " + (img.height * cy) + "px";
        });
        container.addEventListener("mouseleave", function() {
            lens.style.display = "none";
            result.style.display = "none";
        });
        container.addEventListener("mousemove", moveLens);
        container.addEventListener("touchmove", moveLens);

        function moveLens(e) {
            let pos, x, y;
            e.preventDefault();
            pos = getCursorPos(e);
            x = pos.x - (lens.offsetWidth / 2);
            y = pos.y - (lens.offsetHeight / 2);
            if (x > img.width - lens.offsetWidth) { x = img.width - lens.offsetWidth; }
            if (x < 0) { x = 0; }
            if (y > img.height - lens.offsetHeight) { y = img.height - lens.offsetHeight; }
            if (y < 0) { y = 0; }
            lens.style.left = x + "px";
            lens.style.top = y + "px";
            result.style.backgroundPosition = "-" + (x * cx) + "px -" + (y * cy) + "px";
        }
        function getCursorPos(e) {
            let a, x = 0, y = 0;
            e = e || window.event;
            a = img.getBoundingClientRect();
            x = e.pageX - a.left - window.pageXOffset;
            y = e.pageY - a.top - window.pageYOffset;
            return {x : x, y : y};
        }
    }

    window.onload = function() { setupZoom(); };

    // --- LOGIC ĐỔI GIÁ & TỒN KHO KHI CLICK BIẾN THỂ ---
    const radios = document.querySelectorAll('.variant-radio');
    const priceDisplay = document.getElementById('display-price');
    const stockDisplay = document.getElementById('display-stock');
    const stockContainer = document.getElementById('stock-container');
    const qtyInput = document.getElementById('quantity');
    const btnAddCart = document.getElementById('btn-add-cart');
    const btnBuyNow = document.getElementById('btn-buy-now');

    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            let price = parseInt(this.getAttribute('data-price'));
            priceDisplay.innerText = price.toLocaleString('vi-VN') + 'đ';
            
            let stock = parseInt(this.getAttribute('data-stock'));
            stockDisplay.innerText = stock;
            
            // Xử lý Giao diện Nút bấm khi Hết Hàng
            if (stock <= 0) {
                qtyInput.value = 0;
                stockContainer.className = "mb-4 text-danger fw-bold fs-6";
                stockContainer.innerHTML = `<i class="bi bi-x-circle"></i> Sản phẩm phân loại này đã hết hàng`;
                
                btnAddCart.disabled = true;
                btnBuyNow.disabled = true;
                btnAddCart.innerHTML = "HẾT HÀNG";
            } else {
                qtyInput.value = 1;
                stockContainer.className = "mb-4 text-success fw-bold fs-6";
                stockContainer.innerHTML = `<i class="bi bi-check2"></i> Kho còn: <span id="display-stock">${stock}</span> sản phẩm`;
                
                btnAddCart.disabled = false;
                btnBuyNow.disabled = false;
                btnAddCart.innerHTML = `<i class="bi bi-cart-plus me-2"></i>THÊM VÀO GIỎ`;
            }
        });
    });

    if(radios.length > 0) {
        radios[0].dispatchEvent(new Event('change'));
    }

    function changeQty(amount) {
        let currentVal = parseInt(qtyInput.value);
        let maxStockElement = document.getElementById('display-stock');
        
        if(!maxStockElement) return; // Nếu đang hết hàng (thẻ span bị xóa) thì không làm gì
        
        let maxStock = parseInt(maxStockElement.innerText);
        if (maxStock <= 0) return;

        let newVal = currentVal + amount;
        
        if(newVal >= 1 && newVal <= maxStock) { 
            qtyInput.value = newVal; 
        } else if (newVal > maxStock) {
            alert('Xin lỗi, số lượng bạn chọn đã vượt quá tồn kho hiện tại!');
        }
    }

    function addToCart(isBuyNow) {
        let productId = document.getElementById('product_id').value;
        let qty = parseInt(document.getElementById('quantity').value);
        let selectedVariant = document.querySelector('.variant-radio:checked');
        let variantId = selectedVariant && selectedVariant.value !== "" ? selectedVariant.value : null;

        if (qty <= 0) return;

        fetch('{{ route('cart.add') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ product_id: productId, variant_id: variantId, quantity: qty })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                document.querySelector('.cart-count').innerText = data.cart_count;
                if(isBuyNow) {
                    window.location.href = "{{ route('cart.index') }}"; 
                } else {
                    alert(data.message);
                }
            }
        });
    }
</script>
@endsection