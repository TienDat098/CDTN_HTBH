@extends('layouts.app')

@section('title', 'Thanh toán - Chuỗi Tạp Hóa Việt')

@section('content')
<div class="checkout-page bg-white">
    <div class="container py-4">
        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf
            <div class="row g-5">
                
                <div class="col-lg-7">
                    <h2 class="fw-bold mb-2">Chuỗi tạp hóa Việt</h2>
                    
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb small">
                            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none">Giỏ hàng</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Thông tin giao hàng</li>
                        </ol>
                    </nav>

                    <h5 class="fw-bold mb-3">Thông tin giao hàng</h5>
                    
                    @guest
                        <p class="mb-3 small">Bạn đã có tài khoản? <a href="{{ route('login') }}" class="text-decoration-none">Đăng nhập</a></p>
                    @endguest

                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <input type="text" name="customer_name" class="form-control form-control-lg @error('customer_name') is-invalid @enderror" 
                                   value="{{ auth()->check() ? auth()->user()->name : old('customer_name') }}" 
                                   placeholder="Họ và tên" required>
                            @error('customer_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-7">
                            <input type="email" name="email" class="form-control form-control-lg" 
                                   value="{{ auth()->check() ? auth()->user()->email : old('email') }}" 
                                   placeholder="Email">
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="phone" class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                                   value="{{ auth()->check() ? auth()->user()->phone : old('phone') }}" 
                                   placeholder="Số điện thoại" required>
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="border rounded mb-4">
                        <div class="p-3 border-bottom bg-light d-flex align-items-center">
                            <input class="form-check-input mt-0 me-2" type="radio" name="delivery_type" id="delivery1" checked>
                            <label class="form-check-label fw-bold" for="delivery1">Giao tận nơi</label>
                        </div>
                        <div class="p-3">
                            <div class="mb-3">
                                <input type="text" id="street_address" class="form-control form-control-lg" 
                                    placeholder="Địa chỉ cụ thể (Số nhà, Tên đường...)" required>
                                
                                <input type="hidden" name="shipping_address" id="full_shipping_address" 
                                    value="{{ auth()->check() ? auth()->user()->address : old('shipping_address') }}">
                                
                                @error('shipping_address') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-md-4">
                                    <select id="province" class="form-select form-select-lg text-muted" required>
                                        <option value="" selected disabled>Tỉnh / thành</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-4">
                                    <select id="district" class="form-select form-select-lg text-muted" disabled required>
                                        <option value="" selected disabled>Quận / huyện</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <select id="ward" class="form-select form-select-lg text-muted" disabled required>
                                        <option value="" selected disabled>Phường / xã</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 border-top d-flex align-items-center">
                            <input class="form-check-input mt-0 me-2" type="radio" name="delivery_type" id="delivery2">
                            <label class="form-check-label" for="delivery2">Nhận tại cửa hàng</label>
                        </div>
                    </div>

                    

                    <h5 class="fw-bold mb-3">Phương thức thanh toán</h5>
                    
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="border rounded mb-4">
                        <div class="p-3 border-bottom d-flex align-items-center bg-light">
                            <input class="form-check-input mt-0 me-3" type="radio" name="payment_method" id="cod" value="cod" checked>
                            <i class="bi bi-cash-stack text-success fs-4 me-2"></i>
                            <label class="form-check-label" for="cod" style="cursor: pointer;">Thanh toán khi giao hàng (COD)</label>
                        </div>
                        
                        <div class="p-3 d-flex align-items-center bg-light">
                            <input class="form-check-input mt-0 me-3" type="radio" name="payment_method" id="payos" value="payos">
                            <i class="bi bi-qr-code-scan text-primary fs-4 me-2"></i>
                            <label class="form-check-label" for="payos" style="cursor: pointer;">Thanh toán chuyển khoản (Mã VietQR)</label>
                        </div>
                        <div class="p-3 bg-white text-center text-muted small border-top" id="payment_desc">
                            Nhận hàng thanh toán tiền mặt trực tiếp với shipper
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('cart.index') }}" class="text-decoration-none text-primary">Giỏ hàng</a>
                        <button type="submit" class="btn btn-primary btn-lg px-5">Hoàn tất đơn hàng</button>
                    </div>
                </div>

                <div class="col-lg-5 p-4 border-start" style="background-color: #fafafa; height: 100vh;">
                    
                    <div class="product-list mb-4">
                        @foreach($cart as $id => $item)
                        <div class="d-flex align-items-center mb-3">
                            <div class="position-relative me-3">
                                <div class="product-thumbnail border bg-white rounded d-flex align-items-center justify-content-center p-1" style="width: 65px; height: 65px;">
                                    <img src="{{ $item['image'] ?? asset('images/no-image.png') }}" class="img-fluid" style="max-height: 100%;" alt="">
                                </div>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary" style="font-size: 0.75rem;">
                                    {{ $item['quantity'] }}
                                </span>
                            </div>
                            
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold fs-6">{{ $item['name'] }}</h6>
                                <small class="text-muted">{{ $item['variant_name'] ?? 'Bán lẻ' }}</small>
                            </div>

                            <div class="fw-bold text-dark">
                                {{ number_format($item['price'] * $item['quantity']) }}đ
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="py-3 border-top border-bottom mb-4">
                        <div class="d-flex gap-2">
                            <input type="text" id="promo_code" class="form-control form-control-lg" placeholder="Mã giảm giá" value="{{ $promotion['code'] ?? '' }}" style="text-transform: uppercase;">
                            <button type="button" id="btn_apply_promo" class="btn btn-secondary px-4 fw-bold">Sử dụng</button>
                        </div>
                        <div id="promo_message" class="mt-2 small">
                            @if(isset($promotion))
                                <span class="text-success fw-bold"><i class="bi bi-check-circle"></i> Đã áp dụng mã thành công!</span>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-2 text-muted">
                        <span>Tạm tính</span>
                        <span>{{ number_format($total) }}đ</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 text-danger {{ $discountAmount > 0 ? '' : 'd-none' }}" id="discount_row">
                        <span>Giảm giá khuyến mãi</span>
                        <span class="fw-bold" id="discount_text">-{{ number_format($discountAmount) }}đ</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center border-top pt-3">
                        <span class="fs-5 fw-bold">Tổng cộng</span>
                        <div>
                            <span class="fs-3 text-dark fw-bold" id="final_total_text">{{ number_format($finalTotal) }}đ</span>
                            <span class="text-muted small ms-1">VND</span>
                        </div>
                    </div>

                </div>

            </div>
        </form>
    </div>
