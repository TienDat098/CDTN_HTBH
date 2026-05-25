@extends('admin.layouts.app')

@section('title', 'Quản lý Livestream')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">
        <i class="bi bi-broadcast-pin me-2 text-danger"></i>Quản lý Livestream
    </h4>

    <a href="{{ route('admin.livestreams.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Thêm livestream
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Tiêu đề</th>
                    <th>Video ID</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th width="180">Thao tác</th>
                </tr>
            </thead>

            <tbody>
                @forelse($livestreams as $live)
                    <tr>
                        <td>{{ $live->title }}</td>
                        <td>{{ $live->youtube_video_id }}</td>
                        <td>
                            @if($live->is_active)
                                <span class="badge bg-danger">Đang phát</span>
                            @else
                                <span class="badge bg-secondary">Tắt</span>
                            @endif
                        </td>
                        <td>{{ $live->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.livestreams.edit', $live) }}"
                               class="btn btn-sm btn-warning">
                                Sửa
                            </a>

                            <form action="{{ route('admin.livestreams.destroy', $live) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Xóa livestream này?')">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-danger">
                                    Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Chưa có livestream nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $livestreams->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection