@extends('admin.layouts.app')

@section('title', 'Cập nhật Mã Giảm Giá')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<div class="mb-4">
    <h3 class="fw-bold text-dark m-0">Cập nhật Mã Giảm Giá: {{ $promotion->code }}</h3>
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

        <form action="{{ route('admin.promotions.update', $promotion->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold small">Mã Code (Ví dụ: SUMMER2025) <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $promotion->code) }}" required style="text-transform: uppercase;">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold small">Số lượng mã (Số lượt dùng) <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $promotion->quantity) }}" min="1" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold small">Loại giảm <span class="text-danger">*</span></label>
                    <select name="discount_type" class="form-select" required>
                        <option value="fixed" {{ old('discount_type', $promotion->discount_type) == 'fixed' ? 'selected' : '' }}>Số tiền cố định (VNĐ)</option>
                        <option value="percent" {{ old('discount_type', $promotion->discount_type) == 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small">Giá trị giảm <span class="text-danger">*</span></label>
                    <input type="number" name="discount_value" class="form-control" value="{{ old('discount_value', intval($promotion->discount_value)) }}" min="1" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small">Đơn tối thiểu (Áp dụng từ)</label>
                    <input type="number" name="min_order_value" class="form-control" value="{{ old('min_order_value', intval($promotion->min_order_value)) }}" min="0">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold small">Ngày giờ bắt đầu <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date', $promotion->start_date->format('Y-m-d\TH:i')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold small">Ngày giờ kết thúc <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date', $promotion->end_date->format('Y-m-d\TH:i')) }}" required>
                </div>
            </div>

            <div class="mb-4 border-bottom pb-4">
                <label class="form-label fw-bold small d-block">Trạng thái</label>
                <div class="form-check form-switch fs-5">
                    <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="statusSwitch" name="status" {{ $promotion->status == 1 ? 'checked' : '' }}>
                    <label class="form-check-label fs-6 ms-2 pt-1" for="statusSwitch">Đang hoạt động / Hiển thị</label>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success fw-bold px-4 text-dark shadow-sm">
                   Cập nhật mã
                </button>
                <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary px-4 shadow-sm">Quay lại</a>
            </div>
        </form>
    </div>
</div>
@endsection