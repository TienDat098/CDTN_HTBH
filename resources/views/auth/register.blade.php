@extends('layouts.app')

@section('title', 'Đăng ký tài khoản - Web Tạp Hóa')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0 rounded-4">
                
                <div class="card-header bg-primary text-white text-center py-3 rounded-top-4">
                    <h4 class="mb-0 fw-bold">
                        <i class="bi bi-person-plus-fill me-2"></i>ĐĂNG KÝ TÀI KHOẢN
                    </h4>
                </div>

                <div class="card-body p-4 p-md-5">
                    
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row">
                            
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-bold text-dark">Họ và tên</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-danger border-end-0">
                                        <i class="bi bi-person-fill"></i>
                                    </span>
                                    <input id="name" type="text" class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" 
                                           name="name" value="{{ old('name') }}" required autofocus placeholder="Vd: Nguyễn Tiến Đạt">
                                </div>
                                <x-input-error :messages="$errors->get('name')" class="text-danger mt-1 small" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label fw-bold text-dark">Số điện thoại</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-danger border-end-0">
                                        <i class="bi bi-telephone-fill"></i>
                                    </span>
                                    <input id="phone" type="text" class="form-control border-start-0 ps-0 @error('phone') is-invalid @enderror" 
                                           name="phone" value="{{ old('phone') }}" required placeholder="Vd: 0987654321">
                                </div>
                                <x-input-error :messages="$errors->get('phone')" class="text-danger mt-1 small" />
                            </div>

                            <div class="col-12 mb-3">
                                <label for="address" class="form-label fw-bold text-dark">Địa chỉ giao hàng</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-danger border-end-0">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </span>
                                    <input id="address" type="text" class="form-control border-start-0 ps-0 @error('address') is-invalid @enderror" 
                                           name="address" value="{{ old('address') }}" required placeholder="Số nhà, Tên đường, Xã/Phường...">
                                </div>
                                <x-input-error :messages="$errors->get('address')" class="text-danger mt-1 small" />
                            </div>

                        </div>

                        <hr class="text-muted my-4">

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="email" class="form-label fw-bold text-dark">Địa chỉ Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-danger border-end-0">
                                        <i class="bi bi-envelope-fill"></i>
                                    </span>
                                    <input id="email" type="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email') }}" required placeholder="Dùng để đăng nhập...">
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="text-danger mt-1 small" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-bold text-dark">Mật khẩu</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-danger border-end-0">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>
                                    <input id="password" type="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" 
                                           name="password" required placeholder="Ít nhất 8 ký tự...">
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="text-danger mt-1 small" />
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="password_confirmation" class="form-label fw-bold text-dark">Nhập lại Mật khẩu</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-danger border-end-0">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </span>
                                    <input id="password_confirmation" type="password" class="form-control border-start-0 ps-0" 
                                           name="password_confirmation" required placeholder="Nhập lại mật khẩu trên...">
                                </div>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="text-danger mt-1 small" />
                            </div>

                        </div>

                        <button class="btn btn-primary w-100 fw-bold py-2 mb-3 shadow-sm fs-5" type="submit">
                            HOÀN TẤT ĐĂNG KÝ
                        </button>
                    </form>

                    <div class="text-center mt-3 pt-3 border-top">
                        <span class="text-muted">Đã có tài khoản?</span>
                        <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none ms-1 hover-underline">
                            Đăng nhập ngay
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
    
    
    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus, 
    input:-webkit-autofill:active{
        -webkit-box-shadow: 0 0 0 30px white inset !important;
    }
</style>
@endsection