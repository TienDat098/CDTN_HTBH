@extends('admin.layouts.app')

@section('title', 'Tạo Mã Giảm Giá')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<div class="mb-4">
    <h3 class="fw-bold text-dark m-0">Tạo Mã Giảm Giá Mới</h3>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.promotions.store') }}" method="POST">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold small">Mã Code (Ví dụ: SUMMER2025) <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control" value="{{ old('code') }}" required style="text-transform: uppercase;">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold small">Số lượng mã (Số lượt dùng) <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" class="form-control" value="{{ old('quantity', 100) }}" min="1" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold small">Loại giảm <span class="text-danger">*</span></label>
                    <select name="discount_type" class="form-select" required>
                        <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Số tiền cố định (VNĐ)</option>
                        <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small">Giá trị giảm <span class="text-danger">*</span></label>
                    <input type="number" name="discount_value" class="form-control" value="{{ old('discount_value') }}" min="1" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small">Đơn tối thiểu (Áp dụng từ)</label>
                    <input type="number" name="min_order_value" class="form-control" value="{{ old('min_order_value', 0) }}" min="0">
                </div>
            </div>

            <div class="row mb-4 border-bottom pb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold small">Ngày giờ bắt đầu <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold small">Ngày giờ kết thúc <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary fw-bold px-4">
                    <i class="bi bi-save me-1"></i> Lưu mã
                </button>
                <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary px-4">Quay lại</a>
            </div>
        </form>
    </div>
</div>
@endsection