@extends('admin.layouts.app')

@section('title','Thêm sản phẩm')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3>Thêm sản phẩm</h3>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
         Quay lại
    </a>
</div>
@if ($errors->any())
    <div class="alert alert-danger shadow-sm border-0 border-start border-5 border-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="bg-white p-4 shadow-sm rounded">
    @csrf

    <div class="row">
        <div class="col-md-12 mb-3">
            <label class="fw-bold">Mã vạch (Barcode) <span class="text-danger">*</span></label>
            <div class="input-group">
                <input type="text" id="barcode-input" name="barcode" class="form-control @error('barcode') is-invalid @enderror" value="{{ old('barcode') }}" required>
                    @error('barcode')
                        <div class="invalid-feedback fw-bold">{{ $message }}</div>
                    @enderror
                <button type="button" class="btn btn-outline-primary" id="btn-scan-barcode" onclick="startCamera()">
                    <i class="bi bi-camera-video"></i> Quét mã
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="generateBarcode()">
                    <i class="bi bi-magic"></i> Tạo ngẫu nhiên
                </button>
            </div>
            <div id="reader" class="mt-2 d-none"></div>
        </div>

        <div class="col-md-6 mb-3">
            <label>Danh mục</label>
            <select name="category_id" class="form-control">
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label>Thương hiệu</label>
            <select name="brand_id" class="form-control">
                @foreach($brands as $brand)
                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3"><label>Tên sản phẩm</label><input type="text" name="name" class="form-control" required></div>
        <div class="col-md-3 mb-3"><label>Đơn vị (VD: Gói)</label><input type="text" name="unit" class="form-control" required></div>
        <div class="col-md-3 mb-3"><label>Trạng thái</label>
            <select name="status" class="form-control">
                <option value="1">Hiển thị</option>
                <option value="0">Ẩn</option>
            </select>
        </div>

        <div class="col-md-4 mb-3"><label>Giá nhập (Gốc)</label><input type="number" name="import_price" class="form-control" required></div>
        <div class="col-md-4 mb-3"><label>Giá bán (Gốc)</label><input type="number" name="sell_price" class="form-control" required></div>
        <div class="col-md-4 mb-3"><label>Số lượng kho (Gốc)</label><input type="number" name="quantity" class="form-control" required></div>

        <div class="col-md-12 mb-3"><label>Ảnh chính</label><input type="file" name="image" class="form-control"></div>
        <div class="col-md-12 mb-4"><label>Mô tả</label><textarea name="description" class="form-control" rows="3"></textarea></div>
    </div>

    <div class="card border-primary mb-4">
        <div class="card-header bg-primary text-white fw-bold">
            <i class="bi bi-tags"></i> Phân loại / Biến thể (Tùy chọn)
        </div>
        <div class="card-body bg-light">
            <p class="text-muted small mb-3">Thêm các phân loại như Lốc, Thùng... Nếu sản phẩm này chỉ có 1 mức giá bán lẻ, hãy bỏ qua khu vực này.</p>
            
            <div id="variants-container"></div>
            
            <button type="button" class="btn btn-outline-primary btn-sm mt-2 fw-bold" onclick="addVariant()">
                <i class="bi bi-plus-circle"></i> Thêm phân loại
            </button>
        </div>
    </div>

    <button class="btn btn-success btn-lg px-5">Lưu sản phẩm</button>
</form>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    // 1. Hàm tạo mã ngẫu nhiên cho hàng không có tem
    function generateBarcode() {
        let randomCode = '8' + Math.floor(Math.random() * 90000000000 + 10000000000);
        document.getElementById('barcode-input').value = randomCode;
    }

    // 2. Logic bật/tắt Camera
    let html5QrcodeScanner;

    function startCamera() {
        const readerDiv = document.getElementById('reader');
        const btnScan = document.getElementById('btn-scan-barcode');

        if (readerDiv.classList.contains('d-none')) {
            readerDiv.classList.remove('d-none');
            btnScan.innerHTML = '<i class="bi bi-camera-video-off"></i> Tắt Camera';
            btnScan.classList.replace('btn-outline-primary', 'btn-outline-danger');

            html5QrcodeScanner = new Html5Qrcode("reader");
            const config = { fps: 10, qrbox: { width: 250, height: 150 } };

            html5QrcodeScanner.start({ facingMode: "environment" }, config, onScanSuccess)
            .catch(err => {
                alert("Lỗi: Không thể truy cập Camera. Vui lòng cấp quyền!");
            });
        } else {
            stopCamera();
        }
    }

    function stopCamera() {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.stop().then(() => {
                document.getElementById('reader').classList.add('d-none');
                let btnScan = document.getElementById('btn-scan-barcode');
                btnScan.innerHTML = '<i class="bi bi-camera-video"></i> Quét bằng Camera';
                btnScan.classList.replace('btn-outline-danger', 'btn-outline-primary');
            });
        }
    }

    // 3. Hàm chạy khi Camera quét trúng mã vạch
    function onScanSuccess(decodedText, decodedResult) {
        stopCamera(); 
        // Bắn dãy số vào ô input
        document.getElementById('barcode-input').value = decodedText;
        alert('Đã quét thành công mã: ' + decodedText);
    }


    // --- KHU VỰC XỬ LÝ BIẾN THỂ ---
    let variantIndex = 0;
    function addVariant() {
        const html = `
        <div class="row align-items-end mb-3 pb-3 border-bottom variant-row" id="variant-${variantIndex}">
            <div class="col-md-3">
                <label class="form-label small">Tên (VD: Thùng 24 Gói)</label>
                <input type="text" name="variants[${variantIndex}][name]" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Mã vạch riêng</label>
                <input type="text" name="variants[${variantIndex}][barcode]" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Giá bán</label>
                <input type="number" name="variants[${variantIndex}][price]" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Tồn kho</label>
                <input type="number" name="variants[${variantIndex}][stock_quantity]" class="form-control" value="0">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100" onclick="removeVariant(${variantIndex})">
                    <i class="bi bi-trash"></i> Xóa
                </button>
            </div>
        </div>`;
        document.getElementById('variants-container').insertAdjacentHTML('beforeend', html);
        variantIndex++;
    }
    function removeVariant(index) {
        document.getElementById(`variant-${index}`).remove();
    }
</script>
@endsection