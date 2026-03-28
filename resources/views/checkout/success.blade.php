@extends('layouts.app')
@section('title', 'Đặt hàng thành công')

@section('content')
<div class="container py-5 text-center">
    <div class="card border-0 shadow rounded-4 p-5 mx-auto" style="max-width: 600px; background-color: #fff;">
        <i class="bi bi-check-circle-fill text-success" style="font-size: 6rem;"></i>
        <h2 class="fw-bold mt-3 text-dark">ĐẶT HÀNG THÀNH CÔNG!</h2>
        <p class="text-muted mt-2 fs-5">Cảm ơn bạn đã mua sắm tại Web Tạp Hóa.</p>
        
        <div class="bg-light p-3 rounded-3 my-4 border border-warning" style="background-color: #fffbeb !important;">
            <h5 class="mb-0 text-dark">Mã đơn hàng của bạn: <span class="text-danger fw-bold">{{ session('order_code') }}</span></h5>
            <small class="text-muted">Chúng tôi sẽ sớm liên hệ qua SĐT để xác nhận và giao hàng.</small>
        </div>

        <a href="{{ route('home') }}" class="btn btn-warning fw-bold px-5 py-3 rounded-pill fs-5 shadow-sm text-dark">
            <i class="bi bi-house-door-fill me-2"></i>QUAY VỀ TRANG CHỦ
        </a>
    </div>
</div>
@endsection