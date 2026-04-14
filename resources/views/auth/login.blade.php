@extends('layouts.app')

@section('title', 'Đăng nhập - Chuỗi Tạp Hóa Việt')

@section('content')
<div class="container py-5 d-flex justify-content-center">
    
    <div class="w-100" style="max-width: 450px;">
        
        <div class="card shadow-sm border-0 py-5 px-4" style="border-radius: 16px;">
            <div class="card-body p-2">
                
                <div class="text-center mb-5">
                    <h2 class="fw-bold text-primary mb-3">ĐĂNG NHẬP</h2>
                    <p class="text-muted fs-6">Chào mừng bạn đến với Chuỗi Tạp Hóa Việt</p>
                </div>
                
                <x-auth-session-status class="mb-4 text-success fw-bold text-center" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4 pb-2">
                        <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required autofocus placeholder="Địa chỉ Email" 
                               style="font-size: 1rem; padding: 1rem 1.2rem; border-radius: 8px;">
                        <x-input-error :messages="$errors->get('email')" class="text-danger mt-1 small" />
                    </div>

                    <div class="mb-4 pb-2">
                        <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                               name="password" required placeholder="Mật khẩu" 
                               style="font-size: 1rem; padding: 1rem 1.2rem; border-radius: 8px;">
                        <x-input-error :messages="$errors->get('password')" class="text-danger mt-1 small" />
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                            <label class="form-check-label text-muted" for="remember_me">
                                Ghi nhớ đăng nhập
                            </label>
                        </div>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-primary text-decoration-none small hover-underline">
                                Quên mật khẩu?
                            </a>
                        @endif
                    </div>

                    <button class="btn btn-primary w-100 fw-bold py-3 mb-4" type="submit" style="font-size: 1.1rem; border-radius: 8px;">
                        ĐĂNG NHẬP
                    </button>
                </form>

                <div class="text-center mt-3">
                    <span class="text-muted small">Chưa có tài khoản?</span>
                    <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none small ms-1 hover-underline">
                        Đăng ký ngay
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }
    .hover-underline:hover {
        text-decoration: underline !important;
    }
    /* Đổ bóng nhẹ nhàng cho form nổi bật trên nền trắng */
    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }
</style>
@endsection