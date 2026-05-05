@extends('admin.layouts.app')
@section('title', 'Cập nhật Người dùng')

@section('content')
<div class="row justify-content-center mt-3">
    <div class="col-md-6">
        <div class="card shadow border-0 rounded-3">
            
            <div class="card-header bg-primary text-white py-3 rounded-top-3">
                <h5 class="mb-0 fw-bold">Cập nhật người dùng: {{ $user->name }}</h5>
            </div>
            
            <div class="card-body p-4">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label fw-bold">Email (Không thể sửa)</label>
                        <input type="email" class="form-control bg-light text-muted" value="{{ $user->email }}" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Phân quyền (Vai trò cụ thể)</label>
                        <select name="role" class="form-select form-select-lg fs-6 border-primary">
                            <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Khách hàng (Member)</option>
                            <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>Nhân viên (Staff)</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Chủ shop (Admin)</option>
                        </select>
                        <small class="text-muted mt-1 d-block">* Mỗi vai trò sẽ có quyền hạn truy cập các phân khu khác nhau.</small>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold d-block mb-3">Trạng thái tài khoản</label>
                        
                        <input type="hidden" name="status" value="0">
                        
                        <div class="form-check form-switch" style="font-size: 1.25rem;">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="statusSwitch" {{ ($user->status == 1 || $user->status === null) ? 'checked' : '' }}>
                            <label class="form-check-label fs-6 mt-1 ms-2 text-dark" for="statusSwitch">Hoạt động / Khóa tài khoản</label>
                        </div>
                        <small class="text-muted mt-2 d-block">Gạt sang phải để kích hoạt, tắt để khóa tài khoản khách/nhân viên.</small>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-5">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary px-4 py-2 text-white border-0" style="background-color: #6c757d;">Quay lại</a>
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-bold border-0">Lưu thay đổi</button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Chỉnh nút gạt to và đẹp hơn một chút */
    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
        cursor: pointer;
    }
    .form-switch .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
</style>
@endsection