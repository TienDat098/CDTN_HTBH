@extends('layouts.app')

@section('title', 'Quên mật khẩu - Web Tạp Hóa')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0 rounded-4">
                
                <div class="card-header bg-primary text-white text-center py-3 rounded-top-4">
                    <h4 class="mb-0 fw-bold">
                        <i class="bi bi-shield-lock-fill me-2"></i>QUÊN MẬT KHẨU
                    </h4>
                </div>

                <div class="card-body p-4 p-md-5">
                    
                    <div class="mb-4 text-muted text-center" style="font-size: 0.95rem;">
                        Trí nhớ đôi khi cũng "đãng trí"? Không sao cả! Chỉ cần nhập địa chỉ email bạn đã đăng ký, chúng tôi sẽ gửi cho bạn một đường link để tạo lại mật khẩu mới ngay lập tức.
                    </div>

                    <x-auth-session-status class="mb-4 text-success fw-bold text-center p-2 bg-success bg-opacity-10 border border-success rounded" :status="session('status')" />

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold text-dark">Địa chỉ Email đã đăng ký</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-danger border-end-0">
                                    <i class="bi bi-envelope-fill"></i>
                                </span>
                                <input id="email" type="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" required autofocus placeholder="Nhập email của bạn...">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="text-danger mt-1 small" />
                        </div>

                        <button class="btn btn-primary w-100 fw-bold py-2 mb-3 shadow-sm" style="font-size: 1.1rem;" type="submit">
                            <i class="bi bi-send-fill me-2"></i>GỬI LINK KHÔI PHỤC
                        </button>
                    </form>

                    <div class="text-center mt-3 pt-3 border-top">
                        <span class="text-muted">Chợt nhớ ra mật khẩu?</span>
                        <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none ms-1 hover-underline">
                            Quay lại Đăng nhập
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
    }
    .hover-underline:hover {
        text-decoration: underline !important;
    }
</style>
@endsection