</div>

<style>
    /* Chỉnh sửa css để form đẹp như mẫu */
    .checkout-page { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
    .form-control-lg, .form-select-lg { font-size: 0.95rem; padding: 0.75rem 1rem; border-radius: 4px; box-shadow: none !important; }
    .form-control:focus, .form-select:focus { border-color: #80bdff; }
    .btn-primary { background-color: #338dbc; border-color: #338dbc; border-radius: 4px; }
    .btn-primary:hover { background-color: #2b77a0; border-color: #2b77a0; }
    .text-primary { color: #338dbc !important; }
    .bg-secondary { background-color: #999999 !important; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {

    const radios = document.querySelectorAll('input[name="payment_method"]');
    const descBox = document.getElementById('payment_desc');
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            if(this.value === 'payos') {
                descBox.innerHTML = '<span class="text-primary fw-bold">Hệ thống sẽ chuyển sang trang quét mã QR của ngân hàng. Đơn hàng sẽ tự động duyệt ngay sau khi thanh toán thành công!</span>';
            } else {
                descBox.innerHTML = 'Nhận hàng thanh toán tiền mặt trực tiếp với shipper.';
            }
        });
    });
    const btnApply = document.getElementById('btn_apply_promo');
    
    btnApply.addEventListener('click', function() {
        const code = document.getElementById('promo_code').value.trim();
        const msgBox = document.getElementById('promo_message');
        const originalText = this.innerHTML;

        if(!code) {
            msgBox.innerHTML = '<span class="text-danger fw-bold"><i class="bi bi-exclamation-triangle"></i> Vui lòng nhập mã giảm giá.</span>';
            return;
        }

        // Hiệu ứng Loading nút bấm
        this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        this.disabled = true;

        fetch('{{ route('checkout.apply_promotion') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ code: code })
        })
        .then(response => response.json())
        .then(data => {
            this.innerHTML = originalText;
            this.disabled = false;

            if(data.success) {
                // Thành công: Đổi chữ xanh, hiện cột giảm giá, cập nhật tổng tiền
                msgBox.innerHTML = '<span class="text-success fw-bold"><i class="bi bi-check-circle"></i> ' + data.message + '</span>';
                document.getElementById('discount_row').classList.remove('d-none');
                document.getElementById('discount_text').innerText = '-' + data.discount_amount_formatted;
                document.getElementById('final_total_text').innerText = data.final_total_formatted;
            } else {
                // Thất bại: Báo lỗi đỏ
                msgBox.innerHTML = '<span class="text-danger fw-bold"><i class="bi bi-exclamation-circle"></i> ' + data.message + '</span>';
                
                // Ẩn dòng giảm giá cũ đi và trả lại tổng tiền chưa giảm
                document.getElementById('discount_row').classList.add('d-none');
                if(data.final_total_formatted) {
                    document.getElementById('final_total_text').innerText = data.final_total_formatted;
                }
            }
        })
        .catch(error => {
            this.innerHTML = originalText;
            this.disabled = false;
            console.error('Lỗi API:', error);
        });
    });
});



