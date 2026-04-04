@extends('admin.layouts.app') 

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<div class="container-fluid mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Quản lý Bài Viết (Blog)</h2>
        <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Thêm bài viết mới
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="5%">ID</th>
                        <th class="text-center" width="15%">Hình ảnh</th>
                        <th width="35%">Tiêu đề</th>
                        <th width="15%">Tác giả</th>
                        <th class="text-center" width="15%">Trạng thái</th>
                        <th class="text-center" width="15%">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blogs as $blog)
                        <tr>
                            <td class="text-center align-middle">{{ $blog->id }}</td>
                            <td class="text-center align-middle">
                                <img src="{{ $blog->image ? asset('storage/' . $blog->image) : asset('images/no-image.png') }}" 
                                     alt="Hình ảnh" 
                                     class="img-thumbnail" 
                                     style="width: 80px; height: 60px; object-fit: cover;">
                            </td>
                            <td class="align-middle fw-medium">{{ $blog->title }}</td>
                            <td class="align-middle">{{ $blog->author->name ?? 'Không xác định' }}</td>
                            
                            <td class="text-center align-middle">
                                @if($blog->status == 1)
                                    <span class="badge bg-success rounded-pill px-3 py-2 status-badge">
                                        <i class="bi bi-check-circle-fill me-1"></i> Hiện
                                    </span>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-3 py-2 status-badge">
                                        <i class="bi bi-eye-slash-fill me-1"></i> Ẩn
                                    </span>
                                @endif
                            </td>
                            
                            
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center gap-2">
                                    
                                    <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-outline-primary btn-action" title="Sửa">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    
                                    <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-action" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Chưa có bài viết nào!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Phân trang -->
    <div class="d-flex justify-content-center mt-4">
        {{ $blogs->links('pagination::bootstrap-5') }}
    </div>
</div>

<style>
    /* Ép kích thước nút vuông vức và icon nằm chính giữa giống hệt ảnh mẫu */
    .btn-action {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 4px;
    }
    
    /* Chỉnh chữ trong badge to và rõ hơn 1 chút */
    .status-badge {
        font-size: 13px;
        font-weight: 500;
    }
</style>
@endsection