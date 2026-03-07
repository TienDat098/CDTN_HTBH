@extends('admin.layouts.app')
@section('title', 'Nhập hàng mới')

@section('content')
<div class="d-flex flex-column align-items-center justify-content-center min-vh-75">
    
    <div class="w-100" style="max-width: 800px;"> <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-dark"> Nhập hàng vào kho</h2>
            <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary px-4">
                Quay lại
            </a>
        </div>

        <div class="card shadow border-0 rounded-3">
            <div class="card-body p-5"> <form action="{{ route('admin.inventory.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-bold fs-5">Chọn Sản phẩm cần nhập</label>
                        <select name="product_id" class="form-select form-select-lg" required>
                            <option value=""> Chọn sản phẩm </option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">
                                    [{{ $product->barcode }}] - {{ $product->name }} 
                                    (Hiện có: {{ $product->stock->quantity ?? 0 }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold fs-5">Số lượng nhập thêm</label>
                        <input type="number" name="quantity" class="form-control form-control-lg" 
                               min="1" required placeholder="Ví dụ: 100">
                        <div class="form-text text-success mt-2">
                            <i class="bi bi-info-circle"></i> Số lượng này sẽ được cộng dồn trực tiếp vào tồn kho hiện tại.
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold fs-5">Ghi chú nhập hàng</label>
                        <textarea name="note" class="form-control" rows="4" 
                                  placeholder="Nhập thông tin nhà cung cấp, số hóa đơn hoặc lý do nhập hàng..."></textarea>
                    </div>

                    <div class="d-grid mt-5">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold py-3 shadow-sm">
                             XÁC NHẬN NHẬP KHO
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<style>
    /* Style bổ sung để căn giữa đẹp hơn */
    .min-vh-75 { min-height: 75vh; }
    .form-label { color: #495057; }
    .card { background-color: #ffffff; }
</style>
@endsection