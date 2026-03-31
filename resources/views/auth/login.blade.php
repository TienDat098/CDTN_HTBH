@extends('layouts.app')

@section('title', 'Đăng nhập - Web Tạp Hóa')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0 rounded-4">
                
                <div class="card-header bg-primary text-white text-center py-3 rounded-top-4">

                    <h4 class="mb-0 fw-bold">
                        <i class="bi bi-box-arrow-in-right me-2"></i>ĐĂNG NHẬP
                    </h4>
                </div>

                <div class="card-body p-4">
                    
                    <x-auth-session-status class="mb-4 text-success fw-bold text-center" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold text-dark">Địa chỉ Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-primary border-end-0">

                                    <i class="bi bi-envelope-fill"></i>
                                </span>
                                <input id="email" type="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" required autofocus placeholder="Nhập email của bạn...">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="text-danger mt-1 small" />
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold text-dark">Mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-danger border-end-0">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input id="password" type="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" 
                                       name="password" required placeholder="Nhập mật khẩu...">
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="text-danger mt-1 small" />
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                                <label class="form-check-label text-muted" for="remember_me">
                                    Ghi nhớ tài khoản
                                </label>
                            </div>
                            
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-danger text-decoration-none small fw-bold hover-underline">
                                    Quên mật khẩu?
                                </a>
                            @endif
                        </div>

                        <button class="btn btn-primary w-100 fw-bold py-2 mb-3 shadow-sm fs-5" type="submit">
                            ĐĂNG NHẬP
                        </button>
                    </form>

                    <div class="text-center mt-3 pt-3 border-top">
                        <span class="text-muted">Bạn chưa có tài khoản?</span>
                        <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none ms-1 hover-underline">
                            Đăng ký ngay
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
}

    .hover-underline:hover {
        text-decoration: underline !important;
    }
    .card {
    border: 1px solid #dbeafe;
}

</style>
@endsection