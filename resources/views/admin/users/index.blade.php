@extends('admin.layouts.app') @section('title', 'Quản lý Người dùng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold"> Danh sách Người dùng</h2>
</div>


<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Vai trò</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td class="fw-bold">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? 'Chưa cập nhật' }}</td>
                        <td>{{ Str::limit($user->address, 30) ?? 'Chưa cập nhật' }}</td>
                        
                        <td>
                            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="role" class="form-select form-select-sm {{ $user->role == 'admin' ? 'bg-danger text-white' : ($user->role == 'staff' ? 'bg-primary text-white' : '') }}" onchange="this.form.submit()">
                                    <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Khách hàng</option>
                                    <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>Nhân viên</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Chủ shop</option>
                                </select>
                            </form>
                        </td>

                        <td class="text-center">
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này không?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" {{ $user->id == auth()->id() ? 'disabled' : '' }}>
                                    Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $users->links('pagination::bootstrap-5') }}
</div>
@endsection