// ========================================================
    // TÍCH HỢP API ĐỊA CHỈ HÀNH CHÍNH VIỆT NAM
    // ========================================================
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    const streetInput = document.getElementById('street_address');
    const fullAddressInput = document.getElementById('full_shipping_address');

    // 1. Gọi API lấy danh sách Tỉnh/Thành ngay khi load trang
    fetch('https://provinces.open-api.vn/api/p/')
        .then(res => res.json())
        .then(data => {
            data.forEach(p => {
                // Lưu tên tỉnh vào data-name để lát nối chuỗi
                provinceSelect.innerHTML += `<option value="${p.code}" data-name="${p.name}">${p.name}</option>`;
            });
        });

    // 2. Xử lý khi chọn Tỉnh/Thành -> Load danh sách Quận/Huyện
    provinceSelect.addEventListener('change', function() {
        districtSelect.innerHTML = '<option value="" selected disabled>Quận / huyện</option>';
        wardSelect.innerHTML = '<option value="" selected disabled>Phường / xã</option>';
        districtSelect.disabled = false;
        wardSelect.disabled = true;

        fetch(`https://provinces.open-api.vn/api/p/${this.value}?depth=2`)
            .then(res => res.json())
            .then(data => {
                data.districts.forEach(d => {
                    districtSelect.innerHTML += `<option value="${d.code}" data-name="${d.name}">${d.name}</option>`;
                });
            });
        compileFullAddress();
    });

    // 3. Xử lý khi chọn Quận/Huyện -> Load danh sách Phường/Xã
    districtSelect.addEventListener('change', function() {
        wardSelect.innerHTML = '<option value="" selected disabled>Phường / xã</option>';
        wardSelect.disabled = false;

        fetch(`https://provinces.open-api.vn/api/d/${this.value}?depth=2`)
            .then(res => res.json())
            .then(data => {
                data.wards.forEach(w => {
                    wardSelect.innerHTML += `<option value="${w.code}" data-name="${w.name}">${w.name}</option>`;
                });
            });
        compileFullAddress();
    });

    // 4. Lắng nghe sự kiện để nối chuỗi địa chỉ tự động
    wardSelect.addEventListener('change', compileFullAddress);
    streetInput.addEventListener('input', compileFullAddress);

    // 5. Hàm gộp các trường thành 1 chuỗi hoàn chỉnh lưu vào thẻ Input ẩn
    function compileFullAddress() {
        let street = streetInput.value.trim();
        let pName = provinceSelect.options[provinceSelect.selectedIndex]?.dataset?.name || '';
        let dName = districtSelect.options[districtSelect.selectedIndex]?.dataset?.name || '';
        let wName = wardSelect.options[wardSelect.selectedIndex]?.dataset?.name || '';

        let addressParts = [];
        if (street) addressParts.push(street);
        if (wName)  addressParts.push(wName);
        if (dName)  addressParts.push(dName);
        if (pName)  addressParts.push(pName);

        // Nối bằng dấu phẩy. VD: "45 Lê Lợi, Phường Vạn Thạnh, Thành phố Nha Trang, Tỉnh Khánh Hòa"
        fullAddressInput.value = addressParts.join(', ');
    }
</script>
@endsection