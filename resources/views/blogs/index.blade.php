@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-5">
    <h3 class="mb-4 fw-bold">Tin tức & Blog</h3>
    
    <div class="row">
        @foreach($blogs as $blog)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0 blog-card">
                    
                    <a href="{{ route('blogs.show', $blog->slug) }}" class="overflow-hidden">
                        <img src="{{ $blog->image ? asset('storage/' . $blog->image) : asset('images/no-image.png') }}" 
                             class="card-img-top blog-image" 
                             style="height: 220px; object-fit: cover;">
                    </a>
                    
                    <div class="card-body d-flex flex-column p-4">
                        
                        <a href="{{ route('blogs.show', $blog->slug) }}" class="text-decoration-none text-dark">
                            <h5 class="card-title fw-bold mb-3 blog-title">{{ $blog->title }}</h5>
                        </a>

                        <p class="card-text text-muted mb-4 blog-excerpt">
                            {{ Str::limit(strip_tags($blog->content), 120) }}
                        </p>

                        <div class="mt-auto d-flex justify-content-between align-items-center pt-3 border-top">
                            <div class="text-muted small d-flex align-items-center">
                                <i class="bi bi-calendar3 me-2"></i>
                                {{ $blog->created_at->format('d/m/Y') }}
                            </div>
                            <a href="{{ route('blogs.show', $blog->slug) }}" class="btn btn-outline-primary btn-sm px-3 rounded-1">
                                Xem thêm
                            </a>
                        </div>
                        
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $blogs->links('pagination::bootstrap-5') }}
    </div>
</div>

<style>
    /* Bo góc và viền chuẩn form mẫu */
    .blog-card {
        border: 1px solid #e5e7eb !important;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    /* Đổ bóng khi đưa chuột vào card */
    .blog-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1) !important;
    }
    /* Hiệu ứng zoom nhẹ ảnh nhìn cực kỳ chuyên nghiệp */
    .blog-image {
        transition: transform 0.4s ease;
    }
    .blog-card:hover .blog-image {
        transform: scale(1.05);
    }
    /* Đổi màu tiêu đề khi hover */
    .blog-title {
        transition: color 0.2s;
        line-height: 1.4;
    }
    .blog-title:hover {
        color: #3b82f6 !important; 
    }
    /* Khóa số dòng hiển thị của tóm tắt (Max 3 dòng) để các card luôn bằng nhau */
    .blog-excerpt {
        font-size: 0.95rem;
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection