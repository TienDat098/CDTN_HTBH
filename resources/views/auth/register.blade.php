@extends('layouts.app')

@section('title', 'Đăng ký thành viên - Chuỗi Tạp Hóa Việt')

@section('content')
<div class="container py-5 d-flex justify-content-center">
    
    <div class="w-100" style="max-width: 500px;">
        
        <div class="card shadow-sm border-0 py-5 px-4" style="border-radius: 16px;">
            <div class="card-body p-2">
                
                <div class="text-center mb-4 pb-2">
                    <h2 class="fw-bold text-primary mb-0">ĐĂNG KÝ THÀNH VIÊN</h2>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold text-dark small mb-1">Họ và tên</label>
                        <input id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                               name="name" value="{{ old('name') }}" required autofocus placeholder="Vd: Nguyễn Văn A"
                               style="font-size: 1rem; padding: 0.8rem 1rem; border-radius: 8px;">
                        <x-input-error :messages="$errors->get('name')" class="text-danger mt-1 small" />
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold text-dark small mb-1">Email</label>
                        <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required placeholder="email@gmail.com"
                               style="font-size: 1rem; padding: 0.8rem 1rem; border-radius: 8px;">
                        <x-input-error :messages="$errors->get('email')" class="text-danger mt-1 small" />
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label fw-bold text-dark small mb-1">Số điện thoại</label>
                        <input id="phone" type="text" class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                               name="phone" value="{{ old('phone') }}" required placeholder="Vd: 0912345678"
                               style="font-size: 1rem; padding: 0.8rem 1rem; border-radius: 8px;">
                        <x-input-error :messages="$errors->get('phone')" class="text-danger mt-1 small" />
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold text-dark small mb-1">Mật khẩu</label>
                        <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                               name="password" required placeholder="Nhập mật khẩu..."
                               style="font-size: 1rem; padding: 0.8rem 1rem; border-radius: 8px;">
                        <small class="text-muted" style="font-size: 0.8rem;">* Mật khẩu cần ít nhất 8 ký tự.</small>
                        <x-input-error :messages="$errors->get('password')" class="text-danger mt-1 small" />
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-bold text-dark small mb-1">Nhập lại mật khẩu</label>
                        <input id="password_confirmation" type="password" class="form-control form-control-lg" 
                               name="password_confirmation" required placeholder="Nhập lại mật khẩu trên..."
                               style="font-size: 1rem; padding: 0.8rem 1rem; border-radius: 8px;">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="text-danger mt-1 small" />
                    </div>

                    <button class="btn btn-primary w-100 fw-bold py-3 mt-2 mb-4" type="submit" style="font-size: 1.1rem; border-radius: 8px;">
                        Đăng Ký
                    </button>
                </form>

                <div class="text-center">
                    <span class="text-muted small">Đã có tài khoản?</span><br>
                    <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none small hover-underline mt-1 d-inline-block">
                        Đăng nhập ngay
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
    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }
    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus, 
    input:-webkit-autofill:active{
        -webkit-box-shadow: 0 0 0 30px white inset !important;
    }
</style>
@endsection