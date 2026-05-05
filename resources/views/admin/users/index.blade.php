@extends('admin.layouts.app') 
@section('title', 'Quản lý Người dùng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold"> Danh sách Người dùng</h2>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 bg-white">
                <thead class="table-dark">
                    <tr>
                        <th class="py-3 px-3">ID</th>
                        <th>Thông tin</th>
                        <th>Liên hệ</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="fw-bold px-3">#{{ $user->id }}</td>
                        
                        <td>
                            <div class="fw-bold text-dark fs-6">{{ $user->name }}</div>
                            <div class="text-muted small mt-1">
                                <i class="bi bi-clock me-1"></i>{{ $user->created_at->format('d/m/Y') }}
                            </div>
                        </td>

                        <td>
                            <div class="text-dark mb-1">
                                <i class="bi bi-envelope-fill text-secondary me-2"></i>{{ $user->email }}
                            </div>
                            <div class="text-dark">
                                <i class="bi bi-telephone-fill text-secondary me-2"></i>{{ $user->phone ?? 'Chưa cập nhật' }}
                            </div>
                        </td>
                        
                        <td>
                            @if($user->role == 'admin')
                                <span class="badge bg-danger rounded-pill px-3 py-2">Chủ shop</span>
                            @elseif($user->role == 'staff')
                                <span class="badge bg-secondary rounded-pill px-3 py-2">Nhân viên</span>
                            @else
                                <span class="badge bg-primary rounded-pill px-3 py-2">Khách hàng</span>
                            @endif
                        </td>

                        <td>
                            @if($user->status == 1 || $user->status === null)
                                <span class="badge border border-success text-success bg-white px-3 py-2 rounded-1">Hoạt động</span>
                            @else
                                <span class="badge border border-secondary text-secondary bg-white px-3 py-2 rounded-1">Đã khóa</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-info text-info border-info bg-light">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này không?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger text-danger border-danger bg-light" {{ $user->id == auth()->id() ? 'disabled' : '' }}>
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
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