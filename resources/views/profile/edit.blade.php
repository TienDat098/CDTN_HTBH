@extends('layouts.app')

@section('content')
<div class="row mt-4 mb-5">
    <!-- SIDEBAR BÊN TRÁI -->
    <div class="col-lg-3 col-md-4 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center pt-4 pb-3">
                
                <!-- Avatar -->
                <div class="avatar-circle mx-auto mb-3">
                    <span class="fs-1 text-white fw-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    <div class="camera-icon shadow-sm">
                        <i class="bi bi-camera-fill"></i>
                    </div>
                </div>
                
                <h5 class="fw-bold mb-1">{{ Auth::user()->name }}</h5>
                <p class="text-muted small mb-4">{{ Auth::user()->email }}</p>

                <!-- Menu Sidebar -->
                <div class="list-group list-group-flush text-start custom-sidebar-menu">
                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'staff')
                        <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action text-white bg-danger mb-2 rounded border-0 fw-bold shadow-sm">
                            <i class="bi bi-speedometer2 me-2"></i> Về Trang quản trị
                        </a>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action active">
                        <i class="bi bi-person-fill me-2"></i> Hồ sơ của tôi
                    </a>
                    
                    <a href="{{ route('profile.orders') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-receipt me-2"></i> Đơn mua
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="list-group-item list-group-item-action text-danger mt-2">
                            <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                        </button>
                    </form>
                </div>
                
            </div>
        </div>
    </div>

    <!-- NỘI DUNG BÊN PHẢI -->
    <div class="col-lg-9 col-md-8">
        
        <!-- BLOCK 1: THÔNG TIN TÀI KHOẢN -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2">
                <h5 class="text-primary fw-bold mb-0">Thông tin tài khoản</h5>
            </div>
            <div class="card-body">
                <hr class="mt-0 mb-4 text-muted">
                
                <!-- Hiển thị thông báo khi cập nhật thành công -->
                @if (session('status') === 'profile-updated')
                    <div class="alert alert-success py-2 small fw-bold">
                        <i class="bi bi-check-circle-fill me-1"></i> Đã cập nhật thông tin thành công!
                    </div>
                @endif
                
                <form method="post" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label text-muted small">Họ và tên</label>
                            <input type="text" name="name" class="form-control border-secondary-subtle" value="{{ old('name', Auth::user()->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control border-secondary-subtle" value="{{ old('phone', Auth::user()->phone ?? '') }}" placeholder="Chưa cập nhật">
                        </div>
                    </div>

            
                </form>

            </div>
        </div>

        

    </div>
</div>

<style>
    /* CSS tuỳ chỉnh cho Profile Bootstrap */
    .avatar-circle {
        width: 100px;
        height: 100px;
        background-color: #6c4e4e;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    
    .camera-icon {
        position: absolute;
        bottom: 0;
        right: 0px;
        background: #0d6efd;
        color: white;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
        cursor: pointer;
    }

    .custom-sidebar-menu .list-group-item {
        border: none;
        padding: 12px 16px;
        font-weight: 500;
        color: #495057;
        margin-bottom: 2px;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .custom-sidebar-menu .list-group-item.active {
        background-color: #0d6efd;
        color: white;
    }

    .custom-sidebar-menu .list-group-item:hover:not(.active) {
        background-color: #f8f9fa;
        color: #0d6efd;
    }

    .custom-sidebar-menu button.text-danger:hover {
        background-color: #fff5f5 !important;
        color: #dc3545 !important;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #0d6efd;
    }
</style>
@endsection