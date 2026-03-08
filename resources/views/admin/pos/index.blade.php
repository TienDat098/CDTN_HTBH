@extends('admin.layouts.app')

@section('title', 'Bán hàng tại quầy (POS)')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h3 class="fw-bold mb-0"><i class="bi bi-shop"></i> Bán Hàng Tại Quầy (POS)</h3>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Thoát POS
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7 col-lg-8">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body bg-light">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-white"><i class="bi bi-upc-scan"></i></span>
                        <input type="text" id="barcode-input" class="form-control" placeholder="Quét mã vạch hoặc nhập tên sản phẩm..." autofocus>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body" style="height: 65vh; overflow-y: auto; background-color: #f8f9fa;">
                    <div class="row g-3" id="product-list">
                        @foreach($products as $product)
                            @php 
                                $stock = $product->stock->quantity ?? 0;
                                $isOutOfStock = $stock <= 0;
                            @endphp

                            <div class="col-6 col-md-4 col-xl-3">
                                <div class="card h-100 border-0 shadow-sm product-card {{ $isOutOfStock ? 'opacity-50' : '' }}" 
                                     style="cursor: {{ $isOutOfStock ? 'not-allowed' : 'pointer' }};"
                                     onclick="{{ $isOutOfStock ? '' : "addToCart({$product->id}, '{$product->name}', {$product->sell_price}, {$stock})" }}">
                                    
                                    <img src="{{ $product->thumbnail }}" class="card-img-top" style="height: 120px; object-fit: cover;">
                                    
                                    <div class="card-body p-2 text-center d-flex flex-column justify-content-between">
                                        <h6 class="card-title fw-bold mb-1" style="font-size: 0.9rem;">{{ $product->name }}</h6>
                                        <p class="text-danger fw-bold mb-1">{{ number_format($product->sell_price) }}đ</p>
                                        <small class="text-muted">Kho: <span class="fw-bold {{ $stock <= 5 ? 'text-danger' : '' }}">{{ $stock }}</span></small>
                                    </div>
                                    
                                    @if($isOutOfStock)
                                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(255,255,255,0.7);">
                                            <span class="badge bg-danger fs-6 px-3 py-2">Hết hàng</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5 col-lg-4">
            <div class="card shadow-sm border-0 h-100 d-flex flex-column">
                <div class="card-header bg-white py-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <select class="form-select" id="customer-select">
                            <option value="">Khách vãng lai</option>
                            </select>
                        <button class="btn btn-outline-primary"><i class="bi bi-plus"></i></button>
                    </div>
                </div>

                <div class="card-body p-0" style="flex-grow: 1; overflow-y: auto; height: 40vh;" id="pos-cart-container">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Sản phẩm</th>
                                <th class="text-center" width="110">SL</th>
                                <th class="text-end">Thành tiền</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="cart-items">
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="bi bi-cart-x fs-1 d-block mb-2"></i>
                                    Chưa có sản phẩm nào
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white border-top-0 p-3 shadow-lg">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fs-5 fw-bold text-secondary">Tổng số lượng:</span>
                        <span class="fs-5 fw-bold" id="total-qty">0</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fs-4 fw-bold text-dark">Tổng tiền:</span>
                        <span class="fs-3 fw-bold text-danger" id="total-price">0đ</span>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-success btn-lg py-3 fw-bold fs-5" id="btn-checkout">
                            <i class="bi bi-cash-coin"></i> THANH TOÁN
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    
    .product-card { transition: transform 0.2s, box-shadow 0.2s; border: 1px solid #e9ecef !important;}
    .product-card:hover { transform: translateY(-3px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; border-color: #0d6efd !important;}
    /* Ẩn thanh cuộn nhưng vẫn cuộn được */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: #999; }
</style>
<script>
    // Mảng chứa các sản phẩm trong hóa đơn
    let posCart = [];

    //  Hàm thêm sản phẩm vào giỏ (Kích hoạt khi click vào thẻ sản phẩm)
    function addToCart(id, name, price, maxStock) {
        // Tìm xem món này đã có bên tờ hóa đơn chưa
        let existingItem = posCart.find(item => item.id === id);

        if (existingItem) {
            // Có rồi thì cộng thêm 1 (nếu kho còn đủ)
            if (existingItem.qty < maxStock) {
                existingItem.qty++;
            } else {
                alert('Cảnh báo: Không đủ số lượng trong kho!');
            }
        } else {
            // Chưa có thì thêm dòng mới vào hóa đơn
            if (maxStock > 0) {
                posCart.push({ id: id, name: name, price: price, qty: 1, maxStock: maxStock });
            }
        }
        renderCart(); // Gọi hàm vẽ lại giao diện
    }

    //  Hàm vẽ lại tờ hóa đơn bên phải
    function renderCart() {
        let cartHtml = '';
        let totalQty = 0;
        let totalPrice = 0;

        if (posCart.length === 0) {
            cartHtml = `<tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                <i class="bi bi-cart-x fs-1 d-block mb-2"></i>
                                Chưa có sản phẩm nào
                            </td>
                        </tr>`;
        } else {
            // Duyệt qua từng món để tạo HTML
            posCart.forEach((item) => {
                let itemTotal = item.price * item.qty;
                totalQty += item.qty;
                totalPrice += itemTotal;

                cartHtml += `
                    <tr>
                        <td>
                            <div class="fw-bold text-truncate" style="max-width: 150px; font-size: 0.9rem;" title="${item.name}">${item.name}</div>
                            <small class="text-danger">${item.price.toLocaleString('vi-VN')}đ</small>
                        </td>
                        <td class="text-center">
                            <div class="input-group input-group-sm" style="width: 90px; margin: 0 auto;">
                                <button class="btn btn-outline-secondary px-2" type="button" onclick="changeQty(${item.id}, -1)">-</button>
                                <input type="number" min="1" class="form-control text-center px-0" value="${item.qty}" onchange="updateQty(${item.id}, this.value)">
                                <button class="btn btn-outline-secondary px-2" type="button" onclick="changeQty(${item.id}, 1)">+</button>
                            </div>
                        </td>
                        <td class="text-end fw-bold text-danger">
                            ${itemTotal.toLocaleString('vi-VN')}đ
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm text-danger border-0" onclick="removeItem(${item.id})">
                                <i class="bi bi-trash fs-5"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
        }

        // Đổ dữ liệu ra màn hình
        document.getElementById('cart-items').innerHTML = cartHtml;
        document.getElementById('total-qty').innerText = totalQty;
        document.getElementById('total-price').innerText = totalPrice.toLocaleString('vi-VN') + 'đ';
    }

    //  Hàm xử lý nút [+] và [-] trên hóa đơn
    function changeQty(id, delta) {
        let item = posCart.find(item => item.id === id);
        if (item) {
            let newQty = item.qty + delta;
            if (newQty > 0 && newQty <= item.maxStock) {
                item.qty = newQty;
            } else if (newQty > item.maxStock) {
                alert('Trong kho chỉ còn ' + item.maxStock + ' sản phẩm!');
            } else if (newQty <= 0) {
                removeItem(id); // Nếu giảm về 0 thì tự động xóa món
                return; 
            }
        }
        renderCart();
    }
    //  Hàm xử lý khi thu ngân GÕ TRỰC TIẾP số lượng vào ô
    function updateQty(id, newQty) {
        let item = posCart.find(item => item.id === id);
        if (item) {
            let parsedQty = parseInt(newQty);

            // Kiểm tra nếu nhập chữ hoặc số âm/số 0
            if (isNaN(parsedQty) || parsedQty <= 0) {
                alert('Số lượng không hợp lệ! Đã quay về 1.');
                item.qty = 1;
            } 
            // Kiểm tra nếu gõ vượt quá số lượng trong kho
            else if (parsedQty > item.maxStock) {
                alert('Trong kho chỉ còn ' + item.maxStock + ' sản phẩm!');
                item.qty = item.maxStock; // Tự động set bằng số lượng tối đa kho đang có
            } 
            // Nếu gõ đúng chuẩn
            else {
                item.qty = parsedQty;
            }
        }
        renderCart(); // Vẽ lại giao diện để cập nhật lại tổng tiền
    }

    // Hàm xóa hẳn 1 món khỏi hóa đơn
    function removeItem(id) {
        posCart = posCart.filter(item => item.id !== id);
        renderCart();
    }

    //  Hàm xử lý khi bấm nút THANH TOÁN
    document.getElementById('btn-checkout').addEventListener('click', function() {
        if (posCart.length === 0) {
            alert('Giỏ hàng đang trống! Vui lòng chọn sản phẩm.');
            return;
        }

        // Hỏi lại thu ngân cho chắc chắn
        if (!confirm('Xác nhận thanh toán đơn hàng này?')) {
            return;
        }

        // Đổi chữ trên nút để báo đang xử lý
        let btn = this;
        let originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang xử lý...';
        btn.disabled = true;

        // Gửi dữ liệu giỏ hàng lên Server qua AJAX
        fetch('{{ route("admin.pos.checkout") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Token bảo mật bắt buộc của Laravel
            },
            body: JSON.stringify({ cart: posCart })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(' ' + data.message);
                window.location.reload(); // Tải lại trang để cập nhật lại số lượng kho mới
            } else {
                alert(' ' + data.message);
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        })
        .catch(error => {
            alert('Lỗi kết nối Server!');
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    });
</script>
@endsection