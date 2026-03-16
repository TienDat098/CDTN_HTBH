@extends('admin.layouts.app')

@section('title','Thêm sản phẩm')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3>Thêm sản phẩm</h3>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
         Quay lại
    </a>
</div>
<form method="POST"
action="{{ route('admin.products.store') }}"
enctype="multipart/form-data">

        @csrf
            <label class="fw-bold mt-2">Mã vạch (Barcode) <span class="text-danger">*</span></label>
            <div class="input-group mb-3">
                <input type="text" id="barcode-input" name="barcode" class="form-control" placeholder="Quét mã vạch hoặc nhập tay..." required>
                <button type="button" class="btn btn-outline-primary" id="btn-scan-barcode" onclick="startCamera()">
                    <i class="bi bi-camera-video"></i> Quét bằng Camera
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="generateBarcode()">
                    <i class="bi bi-magic"></i> Tạo ngẫu nhiên
                </button>
            </div>

            <div id="reader" class="mb-3 d-none" style="width: 100%; max-width: 500px; border-radius: 8px; overflow: hidden; margin: 0 auto;"></div>
            
            <label>Danh mục</label>
            <select name="category_id" class="form-control">
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
            </select>

            <label>Thương hiệu</label>
            <select name="brand_id" class="form-control">
            @foreach($brands as $brand)
            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
            @endforeach
            </select>

        <label>Tên</label>
        <input type="text" name="name" class="form-control">
        <label>Giá nhập</label>
        <input type="number" name="import_price" class="form-control">
        <label>Giá bán</label>
        <input type="number" name="sell_price" class="form-control">
        <label>Đơn vị</label>
        <input type="text" name="unit" class="form-control">
        <label>Số lượng</label>
        <input type="number" name="quantity" class="form-control">
        <label>Ảnh</label>
        <input type="file" name="image" class="form-control">
        <label>Mô tả</label>
        <textarea name="description" class="form-control"></textarea>
        <label>Trạng thái</label>
        <select name="status" class="form-control">
        <option value="1">Hiển thị</option>
        <option value="0">Ẩn</option>
        </select>

    <br>

        <button class="btn btn-success">Lưu</button>

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
</script>
@endsection